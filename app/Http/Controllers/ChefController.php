<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class ChefController extends Controller
{
    public function index()
    {
        $orders = Penjualan::where('status_pembayaran', 'lunas')
                            ->whereIn('status_pesanan', ['pending', 'proses memasak'])
                            ->get();

        return view('admin.chef.index', compact('orders'));
    }

    public function updateOrder(Request $request, $id)
    {
        $order = Penjualan::findOrFail($id);

        // Update status pesanan berdasarkan input yang dikirim dari form
        if ($request->status_pesanan == 'proses memasak') {
            $order->status_pesanan = 'proses memasak';
        } elseif ($request->status_pesanan == 'selesai') {
            $order->status_pesanan = 'selesai';
        }

        $order->save();

        return redirect()->back()->with('success', 'Status pesanan diperbarui.');
    }
}
