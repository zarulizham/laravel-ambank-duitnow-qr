<?php

namespace ZarulIzham\DuitNowQR\Models;

use Illuminate\Database\Eloquent\Model;
use ZarulIzham\DuitNowQR\Models\QRInfo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QRPayment extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'duitnow_qr_payments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'end_id',
        'biz_id',
        'qr_string',
        'transaction_status',
        'reason',
        'sender_name',
        'amount',
        'currency',
        'account_number',
        'payment_received_at',
        'source_of_fund',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'amount' => 'float',
    ];

    /**
     * Get the qrInfo that owns the QRPayment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function qrInfo(): BelongsTo
    {
        return $this->belongsTo(QRInfo::class, 'qr_info_id');
    }
}