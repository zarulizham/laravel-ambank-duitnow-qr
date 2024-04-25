<?php

namespace ZarulIzham\DuitNowQR\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ZarulIzham\DuitNowQR\Models\QRInfo;

class CallbackController extends Controller
{
    public function __invoke(Request $request)
    {
        $qrInfo = QRInfo::where('qr_string', $request->QRString)->first();

        if ($qrInfo) {
            if ($qrInfo->transaction_status != 'ACSP') {
                $qrInfo->update([
                    'transaction_status' => $request->TransactionStatus,
                    'response_payload' => $request->all(),
                ]);
                if ($request->TransactionStatus == 'ACSP') {
                    if ((float) $request->TrxAmount == $qrInfo->amount) {
                        $bill = Bill::where('hash_id', $qrInfo->reference_id)->first();

                        if (! $bill->paid_at) {
                            $bill->update([
                                'paid_at' => now(),
                                'paid_amount' => $request->TrxAmount,
                                'payment_method' => 'DuitNow QR',
                                'status' => 'Paid',
                            ]);

                            // \Log::channel('dnqr')->debug([
                            //     'message' => 'Bill has been updated.',
                            // ]);

                            // NotifySubsystem::dispatch($bill);
                        } else {
                            // \Log::channel('dnqr')->warning([
                            //     'message' => 'Bill already updated.',
                            // ]);
                        }
                    } else {
                        // \Log::channel('dnqr')->debug([
                        //     'message' => 'Amount received did not same with requested.',
                        //     'request_amount' => $duitNowQrTransaction->amount,
                        //     'received_amount' => (float) $request->TrxAmount,
                        // ]);
                    }
                }
            }
        }

        return response()->json([
            'message' => 'DuitNow QR Callback Received.',
        ], 200);
    }
}
