<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_sewa',
        'tanggal_kembali',
        'status',
        'total_harga',
        'foto_ktp'
    ];

    protected $casts = [
        'tanggal_sewa' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // RELASI DETAIL
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function dendas()
    {
        return $this->hasMany(\App\Models\Denda::class);
    }

    public function getTotalDendaAttribute()
    {
        return $this->dendas()->sum('jumlah');
    }

    public function getTotalTagihanAttribute()
    {
        return $this->total_harga + $this->total_denda;
    }

    public function getAdaDendaBelumLunasAttribute()
    {
        return $this->dendas()
            ->where('status', 'belum_dibayar')
            ->exists();
    }

    public function hitungTotal()
    {
        return $this->details->sum('subtotal');
    }

    public function isTerlambat()
    {
        if (!$this->tanggal_dikembalikan) {
            return false;
        }

        return $this->tanggal_dikembalikan->gt($this->tanggal_kembali);
    }

    public function selisihHariTerlambat()
    {
        if (!$this->isTerlambat()) {
            return 0;
        }

        return $this->tanggal_kembali
            ->diffInDays($this->tanggal_dikembalikan);
    }
}
