<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class ChefController extends Controller
{
    // Menampilkan daftar pesanan yang telah lunas dan masih dalam proses
    public function index()
    {
        $orders = Penjualan::where('status_pembayaran', 'lunas') // Ambil hanya pesanan dengan status pembayaran 'lunas'
                            ->whereIn('status_pesanan', ['pending', 'proses memasak']) // Filter pesanan yang masih menunggu atau sedang dimasak
                            ->get(); // Ambil data dari database

        return view('admin.chef.index', compact('orders')); // Tampilkan data ke dalam view 'admin.chef.index'
    }

    // Memperbarui status pesanan berdasarkan input dari form
    public function updateOrder(Request $request, $id)
    {
        $order = Penjualan::findOrFail($id); // Cari pesanan berdasarkan ID, atau tampilkan error jika tidak ditemukan

        // Perbarui status pesanan sesuai dengan input dari form
        if ($request->status_pesanan == 'proses memasak') {
            $order->status_pesanan = 'proses memasak'; // Ubah status menjadi 'proses memasak'
        } elseif ($request->status_pesanan == 'selesai') {
            $order->status_pesanan = 'selesai'; // Ubah status menjadi 'selesai'
        }

        $order->save(); // Simpan perubahan ke database

        return redirect()->back()->with('success', 'Status pesanan diperbarui.'); // Redirect kembali dengan pesan sukses
    }
}
