<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';
    // protected $fillable = ['penjualan_id', 'barang_id', 'harga_jual', 'jumlah', 'sub_total'];
    protected $guarded = [];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }
    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }
        
}
