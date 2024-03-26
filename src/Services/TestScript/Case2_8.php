<?php

namespace ZarulIzham\DuitNowQR\Services\TestScript;

use ZarulIzham\DuitNowQR\Facades\DuitNowQR;

class Case2_8
{
    public function run()
    {
        $response = DuitNowQR::generateQR(
            amount: 9,
            storeLabel: 'Store Label',
            referenceLabel: 'Case28',
            consumerLabel: 'ConsumerLabel',
            terminalLabel: null,
            referenceId: 'case 2.8 '.date('Ymd.His'),
            expiryMinutes: 87600,
        );

        $raw_image_string = base64_decode($response['QRCode']);
        $raw_image_string = str_replace('data:image/png;base64,', '', $raw_image_string);
        $raw_image_string = str_replace(' ', '+', $raw_image_string);
        $imageName = 'case2.8.png';
        \File::put(public_path().'/app/dnqr/test-script/'.$imageName, $raw_image_string);
    }
}
