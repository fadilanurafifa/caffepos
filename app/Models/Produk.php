<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produk';
    public $timestamps = true;
    protected $fillable = [
        'kategori_id', 
        'nama_produk',
        'harga', 
        'foto',
        'stok', 
        'created_at',
        'updated_at'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }    
   
    public function detail_penjualan()
    {     
        return $this->hasMany(DetailPenjualan::class, 'produk_id', 'id');
    }
    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'produk_id');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'produk_id');
    }
    

}

