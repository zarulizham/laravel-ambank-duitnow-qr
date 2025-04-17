<?php

namespace ZarulIzham\DuitNowQR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DuitNowQRPayment extends Model
{
    protected $table = 'duitnow_qr_payments';

    protected $fillable = [
        'transaction_id',
        'end_id',
        'biz_id',
        'qr_string',
        'transaction_status',
        'reason',
        'sender_name',
        'amount',
        'currency',
        'account_number',
        'paid_at',
        'source_of_fund',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Get the DuitNowQRTransaction that owns the DuitNowQRPayment
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(DuitNowQRTransaction::class);
    }
}
