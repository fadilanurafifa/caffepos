<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model {
    
    use HasFactory;
    
    protected $table = 'pelanggan';
    protected $fillable = [
        'kode_pelanggan', 
        'nama', 'alamat', 
        'no_telp', 
        'email'];

    // Auto-generate kode pelanggan
    public static function generateKodePelanggan() {
        $latest = self::latest()->first();
        $number = $latest ? intval(substr($latest->kode_pelanggan, 3)) + 1 : 1;
        return 'PLG' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function penjualan() {
        return $this->hasMany(Penjualan::class);
    }
}

