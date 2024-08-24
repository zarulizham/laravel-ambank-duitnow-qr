<?php

namespace ZarulIzham\DuitNowQR;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use ZarulIzham\DuitNowQR\Exceptions\BadRequest;
use ZarulIzham\DuitNowQR\Models\DuitNowQRTransaction;

class DuitNowQR
{
    public function authenticate()
    {
        $url = config('duitnowqr.url').'/api/oauth/v2.0/token';

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

    public function generateQR($amount, $storeLabel, $referenceLabel, $consumerLabel, $terminalLabel, $referenceId = null, $expiryMinutes = 60, $referenceType = null, $pointInitiation = '12')
    {
        $token = Cache::remember('duitnow_qr_token', config('duitnowqr.token_expiry'), fn () => $this->authenticate());

        $sourceReferenceNumber = $this->getSrcRefNo();

        if (config('duitnowqr.version') == 2) {
            $url = config('duitnowqr.url').'/api/DuitNowQR/v2.0/GenQR/'.$sourceReferenceNumber;
        } else {
            $url = config('duitnowqr.url').'/api/DuitNowQR/v1.0/GenQR';
        }

        $headers = [
            'Authorization' => 'Bearer '.$token,
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
            'PointInitiation' => $pointInitiation,
            'TrxCurrency' => '458',
            'AdditionalDataFieldTemplate' => '1',
            'StoreLabel' => $storeLabel,
            'ReferenceLabel' => $referenceLabel,
            'ConsumerLabel' => $consumerLabel,
            'TerminalLabel' => $terminalLabel,
            'ExpiryMinutes' => $expiryMinutes,
        ];

        if ($amount) {
            $body['TrxAmount'] = number_format($amount, 2, '.', '');
        }

        $response = Http::withHeaders($headers)->withOptions([
            'debug' => false,
        ])
            ->withBody(json_encode($body), 'application/json')
            ->post($url);

        if ($response->status() == 400) {
            throw new BadRequest($response['ResponseMessage']);
        }

        $this->saveTransaction($body, $response['QRString'], $response['QRCode'], $sourceReferenceNumber, $referenceId, $referenceType);

        return $response->json();
    }

    protected function getSrcRefNo()
    {
        $sequence = str_pad(Cache::increment('duitnow_qr_sequence'), 6, '0', STR_PAD_LEFT);

        return config('duitnowqr.prefix_id').date('dmY').$sequence;
    }

    public function saveTransaction($body, $qrString, $qrCode, $sourceReferenceNumber, $referenceId = null, $referenceType = null)
    {
        return DuitNowQRTransaction::create([
            'request_payload' => $body,
            'amount' => $body['TrxAmount'] ?? null,
            'reference_id' => $referenceId,
            'reference_type' => $referenceType,
            'qr_string' => $qrString,
            'qr_code' => $qrCode,
            'source_reference_number' => $sourceReferenceNumber,
            'transaction_status' => 'Created',
        ]);
    }

    public function getStatus($qrString, $transactionDate)
    {
        $token = Cache::remember('duitnow_qr_token', config('duitnowqr.token_expiry'), fn () => $this->authenticate());

        $sourceReferenceNumber = $this->getSrcRefNo();
        $url = config('duitnowqr.url')."/api/MerchantQR/v1.0/GetQTNotification/$sourceReferenceNumber";

        $ambankTimestamp = now()->format('dmYHis');

        $headers = [
            'Authorization' => 'Bearer '.$token,
            'Authentication' => $token,
            'AmBank-Timestamp' => $ambankTimestamp,
            'Channel-Token' => config('duitnowqr.channel_token'),
            'srcRefNo' => $sourceReferenceNumber,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Channel-APIKey' => config('duitnowqr.api_key'),
        ];

        $body = [
            'QRString' => $qrString,
            'TrxDate' => $transactionDate->format('d/m/Y'),
            'TrxTimeStart' => $transactionDate->clone()->setTime(0, 1, 0)->format('H:i'),
            'TrxTimeEnd' => $transactionDate->clone()->setTime(23, 59, 59)->format('H:i'),
        ];

        $bodyEscaped = str_replace('\\', '', json_encode($body));

        $headers['Ambank-Signature'] = $this->signature('/api/MerchantQR/v1.0/GetQTNotification/'.$sourceReferenceNumber, $ambankTimestamp, $body);

        $response = Http::withHeaders($headers)->withOptions([
            'debug' => false,
        ])
            ->withBody($bodyEscaped, 'application/json')
            ->post($url);

        return $response->json();
    }

    public function getStatusByTransaction(DuitNowQRTransaction $duitNowQRTransaction, Carbon $transactionDate, $transactionTimeStart, $transactionTimeEnd)
    {
        $token = Cache::remember('duitnow_qr_token', config('duitnowqr.token_expiry'), fn () => $this->authenticate());

        $sourceReferenceNumber = $this->getSrcRefNo();
        $url = config('duitnowqr.url')."/api/MerchantQR/v1.0/GetQTNotification/$sourceReferenceNumber";

        $ambankTimestamp = now()->format('dmYHis');

        $headers = [
            'Authorization' => 'Bearer '.$token,
            'Authentication' => $token,
            'AmBank-Timestamp' => $ambankTimestamp,
            'Channel-Token' => config('duitnowqr.channel_token'),
            'srcRefNo' => $sourceReferenceNumber,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Channel-APIKey' => config('duitnowqr.api_key'),
        ];

        try {
            $transactionTimeStart = $transactionDate->clone()->setTimeFromTimeString($transactionTimeStart);
            $transactionTimeEnd = $transactionDate->clone()->setTimeFromTimeString($transactionTimeEnd);
        } catch (\Throwable $th) {
            throw new Exception('Failed to read transaction time. Please use format H:i (Eg: 23:00)');
        }

        $body = [
            'QRString' => $duitNowQRTransaction->qr_string,
            'TrxDate' => $transactionDate->format('d/m/Y'),
            'TrxTimeStart' => $transactionTimeStart->format('H:i'),
            'TrxTimeEnd' => $transactionTimeEnd->format('H:i'),
        ];

        $bodyEscaped = str_replace('\\', '', json_encode($body));

        $headers['Ambank-Signature'] = $this->signature('/api/MerchantQR/v1.0/GetQTNotification/'.$sourceReferenceNumber, $ambankTimestamp, $body);

        $response = null;
        try {
            $response = Http::withHeaders($headers)->withOptions([
                'debug' => false,
            ])
                ->withBody($bodyEscaped, 'application/json')
                ->post($url);
        } catch (\Throwable $th) {
        }

        return $this->storeRequeryStatus($duitNowQRTransaction, $sourceReferenceNumber, $transactionTimeStart, $transactionTimeEnd, $response);
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

        $stringToHashBase64 = base64_encode($headerString).'.'.base64_encode($bodyString);
        $stringToHashBase64 = str_replace('=', '', $stringToHashBase64);

        $hash_hmac = hash_hmac('sha256', $stringToHashBase64, config('duitnowqr.api_secret'));
        $signatureHex = hex2bin($hash_hmac);
        $base64 = str_replace('=', '', base64_encode($signatureHex));

        return $base64;
    }

    public function storeRequeryStatus($duitNowQRTransaction, $sourceReferenceNumber, $transactionTimeStart, $transactionTimeEnd, $response)
    {
        $duitNowQRTransaction->requeries()->create([
            'source_reference_number' => $sourceReferenceNumber,
            'status_code' => $response?->status(),
            'response_payload' => $response?->body(),
            'transaction_date' => $transactionTimeStart->format('Y-m-d'),
            'transaction_time_start' => $transactionTimeStart->format('H:i:s'),
            'transaction_time_end' => $transactionTimeEnd->format('H:i:s'),
        ]);
    }
}
