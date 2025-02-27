<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model {
    use HasFactory;

    protected $table = 'keranjang'; // Pastikan tabel sesuai di database

    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
        'sub_total'
    ];

    // Relasi ke Produk
    public function produk() {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
}
