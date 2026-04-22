<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'jenis',
        'keterangan',
        'jumlah',
        'status'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}
