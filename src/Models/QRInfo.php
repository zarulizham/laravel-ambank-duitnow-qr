<?php

namespace ZarulIzham\DuitNowQR\Models;

use Illuminate\Database\Eloquent\Model;

class QRInfo extends Model
{
    protected $table = 'duitnow_qr_info';

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

    protected $casts = [
        'amount' => 'double',
        'request_payload' => 'object',
        'response_payload' => 'object',
    ];
}