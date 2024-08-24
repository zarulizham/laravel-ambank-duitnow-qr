<?php

namespace ZarulIzham\DuitNowQR\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use ZarulIzham\DuitNowQR\Data\Requery;

class DuitNowQRRequery extends Model
{
    protected $table = 'duitnow_qr_requeries';

    protected $fillable = [
        'transaction_id',
        'source_reference_number',
        'transaction_date',
        'transaction_time_start',
        'transaction_time_end',
        'status_code',
        'response_payload',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'transaction_date' => 'date',
        'response_payload' => 'array',
    ];

    public function getResponseAttribute()
    {
        if ($this->status_code == '200') {
            return Requery::collect($this->response_payload, Collection::class);
        }

        return $this->response_payload;
    }
}
