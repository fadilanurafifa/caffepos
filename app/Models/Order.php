<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Nama tabel di database

    protected $fillable = [
        'nama_produk', 'status', 'user_id', 'jumlah', 'total_bayar'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }
}

