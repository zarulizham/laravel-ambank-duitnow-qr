<?php

namespace ZarulIzham\DuitNowQR\Services\TestScript;
use ZarulIzham\DuitNowQR\Facades\DuitNowQR;

class Case2_10 {

    public function run() {
        $response = DuitNowQR::generateQR(
            amount: 11,
            storeLabel: "Store Label",
            referenceLabel: "Case 2.10",
            consumerLabel:"Consumer Label",
            terminalLabel: null,
            referenceId: "case2.10_".date('Ymd_His'),
            expiryMinutes: 87600,
        );

        $raw_image_string = base64_decode($response['QRCode']);
        $raw_image_string = str_replace('data:image/png;base64,', '', $raw_image_string);
        $raw_image_string = str_replace(' ', '+', $raw_image_string);
        $imageName = 'case2.10.png';
        \File::put(storage_path(). '/app/dnqr/test-script/' . $imageName, $raw_image_string);
    }

}
