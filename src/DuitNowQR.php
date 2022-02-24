<?php

namespace ZarulIzham\DuitNowQR;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

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

    public function generateQR($amount)
    {
        $token = Cache::remember('duitnow_qr_token', config('duitnowqr.token_expiry'), fn () => $this->authenticate());

        $url = config('duitnowqr.url') . '/api/DuitNowQR/v1.0/GenQR';

        $response = Http::withHeaders([
            'Authorization' => $token,
            'AmBank-Timestamp' => now()->format('YmdHis'),
            'Channel-Token' => config('duitnowqr.channel_token'),
            'srcRefNo' => $this->getSrcRefNo(),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'ColourType' => config('duitnowqr.colour_type'),
            'QRWidth' => 1024,
            'QRHeight' => 1024,
        ])->post($url, [
            'QRId' => config('duitnowqr.qr_id'),
            'PointInitiation' => 12,
            'TrxCurrency' => 'MYR',
            'TrxAmount' => $amount,
            'AdditionalDataFieldTemplate' => "1",
            'StoreLabel' => "MBSP Aspire",
            'ReferenceLabel' => 'Payment',
            'ConsumerLabel' => 'ConsumerLabel',
            'TerminalLabel' => 'TerminalLabel',
        ]);

        return $response->json();
    }

    protected function getSrcRefNo()
    {
        $sequence = str_pad(Cache::increment('duitnow_qr_sequence'), 8, "0", STR_PAD_LEFT);

        return config('duitnowqr.prefix_id') . date('dmY') . $sequence;
    }
}
