<?php

namespace ZarulIzham\DuitNowQR\Data;

use Carbon\Carbon;
use DateTime;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use ZarulIzham\DuitNowQR\Models\DuitNowQRTransaction;

class Callback extends Data
{
    public function __construct(

        #[MapInputName('EndID')]
        public string $end_id,

        #[MapInputName('BizID')]
        public string $biz_id,

        #[MapInputName('QRString')]
        public string $qr_string,

        #[MapInputName('TransactionStatus')]
        public string $transaction_status,

        #[MapInputName('Reason')]
        public ?string $reason,

        #[MapInputName('SenderName')]
        public string $sender_name,

        #[MapInputName('TrxAmount')]
        public float $amount,

        #[MapInputName('TrxCurrency')]
        public string $currency,

        #[MapInputName('AccNo')]
        public string $account_number,

        #[MapInputName('PaymentDt')]
        public string $payment_date,

        #[MapInputName('PaymentHrs')]
        public string $payment_time,

        #[MapInputName('SourceofFund')]
        public string $source_of_fund,

        public ?DateTime $paid_at,

        public ?DuitNowQRTransaction $duitnow_qr_transaction,
    ) {
        $this->paid_at = Carbon::createFromFormat('d/m/Y H:i:s', $payment_date.' '.$payment_time);
        $this->duitnow_qr_transaction = DuitNowQRTransaction::query()
            ->select(
                'id',
                'reference_id',
                'reference_type',
                'source_reference_number',
                'transaction_status',
                'amount',
                'qr_string',
                'created_at',
            )
            ->where('qr_string', $qr_string)
            ->latest()
            ->first();

        $this->storePayment();
    }

    private function storePayment()
    {
        $this->duitnow_qr_transaction?->payments()->create($this->toArray());
    }

    public function excludeProperties(): array
    {
        return [
            'payment_date',
            'payment_time',
        ];
    }
}
