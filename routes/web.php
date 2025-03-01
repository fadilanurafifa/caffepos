<?php

use App\Http\Controllers\Admin\AboutController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

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
Route::get('/dashboard', [DashboardController::class, 'indexPage'])->name('dashboard');

// login
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth');

// barang 
Route::get('/admin/barang', [BarangController::class, 'index'])->name('barang.index');
Route::post('/admin/barang', [BarangController::class, 'store'])->name('barang.store');
Route::delete('/admin/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

// kategori
Route::get('admin/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::post('admin/kategori', [KategoriController::class, 'store'])->name('kategori.store');
Route::delete('/admin/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

// produk
Route::prefix('admin')->group(function () {
    Route::resource('produk', ProdukController::class)->names([
        'index' => 'admin.produk.index',
        'create' => 'admin.produk.create',
        'store' => 'admin.produk.store',
        'edit' => 'admin.produk.edit',
        'update' => 'admin.produk.update',
        'destroy' => 'admin.produk.destroy',
    ]);
});

// pelanggan 

Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::post('/pelanggan', [PelangganController::class, 'store'])->name('pelanggan.store');
Route::get('/pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('pelanggan.edit');
Route::put('/pelanggan/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
Route::delete('/pelanggan/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

// pemasok
Route::get('/pemasok', [PemasokController::class, 'index'])->name('pemasok.index');
Route::post('/pemasok', [PemasokController::class, 'store'])->name('pemasok.store');
Route::put('/pemasok/{id}', [PemasokController::class, 'update'])->name('pemasok.update');
Route::delete('/pemasok/{id}', [PemasokController::class, 'destroy'])->name('pemasok.destroy');

// penjualan
Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
Route::post('/penjualan/store', [PenjualanController::class, 'store'])->name('penjualan.store');

// keranjang 
Route::middleware(['auth'])->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/{id}/add', [KeranjangController::class, 'addToCart'])->name('keranjang.add');
    Route::post('/checkout', [KeranjangController::class, 'checkout'])->name('checkout');
    Route::get('/pembayaran/{id}', [KeranjangController::class, 'showPembayaran'])->name('pembayaran.show');
    Route::post('/pembayaran/{id}/confirm', [KeranjangController::class, 'confirmPayment'])->name('pembayaran.confirm');
});

// transaksi
Route::get('/admin/transaksi', [TransaksiController::class, 'index'])->name('admin.transaksi');
Route::get('/admin/transaksi/bayar/{id}', [TransaksiController::class, 'bayar'])->name('admin.transaksi.bayar');
