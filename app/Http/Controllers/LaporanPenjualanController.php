<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\DetailPenjualan;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanPenjualanController extends Controller
{
    public function index(Request $request)
    {
        $kategori_id = $request->input('kategori_id'); // Ambil kategori yang dipilih dari request
    
        // Ambil daftar kategori untuk dropdown
        $kategoriList = Kategori::all();
    
        // Query produk dengan relasi detailPenjualan, lalu filter berdasarkan kategori jika ada
        $laporan = Produk::with('detailPenjualan')
            ->when($kategori_id, function ($query) use ($kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->get();
    
        return view('admin.laporan.index', compact('laporan', 'kategoriList'));
    }
    public function cetakPDF(Request $request)
    {
        $kategori_id = $request->kategori_id;
    
        // Query laporan berdasarkan kategori yang dipilih
        $laporan = Produk::with('detailPenjualan')
            ->when($kategori_id, function ($query) use ($kategori_id) {
                return $query->where('kategori_id', $kategori_id);
            })
            ->get();
    
        foreach ($laporan as $produk) {
            $stok_awal = 100; // Stok awal tetap 100
            $terjual = $produk->detailPenjualan
                ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                ->sum('jumlah');
    
            $produk->stok_awal = $stok_awal;
            $produk->terjual = $terjual;
            $produk->keuntungan = $terjual * $produk->harga;
        }
    
        $pdf = Pdf::loadView('admin.laporan.cetak', compact('laporan'))->setPaper('a4', 'portrait');
    
        return $pdf->download('laporan_penjualan.pdf');
    }
    

}



