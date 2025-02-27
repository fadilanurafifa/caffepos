<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori'; // Nama tabel
    protected $fillable = ['nama_kategori']; // Kolom yang bisa diisi

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori_id'); // Relasi ke model Barang
    }

    public function barang() {
        return $this->hasMany(Barang::class);
    }
    
    public function produk()
    {
        return $this->hasMany(Produk::class, 'kategori_id');
    }
}

