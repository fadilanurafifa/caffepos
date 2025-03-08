<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PenjualanController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        $penjualan = Penjualan::with('pelanggan')->paginate(10);
        $produk = Produk::select('id', 'nama_produk', 'harga', 'foto')->get();

        return view('admin.penjualan.index', compact('pelanggan', 'penjualan', 'produk'));
    }

    public function store(Request $request)
    {
        // ✅ Validasi input
        $validated = $request->validate([
            'pelanggan_id' => 'required|integer',
            'produk' => 'required|array|min:1',
            'produk.*.produk_id' => 'required|exists:produk,id',
            'produk.*.jumlah' => 'required|integer|min:1',
            'metode_pembayar' => 'required|string|max:50', // ✅ Kolom diperbaiki
        ]);

        Log::info('Request Penjualan:', $request->all());

        DB::beginTransaction();
        try {
            $totalBayar = 0;

            // ✅ Cek nomor faktur terakhir dan buat format baru
            $lastPenjualan = Penjualan::orderBy('id', 'desc')->first();

            if ($lastPenjualan && preg_match('/\d+/', $lastPenjualan->no_faktur, $matches)) {
                $lastNumber = (int) $matches[0];
                $no_faktur = 'FTR-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $no_faktur = 'FTR-00001';
            }

            // ✅ Simpan transaksi penjualan
            $penjualan = Penjualan::create([
                'no_faktur' => $no_faktur,
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $validated['pelanggan_id'],
                'user_id' => Auth::id() ?? 1,
                'metode_pembayar' => $validated['metode_pembayar'], // ✅ Perbaikan
                'status_pembayaran' => 'pending',
            ]);

            // ✅ Simpan detail penjualan dan hitung total bayar
            foreach ($validated['produk'] as $item) {
                $produk = Produk::findOrFail($item['produk_id']);
                $subTotal = $produk->harga * $item['jumlah'];
                $totalBayar += $subTotal;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'sub_total' => $subTotal,
                ]);
            }

            // ✅ Update total bayar setelah semua item dihitung
            $penjualan->update(['total_bayar' => $totalBayar]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan!',
                'no_faktur' => $penjualan->no_faktur,
                'total_bayar' => $penjualan->total_bayar,
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error Transaksi Penjualan:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan transaksi. ' . $e->getMessage(),
            ], 400);
        }
    }
}
