<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk.
     * Jika kategori dipilih, maka hanya produk dengan kategori tersebut yang ditampilkan.
     */
    public function index(Request $request)
    {
        // Mengambil semua kategori dari database
        $kategori = Kategori::all(); 

        // Mengambil semua produk dengan relasi kategori
        // Jika request memiliki kategori_id, filter produk berdasarkan kategori tersebut
        $produk = Produk::with('kategori')
            ->when($request->kategori_id, function ($query) use ($request) {
                return $query->where('kategori_id', $request->kategori_id);
            })
            ->get();

        // Mengembalikan view dengan data produk dan kategori
        return view('admin.produk.index', compact('produk', 'kategori'));
    }

    /**
     * Menyimpan produk baru ke database.
     * Validasi dilakukan sebelum penyimpanan.
     */
    public function store(Request $request)
    {
        // Validasi input yang dikirimkan oleh pengguna
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'foto' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048', // Foto harus berupa gambar dengan format tertentu
            'kategori_id' => 'nullable|exists:kategori,id', // kategori_id harus ada dalam tabel kategori
        ]);

        // Jika ada file foto yang diunggah
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension(); // Nama file unik dengan timestamp
            $file->move(public_path('assets/produk_fotos'), $fileName); // Pindahkan file ke folder penyimpanan

            // Simpan data produk ke database
            Produk::create([
                'nama_produk' => $request->nama_produk,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'foto' => $fileName, // Simpan nama file foto
                'kategori_id' => $request->kategori_id
            ]);
        }

        // Redirect kembali ke halaman daftar produk dengan pesan sukses
        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Mengupdate stok produk berdasarkan ID produk yang dipilih.
     * Validasi memastikan stok minimal 0.
     */
    public function updateStok(Request $request, $id)
    {
        // Validasi input stok
        $request->validate([
            'stok' => 'required|integer|min:0'
        ]);

        // Cari produk berdasarkan ID, jika tidak ditemukan akan menampilkan error 404
        $produk = Produk::findOrFail($id);

        // Perbarui stok produk
        $produk->stok = $request->stok;
        $produk->save();

        // Mengembalikan response JSON untuk memberi tahu bahwa stok berhasil diperbarui
        return response()->json(['message' => 'Stok berhasil diperbarui!']);
    }

    /**
     * Menghapus produk berdasarkan ID.
     * Jika produk memiliki foto, file foto akan dihapus dari penyimpanan.
     */
    public function destroy($id)
    {
        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        // Hapus foto dari penyimpanan jika ada
        if ($produk->foto) {
            $filePath = public_path('assets/produk_fotos/' . $produk->foto);
            if (file_exists($filePath)) {
                unlink($filePath); // Menghapus file dari server
            }
        }

        // Hapus produk dari database
        $produk->delete();

        // Mengembalikan response JSON untuk memberi tahu bahwa produk telah dihapus
        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus!'
        ]);
    }
}
