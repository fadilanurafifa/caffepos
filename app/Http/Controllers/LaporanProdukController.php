<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanProdukController extends Controller
{
    public function laporanProduk()
    {
        $produk = Produk::all(); // Mengambil semua data produk
        return view('admin.laporan-produk.index', compact('produk'));
    }
    
    public function cetakLaporanProduk()
    {
        $produk = Produk::all();
        $pdf = Pdf::loadView('admin.laporan-produk.pdf', compact('produk'));
        return $pdf->download('laporan-produk.pdf');
    }
}
