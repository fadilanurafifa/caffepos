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

        return view('admin.penjualan.index', compact('pelanggan', 'penjualan', 'produk',));
    }

    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'pelanggan_id' => 'required',
            'produk' => 'required|array|min:1',
            'produk.*.produk_id' => 'required',
            'produk.*.jumlah' => 'required|integer|min:1',
        ]);

        Log::info('Request Penjualan:', $request->all());
        DB::beginTransaction();
        try {
            $totalBayar = 0;
            $no_faktur = Penjualan::latest()->first()->no_faktur ?? 0;

            $penjualan = Penjualan::create([
                'no_faktur' => $no_faktur + 1,
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $validated['pelanggan_id'],
                'user_id' => 1,
                'metode_pembayar' => $request->metode_pembayaran ?? 'cash',
            ]);

            foreach ($validated['produk'] as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $hargaJual = $produk->harga;
                $subTotal = $hargaJual * $item['jumlah'];
                $totalBayar += $subTotal;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id ?? null,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'sub_total' => $subTotal,
                ]);
            }

            $penjualan->update(['total_bayar' => $totalBayar]);

            DB::commit();

            return response()->json([
                'success' => true,
                'no_faktur' => $penjualan->no_faktur,
                'total_bayar' => $penjualan->total_bayar,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error Transaksi Penjualan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan transaksi.',
            ], 400);
        }
    }
}
