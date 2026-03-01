<?php

namespace App\Http\Controllers\DuitNowQR;

use App\Http\Controllers\Controller as BaseController;
use ZarulIzham\DuitNowQR\Requests\CallbackMessageRequest;

class Controller extends BaseController
{
    public function callback(CallbackMessageRequest $callbackMessageRequest)
    {
        \Log::channel('dnqr')->debug('DNQR', [
            'callback' => $callbackMessageRequest->all(),
        ]);

        $callbackData = $callbackMessageRequest->data();
        $duitNowQrTransaction = $callbackData->duitnow_qr_transaction;

        // Rest of your logic to handle the callback, e.g. update transaction status, etc.

        return response()->json([
            'message' => 'OK',
        ], 200);
    }
}