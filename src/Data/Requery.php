<?php

namespace ZarulIzham\DuitNowQR\Data;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class Requery extends Data
{
    public function __construct(

        #[MapInputName('EndID')]
        public string $end_id,

        #[MapInputName('BizID')]
        public string $biz_id,

        #[MapInputName('QRString')]
        public ?string $qr_string,

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
    ) {}
}
