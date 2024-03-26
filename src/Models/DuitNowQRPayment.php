<?php

namespace ZarulIzham\DuitNowQR\Models;

use Illuminate\Database\Eloquent\Model;

class DuitNowQRPayment extends Model
{
    protected $table = 'duitnow_qr_payments';

    protected $fillable = [
        'reference_id',
        'source_reference_number',
        'transaction_status',
        'amount',
        'request_payload',
        'response_payload',
        'qr_string',
        'qr_code',
    ];
}
