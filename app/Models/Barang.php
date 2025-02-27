<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang'; // Nama tabel di database

    protected $fillable = [
        'kode_barang',
        'produk_id', 
        'nama_barang',
        'kategori_id',
        'satuan',
        'harga_jual',
        'stok'
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    
    // Relasi: Barang milik satu pemasok
    public function pemasok() {
        return $this->belongsTo(Pemasok::class);
    }
     // Relasi: Barang dapat masuk ke banyak transaksi penjualan
     public function penjualan() {
        return $this->belongsToMany(Penjualan::class, 'detail_penjualan')
                    ->withPivot('jumlah', 'subtotal')
                    ->withTimestamps();
    }
    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
}
