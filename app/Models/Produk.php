<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    protected $fillable = ['kategori_id', 'nama_produk', 'harga', 'foto', 'stok'];
    public function barang()
    {
        return $this->hasMany(Barang::class, 'produk_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }    
   
    
}

