<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\HistoryPenjualanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LaporanProdukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::post('/kasir/read-notifications', function () {
    Auth::user()->unreadNotifications->markAsRead();
    return back();
})->name('kasir.readNotifications');


// admin
Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware('role:admin');

// kasir
Route::middleware([RoleMiddleware::class . ':kasir'])->group(function () {
    Route::get('/kasir', function () {
        return view('kasir.index');
    })->name('kasir.dashboard');
});

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('admin.login');
});

Route::group(['middleware' => ['role:admin,owner,chef']], function () {
Route::get('/dashboard', [DashboardController::class, 'indexPage'])->name('dashboard');
});

// login
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// kategori
Route::get('admin/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::post('admin/kategori', [KategoriController::class, 'store'])->name('kategori.store');
Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

// Route::group(['middleware' => ['role:admin']], function () {
// produk
Route::prefix('admin')->group(function () {
    Route::resource('produk', ProdukController::class)->names([
        'index' => 'admin.produk.index',
        'create' => 'admin.produk.create',
        'store' => 'admin.produk.store',
        'edit' => 'admin.produk.edit',
        'destroy' => 'admin.produk.destroy',
    ]);

    // Rute khusus untuk memperbarui stok saja
    Route::put('produk/{produk}/update-stok', [ProdukController::class, 'updateStok'])
        ->name('admin.produk.update-stok');
});
// });
    

Route::group(['middleware' => ['role:kasir,admin']], function () {
    // pelanggan 
    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
    Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
    Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
    Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');
    
    // penjualan
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');
    
    
    // pembayaran 
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
            Route::get('/{no_faktur}', [PembayaranController::class, 'show'])->name('show');
            Route::post('/bayar/{no_faktur}', [PembayaranController::class, 'bayar'])->name('bayar');
            Route::get('/print/{no_faktur}', [PembayaranController::class, 'print'])->name('print');
        });
    }); 
});

Route::group(['middleware' => ['role:owner,admin']], function () {
// history Transaksi
Route::get('/admin/history-penjualan', [HistoryPenjualanController::class, 'index'])->name('history.penjualan');

// laporan penjualan
Route::get('/admin/laporan-penjualan', [LaporanPenjualanController::class, 'index'])->name('admin.laporan.penjualan');
Route::get('/admin/laporan/cetak-pdf', [LaporanPenjualanController::class, 'cetakPDF'])->name('admin.laporan.cetak');
Route::get('/laporan-transaksi/export-pdf', [LaporanPenjualanController::class, 'exportPdf'])->name('laporan.transaksi.pdf');
Route::get('/cetak-laporan', [LaporanPenjualanController::class, 'cetakPDF'])->name('cetak.pdf');

// laporan produk
Route::get('/laporan-produk', [LaporanProdukController::class, 'laporanProduk'])->name('laporan.produk');
Route::get('/laporan-produk/cetak', [LaporanProdukController::class, 'cetakLaporanProduk'])->name('laporan.produk.pdf');
});

// halaman chef
Route::get('/chef/orders', [ChefController::class, 'index'])->name('chef.index');
Route::put('/chef/update-order/{id}', [ChefController::class, 'updateOrder'])->name('chef.updateOrder');