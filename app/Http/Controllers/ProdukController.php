<?php

namespace App\Http\Controllers;

use App\Models\Kategori; // Menggunakan model Kategori untuk mengambil kategori produk
use Illuminate\Http\Request;
use App\Models\Produk; // Menggunakan model Produk untuk mengambil dan mengelola produk
use Illuminate\Support\Facades\Storage; // Menggunakan Storage untuk mengelola file yang diunggah

class ProdukController extends Controller
{
    // Menampilkan daftar produk dan kategori
    public function index(Request $request)
    {
        $kategori = Kategori::all(); // Mengambil semua data kategori dari database

        // Mengambil produk berdasarkan kategori jika ada filter yang dipilih
        $produk = Produk::with('kategori')
            ->when($request->kategori_id, function ($query) use ($request) {
                return $query->where('kategori_id', $request->kategori_id);
            })
            ->get();

        // Menampilkan halaman daftar produk dengan data produk dan kategori
        return view('admin.produk.index', compact('produk', 'kategori'));
    }

    // Menyimpan produk baru ke database
    public function store(Request $request)
    {
        // Validasi input untuk memastikan data yang dikirim benar
        $request->validate([
            'nama_produk' => 'required|string|max:255', // Nama produk harus berupa string dengan maksimal 255 karakter
            'harga' => 'required|numeric', // Harga harus berupa angka
            'foto' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Foto harus berupa gambar dengan format tertentu dan ukuran maksimal 2MB
            'kategori_id' => 'nullable|exists:kategori,id', // kategori_id opsional, tetapi jika ada harus sesuai dengan data di tabel kategori
        ]);

        // Cek apakah ada file gambar yang diunggah
        if ($request->hasFile('foto')) {
            $file = $request->file('foto'); // Mengambil file dari request
            $fileName = time() . '.' . $file->getClientOriginalExtension(); // Membuat nama file unik dengan timestamp
            $file->move(public_path('assets/produk_fotos'), $fileName); // Memindahkan file ke folder penyimpanan

            // Menyimpan produk ke database
            Produk::create([
                'nama_produk' => $request->nama_produk, // Menyimpan nama produk
                'stok' => $request->stok, // Menyimpan jumlah stok produk
                'harga' => $request->harga, // Menyimpan harga produk
                'foto' => $fileName, // Menyimpan nama file gambar
                'kategori_id' => $request->kategori_id // Menyimpan ID kategori jika ada
            ]);
        }

        // Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    // Memperbarui stok produk berdasarkan ID
    public function updateStok(Request $request, $id)
    {
        // Validasi input agar stok minimal 0
        $request->validate([
            'stok' => 'required|integer|min:0'
        ]);

        // Mencari produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        // Memperbarui jumlah stok produk
        $produk->stok = $request->stok;
        $produk->save();

        // Mengembalikan respons JSON bahwa stok berhasil diperbarui
        return response()->json(['message' => 'Stok berhasil diperbarui!']);
    }

    // Menghapus produk berdasarkan ID
    public function destroy($id)
    {
        // Mencari produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        // Jika produk memiliki foto, hapus file foto dari penyimpanan
        if ($produk->foto) {
            $filePath = public_path('assets/produk_fotos/' . $produk->foto); // Menentukan path file
            if (file_exists($filePath)) { // Cek apakah file ada
                unlink($filePath); // Menghapus file gambar dari server
            }
        }

        // Menghapus produk dari database
        $produk->delete();

        // Mengembalikan response JSON bahwa produk berhasil dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus!'
        ]);
    }
}
