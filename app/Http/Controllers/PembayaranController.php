<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;

class PembayaranController extends Controller
{
    public function show($no_faktur)
    {
        // Ambil transaksi berdasarkan no_faktur
        $transaksi = Penjualan::where('no_faktur', $no_faktur)->first();
    
        // Jika transaksi tidak ditemukan, kembalikan error
        if (!$transaksi) {
            return redirect()->route('admin.pembayaran.index')->with('error', 'Transaksi tidak ditemukan.');
        }
    
        // Ambil detail penjualan beserta data produk
        $detail_penjualan = DetailPenjualan::with('produk')->where('penjualan_id', $transaksi->id)->get();

        // dd($detail_penjualan);

        return view('admin.pembayaran.show', compact('transaksi', 'detail_penjualan'));
    }
    
    public function bayar(Request $request, $no_faktur)
    {
        // Validasi input jumlah bayar
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:' . Penjualan::where('no_faktur', $no_faktur)->value('total_bayar'),
        ]);
    
        // Ambil data transaksi berdasarkan no_faktur
        $transaksi = Penjualan::where('no_faktur', $no_faktur)->firstOrFail();
    
        // Hitung kembalian tanpa menyimpannya ke database
        $jumlah_bayar = $request->jumlah_bayar;
        $kembalian = $jumlah_bayar - $transaksi->total_bayar;
    
        // Update hanya status pembayaran
        $transaksi->update([
            'status_pembayaran' => 'lunas',
        ]);
    
        // Redirect dengan membawa jumlah bayar dan kembalian sebagai session flash
        return redirect()->route('admin.pembayaran.show', $no_faktur)
            ->with('success', 'Pembayaran berhasil!')
            ->with('jumlah_bayar', $jumlah_bayar)
            ->with('kembalian', $kembalian);
    }
    
}


