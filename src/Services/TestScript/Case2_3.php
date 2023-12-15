<?php

namespace ZarulIzham\DuitNowQR\Services\TestScript;
use ZarulIzham\DuitNowQR\Facades\DuitNowQR;

class Case2_3 {

    public function run() {
        $response = DuitNowQR::generateQR(
            amount: 4,
            storeLabel: "Store Label",
            referenceLabel: "Case23",
            consumerLabel:"ConsumerLabel",
            terminalLabel: "TerminalLabel",
            referenceId: "case 2.3 ".date('Ymd.His'),
            expiryMinutes: 5,
        );

        $raw_image_string = base64_decode($response['QRCode']);
        $raw_image_string = str_replace('data:image/png;base64,', '', $raw_image_string);
        $raw_image_string = str_replace(' ', '+', $raw_image_string);
        $imageName = 'case2.3.png';
        \File::put(public_path(). '/app/dnqr/test-script/' . $imageName, $raw_image_string);
    }

}
