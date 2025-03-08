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
        // Ambil transaksi berdasarkan no_faktur
        $transaksi = Penjualan::where('no_faktur', $no_faktur)->first();
        
        if (!$transaksi) {
            return redirect()->route('admin.pembayaran.index')->with('error', 'Transaksi tidak ditemukan.');
        }
    
        // Validasi input jumlah bayar
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:' . $transaksi->total_bayar,
        ]);
    
        $jumlah_bayar = $request->jumlah_bayar;
        $kembalian = $jumlah_bayar - $transaksi->total_bayar;
    
        // Pastikan jumlah bayar cukup sebelum mengubah status menjadi lunas
        if ($jumlah_bayar < $transaksi->total_bayar) {
            return redirect()->back()->with('error', 'Jumlah bayar kurang dari total yang harus dibayar.');
        }
    
        // Update status pembayaran
        $transaksi->update([
            'status_pembayaran' => 'lunas',
        ]);
    
        // Ambil semua detail penjualan terkait transaksi ini
        $detail_penjualan = DetailPenjualan::where('penjualan_id', $transaksi->id)->get();
    
        // Kurangi stok produk berdasarkan jumlah yang dibeli
        foreach ($detail_penjualan as $detail) {
            $produk = $detail->produk;
            if ($produk) {
                // Periksa apakah stok cukup sebelum menguranginya
                if ($produk->stok >= $detail->jumlah) {
                    $produk->stok -= $detail->jumlah;
                    $produk->save();
                } else {
                    return redirect()->back()->with('error', 'Stok tidak mencukupi untuk produk: ' . $produk->nama_produk);
                }
            }
        }
    
        return redirect()->route('admin.pembayaran.show', $no_faktur)
            ->with('success', 'Pembayaran berhasil! Stok produk telah diperbarui.')
            ->with('jumlah_bayar', $jumlah_bayar)
            ->with('kembalian', $kembalian);
    }
    
    
    
}


