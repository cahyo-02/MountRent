<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nama'];

    // Relasi ke Barang
    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
