<?php

namespace ZarulIzham\DuitNowQR\Services\TestScript;
use ZarulIzham\DuitNowQR\Facades\DuitNowQR;

class Case2_12 {

    public function run() {
        $response = DuitNowQR::generateQR(
            amount: 13,
            storeLabel: "Store Label",
            referenceLabel: "Case212",
            consumerLabel:"ConsumerLabel",
            terminalLabel: "TerminalLabel",
            referenceId: "case 2.12 ".date('Ymd.His'),
            expiryMinutes: 5,
        );

        $raw_image_string = base64_decode($response['QRCode']);
        $raw_image_string = str_replace('data:image/png;base64,', '', $raw_image_string);
        $raw_image_string = str_replace(' ', '+', $raw_image_string);
        $imageName = 'case2.12.png';
        \File::put(public_path(). '/app/dnqr/test-script/' . $imageName, $raw_image_string);
    }

}
