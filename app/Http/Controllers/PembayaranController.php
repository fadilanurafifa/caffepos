<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Order;
use App\Models\User;
use App\Notifications\PesananKeChefNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class PembayaranController extends Controller
{
    // Menampilkan detail transaksi berdasarkan no_faktur
    public function show($no_faktur) 
    {
        $transaksi = Penjualan::where('no_faktur', $no_faktur)->first(); // Ambil transaksi berdasarkan nomor faktur

        if (!$transaksi) {
            return redirect()->route('admin.pembayaran.index')->with('error', 'Transaksi tidak ditemukan.'); // Redirect jika transaksi tidak ditemukan
        }

        $detail_penjualan = DetailPenjualan::with('produk')->where('penjualan_id', $transaksi->id)->get(); // Ambil detail transaksi dan produk terkait

        return view('admin.pembayaran.show', compact('transaksi', 'detail_penjualan')); // Kirim data ke tampilan pembayaran
    }

    // Memproses pembayaran transaksi
    public function bayar(Request $request, $no_faktur)
    {
        $transaksi = Penjualan::where('no_faktur', $no_faktur)->first(); // Cari transaksi berdasarkan nomor faktur
        
        if (!$transaksi) {
            return redirect()->route('admin.pembayaran.index')->with('error', 'Transaksi tidak ditemukan.'); // Redirect jika transaksi tidak ditemukan
        }

        // Validasi jumlah bayar, harus lebih dari atau sama dengan total bayar
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:' . $transaksi->total_bayar,
        ]);

        $jumlah_bayar = $request->jumlah_bayar; // Ambil jumlah bayar dari input
        $kembalian = $jumlah_bayar - $transaksi->total_bayar; // Hitung kembalian jika ada

        // Jika jumlah bayar kurang dari total, kembalikan error
        if ($jumlah_bayar < $transaksi->total_bayar) {
            return redirect()->back()->with('error', 'Jumlah bayar kurang dari total yang harus dibayar.');
        }

        // Update status transaksi menjadi "lunas"
        $transaksi->update([
            'status_pembayaran' => 'lunas',
        ]);

        // Ambil semua detail transaksi
        $detail_penjualan = DetailPenjualan::where('penjualan_id', $transaksi->id)->get();

        // Kurangi stok produk sesuai jumlah yang dibeli
        foreach ($detail_penjualan as $detail) {
            $produk = $detail->produk;
            if ($produk) {
                if ($produk->stok >= $detail->jumlah) {
                    $produk->stok -= $detail->jumlah; // Kurangi stok
                    $produk->save(); // Simpan perubahan
                } else {
                    return redirect()->back()->with('error', 'Stok tidak mencukupi untuk produk: ' . $produk->nama_produk); // Jika stok tidak cukup, kembalikan error
                }
            }
        }

        return redirect()->route('admin.pembayaran.show', $no_faktur) // Redirect ke halaman pembayaran
            ->with('success', 'Pembayaran berhasil! Stok produk telah diperbarui.') 
            ->with('jumlah_bayar', $jumlah_bayar) 
            ->with('kembalian', $kembalian);
    }

    // Mencetak struk pembayaran dalam tampilan HTML
    public function print($no_faktur)
    {
        $transaksi = Penjualan::where('no_faktur', $no_faktur)->first(); // Cari transaksi berdasarkan nomor faktur
    
        if (!$transaksi) {
            return back()->with('error', 'Transaksi tidak ditemukan'); // Redirect jika transaksi tidak ditemukan
        }

        $detail_penjualan = DetailPenjualan::where('penjualan_id', $transaksi->id)->get(); // Ambil detail transaksi
    
        return view('admin.pembayaran.struk', compact('transaksi', 'detail_penjualan')); // Kirim data ke tampilan struk pembayaran
    }
}
