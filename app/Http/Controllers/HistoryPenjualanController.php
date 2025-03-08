<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;

class HistoryPenjualanController extends Controller
{
    public function index()
    {
        $detailTransaksi = DetailPenjualan::with(['produk', 'penjualan.pelanggan'])
            ->get()
            ->groupBy('penjualan_id');
    
            $transaksi = Penjualan::select(['id', 'pelanggan_id', 'total_bayar', 'status_pembayaran', 'created_at'])
            ->with('pelanggan')
            ->get()
            ->keyBy('id');
        
    
        return view('admin.history-penjualan.index', compact('detailTransaksi', 'transaksi'));
    }
    
}
