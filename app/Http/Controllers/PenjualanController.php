<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Barang;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PenjualanController extends Controller
{
    public function index()
    {

        $pelanggan = Pelanggan::all();
        // $barang = Barang::all();
        $penjualan = Penjualan::with('pelanggan')->paginate(10);
        $produk = Produk::select('id', 'nama_produk', 'harga')->get();

        return view('admin.penjualan.index', compact('pelanggan', 'penjualan', 'produk'));
    }

    public function store(Request $request)
    {
        Log::info('Request Penjualan:', $request->all());

        $validated = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'produk' => 'required|array|min:1',
            'produk.*.produk_id' => 'required|exists:produk,id',
            'produk.*.jumlah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $noFaktur = 'INV-' . now()->format('YmdHis') . rand(100, 999);

            $totalBayar = 0;

            $penjualan = Penjualan::create([
                'no_faktur' => $noFaktur,
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $validated['pelanggan_id'],
                'user_id' => Auth::id(),
                'metode_pembayaran' => $request->metode_pembayaran ?? 'cash',
            ]);

            foreach ($validated['produk'] as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $hargaJual = $produk->harga;
                $subTotal = $hargaJual * $item['jumlah'];
                $totalBayar += $subTotal;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'harga_jual' => $hargaJual,
                    'jumlah' => $item['jumlah'],
                    'sub_total' => $subTotal,
                ]);
            }

            $penjualan->update(['total_bayar' => $totalBayar]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'no_faktur' => $noFaktur,
                'total_bayar' => $totalBayar
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error Transaksi Penjualan: ' . $e->getMessage());

            return response()->json(['error' => 'Gagal menyimpan transaksi.'], 500);
        }
    }
}