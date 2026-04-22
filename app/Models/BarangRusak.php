<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    protected $fillable = [
        'barang_id',
        'detail_id',
        'jumlah',
        'keterangan',
        'status',
        'gambar'
    ];

    // TAMBAHKAN INI AGAR LARAVEL OTOMATIS MENGUBAH ARRAY KE JSON DAN SEBALIKNYA
    protected $casts = [
        'gambar' => 'array',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function detail()
    {
        return $this->belongsTo(DetailTransaksi::class, 'detail_id');
    }
}
