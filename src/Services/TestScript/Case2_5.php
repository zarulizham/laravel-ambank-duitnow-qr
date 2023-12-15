<?php

namespace ZarulIzham\DuitNowQR\Services\TestScript;
use ZarulIzham\DuitNowQR\Facades\DuitNowQR;

class Case2_5 {

    public function run() {
        $response = DuitNowQR::generateQR(
            amount: 6,
            storeLabel: "Store Label",
            referenceLabel: "Case 2.5",
            consumerLabel:"Consumer Label",
            terminalLabel: null,
            referenceId: "case2.5_".date('Ymd_His'),
            expiryMinutes: 87600,
        );

        $raw_image_string = base64_decode($response['QRCode']);
        $raw_image_string = str_replace('data:image/png;base64,', '', $raw_image_string);
        $raw_image_string = str_replace(' ', '+', $raw_image_string);
        $imageName = 'case2.5.png';
        \File::put(storage_path(). '/app/dnqr/test-script/' . $imageName, $raw_image_string);
    }

}
