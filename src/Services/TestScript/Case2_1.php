<?php

namespace ZarulIzham\DuitNowQR\Services\TestScript;

use ZarulIzham\DuitNowQR\Facades\DuitNowQR;

class Case2_1
{
    public function run()
    {
        $response = DuitNowQR::generateQR(
            amount: 2,
            storeLabel: 'ASPIRE',
            referenceLabel: 'Case21',
            consumerLabel: 'ConsumerLabel',
            terminalLabel: 'TerminalLabel',
            referenceId: 'case 2.1 '.date('Ymd.His'),
            expiryMinutes: 87600,
        );

        $raw_image_string = base64_decode($response['QRCode']);
        $raw_image_string = str_replace('data:image/png;base64,', '', $raw_image_string);
        $raw_image_string = str_replace(' ', '+', $raw_image_string);
        $imageName = 'case2.1.png';
        \File::makeDirectory(public_path().'/app/dnqr/test-script/', 755, true, true);
        \File::put(public_path().'/app/dnqr/test-script/'.$imageName, $raw_image_string);
    }
}
