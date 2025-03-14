<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\Keranjang;
// use App\Models\Penjualan;
// use App\Models\Produk;
// use Illuminate\Support\Facades\Auth;

// class KeranjangController extends Controller {
//     // Menampilkan keranjang belanja
//     public function index() {
//         $keranjang = Keranjang::where('user_id', Auth::id())->get();
//         return view('keranjang.index', compact('keranjang'));
//     }

//     // Menambahkan produk ke keranjang
//     public function addToCart(Request $request, $produk_id) {
//         $produk = Produk::findOrFail($produk_id);
//         $jumlah = $request->jumlah;
//         $sub_total = $produk->harga * $jumlah;

//         Keranjang::create([
//             'user_id' => Auth::id(),
//             'produk_id' => $produk_id,
//             'jumlah' => $jumlah,
//             'sub_total' => $sub_total,
//         ]);

//         return redirect()->route('keranjang.index')->with('success', 'Produk ditambahkan ke keranjang!');
//     }

//     // Melakukan checkout
//     public function checkout() {
//         $keranjang = Keranjang::where('user_id', Auth::id())->get();
//         $total_harga = $keranjang->sum('sub_total');

//         $penjualan = Penjualan::create([
//             'user_id' => Auth::id(),
//             'total_harga' => $total_harga,
//             'metode_pembayar' => 'transfer', // Default
//             'status_pembayaran' => 'pending',
//         ]);

//         // Kosongkan keranjang setelah checkout
//         Keranjang::where('user_id', Auth::id())->delete();

//         return redirect()->route('pembayaran.show', $penjualan->id);
//     }

//     // Menampilkan halaman pembayaran
//     public function showPembayaran($id) {
//         $penjualan = Penjualan::findOrFail($id);
//         return view('pembayaran.show', compact('penjualan'));
//     }

//     // Mengonfirmasi pembayaran
//     public function confirmPayment(Request $request, $id) {
//         $penjualan = Penjualan::findOrFail($id);
//         $penjualan->update(['status_pembayaran' => 'lunas']);

//         return redirect()->route('dashboard')->with('success', 'Pembayaran berhasil!');
//     }
// }

