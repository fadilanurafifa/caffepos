<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function indexPage() 
    {
         // Menghitung total pemasukan dari tabel penjualan
        $totalIncome = DB::table('penjualan')->sum('total_bayar'); // Menggunakan kolom total_bayar

        // Target pemasukan yang ingin dicapai (bisa diubah sesuai kebutuhan)
         $targetIncome = 10000000; 

        // Hitung persentase pencapaian pemasukan
        $incomePercentage = $totalIncome > 0 ? ($totalIncome / $targetIncome) * 100 : 0;

        $totalBarang = DB::table('barang')->count(); // Menghitung total barang
        $totalPelanggan = DB::table('pelanggan')->count(); // Menghitung jumlah pelanggan

        $totalTransaksi = DB::table('penjualan')->count(); // Menghitung total transaksi
        $targetTransaksi = 100; // Target transaksi (bisa disesuaikan)
        
        // Hitung persentase pencapaian
        $persentase = $totalTransaksi > 0 ? ($totalTransaksi / $targetTransaksi) * 100 : 0;

         // Ambil data penjualan berdasarkan bulan
        $salesData = DB::table('penjualan')
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('SUM(total_bayar) as total'))
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // Konversi data agar sesuai dengan format Chart.js
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $salesByMonth = array_fill(0, 12, 0); // Inisialisasi array dengan 12 bulan

        foreach ($salesData as $data) {
        $salesByMonth[$data->month - 1] = $data->total; // Isi data sesuai bulan
        }

        return view('admin.pages.dashboard.index', compact('totalBarang', 'totalPelanggan', 'totalTransaksi', 'targetTransaksi', 'persentase', 'totalIncome', 'targetIncome', 'incomePercentage', 'salesByMonth', 'months'));
    }
}
