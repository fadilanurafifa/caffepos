<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan'; // Nama tabel di database

    protected $fillable = [
        'no_faktur',
        'tgl_faktur',
        'total_bayar',
        'pelanggan_id',
        'user_id',
        'metode_pembayar',
        'status_pembayaran',
    ];
    protected $guarded = []; // Memungkinkan mass assignment tanpa batasan

    // Relasi ke Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }

    protected $casts = [
        'total_bayar' => 'decimal:2', // Pastikan total_bayar ada di sini
    ];

}


