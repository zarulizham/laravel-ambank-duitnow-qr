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

        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Authentication' => $token,
            'AmBank-Timestamp' => now()->format('dmYHis'),
            'Channel-Token' => config('duitnowqr.channel_token'),
            'srcRefNo' => $this->getSrcRefNo(),
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

        $this->saveTransaction($body, $referenceId);

        $response = Http::withHeaders($headers)->withOptions([
            'debug' => false,
        ])
            ->withBody(json_encode($body), 'application/json')
            ->post($url);

        return $response->json();
    }

    protected function getSrcRefNo()
    {
        $sequence = str_pad(Cache::increment('duitnow_qr_sequence'), 6, "0", STR_PAD_LEFT);

        return config('duitnowqr.prefix_id') . date('dmY') . $sequence;
    }

    public function saveTransaction($body, $referenceId = null)
    {
        return DuitNowQRTransaction::create([
            'request_payload' => $body,
            'amount' => $body['TrxAmount'],
            'reference_id' => $referenceId,
            'transaction_status' => 'Created',
        ]);
    }
}
