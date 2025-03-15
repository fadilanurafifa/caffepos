<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Order;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\User;
use App\Notifications\OrderForChefNotification;
use App\Notifications\PesananMasukChefNotification;
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

        // Validasi input
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
    
            // Cek apakah ada transaksi sebelumnya untuk menentukan no_faktur
            $lastPenjualan = Penjualan::latest()->first();
           if ($lastPenjualan && preg_match('/\d+/', $lastPenjualan->no_faktur, $matches)) {
                $lastNumber = (int) $matches[0];
                $no_faktur = 'FTR-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $no_faktur = 'FTR-00001';
            }
    
            // Validasi apakah produk ada di database
            $produkIds = array_column($validated['produk'], 'produk_id');
            $produkTersedia = Produk::whereIn('id', $produkIds)->pluck('id')->toArray();
            foreach ($produkIds as $id) {
                if (!in_array($id, $produkTersedia)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Produk dengan ID ' . $id . ' tidak ditemukan.',
                    ], 400);
                }
            }
    
            // Simpan transaksi penjualan
            $penjualan = Penjualan::create([
                'no_faktur' => $no_faktur,
                'tgl_faktur' => now(),
                'total_bayar' => 0,
                'pelanggan_id' => $validated['pelanggan_id'],
                'user_id' => 1,
                'metode_pembayar' => $request->metode_pembayaran ?? 'cash',
            ]);
    
            // Simpan detail penjualan
            foreach ($validated['produk'] as $item) {
                $produk = Produk::find($item['produk_id']);
                $hargaJual = $produk->harga;
                $subTotal = $hargaJual * $item['jumlah'];
                $totalBayar += $subTotal;
    
                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $item['produk_id'],
                    'jumlah' => $item['jumlah'],
                    'sub_total' => $subTotal,
                ]);
            }
    
            // Update total bayar setelah semua item dihitung
            $penjualan->update(['total_bayar' => $totalBayar]);
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'no_faktur' => $penjualan->no_faktur,
                'total_bayar' => $penjualan->total_bayar,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error Transaksi Penjualan:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
    
            return response()->json([
                'success' => false,
                'error' => 'Gagal menyimpan transaksi. ' . $e->getMessage(),
            ], 400);
        }
    }  
    public function notifications()
    {
        $notifications = Auth::user()->notifications; // Ambil notifikasi kasir yang login
        return view('kasir.notifications', compact('notifications'));
    }    
}