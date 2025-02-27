<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model {
    use HasFactory;

    protected $table = 'penjualan';
    // protected $fillable = ['user_id', 'total_harga', 'metode_pembayar', 'status_pembayaran'];
    protected $guarded = [];

    public function details()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }
    public function pelanggan() {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function detail_penjualan()
    {
        return $this->hasMany(DetailPenjualan::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
