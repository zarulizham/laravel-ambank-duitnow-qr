<?php

namespace ZarulIzham\DuitNowQR;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use ZarulIzham\DuitNowQR\Models\DuitNowQRTransaction;

class DuitNowQR
{
    public function authenticate()
    {
        $url = config('duitnowqr.url') . '/api/oauth/v2.0/token';

        $response = Http::asForm()
            ->withBasicAuth(config('duitnowqr.client_id'), config('duitnowqr.client_secret'))
            ->withHeaders([
                'ClientID' => config('duitnowqr.client_id'),
            ])->post($url, [
                'grant_type' => 'client_credentials',
                'scope' => 'resource.READ,resource.WRITE',
            ]);

        if (isset($response->object()->ResponseCode)) {
            throw new \Exception($response->object()->ResponseMessage, 400);
        }

        return $response->object()->access_token;
    }

    public function generateQR($amount, $storeLabel, $referenceLabel, $consumerLabel, $terminalLabel, $referenceId = null)
    {
        $token = Cache::remember('duitnow_qr_token', config('duitnowqr.token_expiry'), fn () => $this->authenticate());

        $url = config('duitnowqr.url') . '/api/DuitNowQR/v1.0/GenQR';

        $sourceReferenceNumber = $this->getSrcRefNo();
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Authentication' => $token,
            'AmBank-Timestamp' => now()->format('dmYHis'),
            'Channel-Token' => config('duitnowqr.channel_token'),
            'srcRefNo' => $sourceReferenceNumber,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'ColourType' => config('duitnowqr.colour_type'),
            'QRWidth' => 512,
            'QRHeight' => 512,
        ];

        $body = [
            'QRId' => config('duitnowqr.qr_id'),
            'PointInitiation' => '12',
            'TrxCurrency' => '458',
            'TrxAmount' => number_format($amount, 2),
            'AdditionalDataFieldTemplate' => "1",
            'StoreLabel' => $storeLabel,
            'ReferenceLabel' => $referenceLabel,
            'ConsumerLabel' => $consumerLabel,
            'TerminalLabel' => $terminalLabel,
        ];

        $response = Http::withHeaders($headers)->withOptions([
            'debug' => false,
        ])
            ->withBody(json_encode($body), 'application/json')
            ->post($url);

        $this->saveTransaction($body, $response['QRString'], $sourceReferenceNumber, $referenceId);

        return $response->json();
    }

    protected function getSrcRefNo()
    {
        $sequence = str_pad(Cache::increment('duitnow_qr_sequence'), 6, "0", STR_PAD_LEFT);
        \Log::debug([
            'sequence' => $sequence
        ]);
        return config('duitnowqr.prefix_id') . date('dmY') . $sequence;
    }

    public function saveTransaction($body, $qrString, $sourceReferenceNumber, $referenceId = null)
    {
        return DuitNowQRTransaction::create([
            'request_payload' => $body,
            'amount' => $body['TrxAmount'],
            'reference_id' => $referenceId,
            'qr_string' => $qrString,
            'source_reference_number' => $sourceReferenceNumber,
            'transaction_status' => 'Created',
        ]);
    }

    public function getStatus($srcRefNo, $qrString, $transactionDate)
    {
        $token = Cache::remember('duitnow_qr_token', config('duitnowqr.token_expiry'), fn () => $this->authenticate());

        $sourceReferenceNumber = $this->getSrcRefNo();
        $url = config('duitnowqr.url') . "/api/MerchantQR/v1.0/GetQTNotification/$sourceReferenceNumber";

        $ambankTimestamp = now()->format('dmYHis');


        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Authentication' => $token,
            'AmBank-Timestamp' => $ambankTimestamp,
            'Channel-Token' => config('duitnowqr.channel_token'),
            'srcRefNo' => $sourceReferenceNumber,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Channel-Token' => config('duitnowqr.channel_token'),
            'Channel-APIKey' => config('duitnowqr.api_key'),
        ];

        $body = [
            'QRString' => $qrString,
            'TrxDate' => $transactionDate->format('d/m/Y'),
            'TrxTimeStart' => $transactionDate->format('H:i'),
            'TrxTimeEnd' => now()->format('H:i'),
        ];

        $bodyEscaped = str_replace('\\', '', json_encode($body));

        // dd($body);

        $headers['Ambank-Signature'] = $this->signature('/api/MerchantQR/v1.0/GetQTNotification/' . $sourceReferenceNumber, $ambankTimestamp, $body);

        \Log::debug([
            'headers' => $headers,
            'stringToHash' => $this->stringToHash,
            'stringToHashBase64' => $this->stringToHashBase64,
            'body' => $body,
        ]);
        $response = Http::withHeaders($headers)->withOptions([
            'debug' => true,
        ])
            ->withBody($bodyEscaped, 'application/json')
            ->post($url);

        return $response->json();
    }

    public function signature($uri, $ambankTimestamp, $body): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT',
            'uri' => $uri,
            'iat' => $ambankTimestamp,
        ];

        $headerString = preg_replace('/\s+/', '', json_encode($header, JSON_UNESCAPED_SLASHES));
        $bodyString = preg_replace('/\s+/', '', json_encode($body, JSON_UNESCAPED_SLASHES));

        $stringToHash = $headerString . '.' . $bodyString;
        $stringToHashBase64 = base64_encode($headerString) . '.' . base64_encode($bodyString);
        $stringToHashBase64 = str_replace('=', '', $stringToHashBase64);
        $this->stringToHash = $stringToHash;
        $this->stringToHashBase64 = $stringToHashBase64;

        $hash_hmac = hash_hmac('sha256', $stringToHashBase64, config('duitnowqr.api_secret'));
        $signatureHex = hex2bin($hash_hmac);
        $base64 = str_replace('=', '', base64_encode($signatureHex));
        return $base64;
    }
}
