<?php

namespace ZarulIzham\DuitNowQR\Models;

use Illuminate\Database\Eloquent\Model;

class DuitNowQRTransaction extends Model
{
    protected $table = 'duitnow_qr_transactions';

    protected $fillable = [
        'reference_id',
        'source_reference_number',
        'transaction_status',
        'amount',
        'request_payload',
        'response_payload',
        'qr_string',
    ];

    protected $casts = [
        'amount' => 'double',
        'request_payload' => 'object',
        'response_payload' => 'object',
    ];
}
