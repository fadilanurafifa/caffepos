<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanProdukController extends Controller
{
    // Menampilkan halaman laporan produk
    public function laporanProduk()
    {
        $produk = Produk::all(); // Ambil semua data produk dari database
        return view('admin.laporan-produk.index', compact('produk')); // Kirim data ke tampilan laporan produk
    }

    // Mencetak laporan produk dalam format PDF
    public function cetakLaporanProduk()
    {
        $produk = Produk::all(); // Ambil semua data produk dari database
        return view('admin.laporan-produk.pdf', compact('produk')); // Kirim data ke tampilan PDF laporan produk
    }
}
