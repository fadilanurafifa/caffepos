<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
{
    // Ambil transaksi dengan status 'pending' atau hanya milik pelanggan yang sedang aktif
    $transaksi = Penjualan::where('status_pembayaran', 'pending')
                          ->where('pelanggan_id', Auth::user()->id) // Jika ada login pelanggan
                          ->get();

    return view('admin.transaksi.index', compact('transaksi'));
}
public function bayar($id)
{
    $transaksi = Penjualan::findOrFail($id);
    $transaksi->status_pembayaran = 'lunas';
    $transaksi->save();

    return redirect()->route('admin.transaksi')->with('success', 'Transaksi berhasil dibayar!');
}

}

