<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $fillable = [
        'nama_barang',
        'deskripsi',
        'harga_sewa',
        'stok',
        'stok_cadangan',
        'category_id',
        'gambar',
        'status',
        'keterangan'
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($barang) {

            // Kurangi 10 untuk cadangan
            $barang->stok_ditampilkan = max(
                (int)$barang->stok - (int)$barang->stok_cadangan,
                0
            );
            // Otomatis ubah status
            if ($barang->stok_ditampilkan <= 0) {
                $barang->status = 'tidak tersedia';
            } else {
                $barang->status = 'tersedia';
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function detailTransaksis()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function barangRusaks()
    {
        return $this->hasMany(\App\Models\BarangRusak::class);
    }

    public function updateStokDitampilkan()
    {
        $this->stok_ditampilkan = max(
            $this->stok - $this->stok_cadangan,
            0
        );

        $this->save();
    }
}
