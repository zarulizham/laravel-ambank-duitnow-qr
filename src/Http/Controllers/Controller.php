<?php

namespace App\Http\Controllers\DuitNowQR;

use ZarulIzham\DuitNowQR\DuitNowQR;
use App\Http\Controllers\Controller as BaseController;
use ZarulIzham\DuitNowQR\Models\DuitNowQRTransaction;
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

    public function status(DuitNowQRTransaction $transaction)
    {
        $duitNowQR = new DuitNowQR();

        $payment = $transaction->payments()
            ->whereNotNull('paid_at')
            ->latest('paid_at')
            ->first();

        $result = $duitNowQR->getStatus(
            $transaction->qr_string,
            $payment->paid_at
        );

        return response()->json($result);
    }
}
