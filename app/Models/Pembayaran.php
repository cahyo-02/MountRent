<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'transaksi_id',
        'jumlah_bayar',
        'metode',
        'status',
        'bukti'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
