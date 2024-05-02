<?php

namespace ZarulIzham\DuitNowQR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DuitNowQRTransaction extends Model
{
    protected $table = 'duitnow_qr_transactions';

    protected $fillable = [
        'reference_id',
        'reference_type',
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

    /**
     * Get all of the payments for the DuitNowQRTransaction
     */
    public function payments(): HasMany
    {
        return $this->hasMany(DuitNowQRPayment::class, 'transaction_id');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
