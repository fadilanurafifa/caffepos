<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;

class HistoryPenjualanController extends Controller
{
    public function index()
    {
        // Mengambil data transaksi dengan relasi ke produk dan penjualan
        $detailTransaksi = DetailPenjualan::with(['produk', 'penjualan.pelanggan'])
            ->get()
            ->groupBy('penjualan_id');

        // Mengambil data penjualan agar dapat diakses di Blade
        $transaksi = Penjualan::with('pelanggan')->get()->keyBy('id');

        return view('admin.history-penjualan.index', compact('detailTransaksi', 'transaksi'));
    }
}
