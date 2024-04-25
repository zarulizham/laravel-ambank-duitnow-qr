<?php

namespace ZarulIzham\DuitNowQR\Services\TestScript;

use ZarulIzham\DuitNowQR\Facades\DuitNowQR;

class Case2_11
{
    public function run()
    {
        $response = DuitNowQR::generateQR(
            amount: 12,
            storeLabel: 'Store Label',
            referenceLabel: 'Case211',
            consumerLabel: 'ConsumerLabel',
            terminalLabel: 'TerminalLabel',
            referenceId: 'case 2.11 '.date('Ymd.His'),
            expiryMinutes: 5,
        );

        $raw_image_string = base64_decode($response['QRCode']);
        $raw_image_string = str_replace('data:image/png;base64,', '', $raw_image_string);
        $raw_image_string = str_replace(' ', '+', $raw_image_string);
        $imageName = 'case2.11.png';
        \File::put(public_path().'/app/dnqr/test-script/'.$imageName, $raw_image_string);
    }
}
