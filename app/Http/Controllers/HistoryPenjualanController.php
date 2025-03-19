<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;

class HistoryPenjualanController extends Controller
{
    // Menampilkan riwayat transaksi penjualan
    public function index()
    {
        // Mengambil semua detail transaksi, termasuk informasi produk dan pelanggan terkait
        $detailTransaksi = DetailPenjualan::with(['produk', 'penjualan.pelanggan']) // Memuat relasi produk dan pelanggan
            ->get() // Mengambil semua data dari tabel DetailPenjualan
            ->groupBy('penjualan_id'); // Mengelompokkan detail transaksi berdasarkan ID penjualan
    
        // Mengambil data transaksi utama dengan informasi pelanggan terkait
        $transaksi = Penjualan::select(['id', 'pelanggan_id', 'total_bayar', 'status_pembayaran', 'created_at']) // Memilih kolom yang diperlukan
            ->with('pelanggan') // Memuat relasi pelanggan
            ->get() // Mengambil semua data transaksi dari tabel Penjualan
            ->keyBy('id'); // Menjadikan ID sebagai key untuk akses lebih mudah
        
        // Menampilkan view 'admin.history-penjualan.index' dengan data transaksi dan detail transaksi
        return view('admin.history-penjualan.index', compact('detailTransaksi', 'transaksi'));
    }
}
