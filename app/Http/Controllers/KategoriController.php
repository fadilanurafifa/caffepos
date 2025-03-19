<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $kategori = Kategori::all(); // Mengambil semua data kategori dari database
        return view('admin.kategori.index', compact('kategori')); // Mengirimkan data kategori ke view
    }

    // Menyimpan kategori ke database
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'nama_kategori' => 'required|unique:kategori|max:100', // Nama kategori harus unik dan maksimal 100 karakter
        ]);

        try {
            // Menyimpan kategori baru ke database
            Kategori::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!'); // Redirect dengan pesan sukses
        } catch (\Exception $e) {
            return redirect()->route('kategori.index')->with('error', 'Gagal menambahkan kategori!'); // Redirect dengan pesan error
        }
    }   

    // Menghapus kategori dari database
    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id); // Cari kategori berdasarkan ID, atau gagal jika tidak ditemukan
            $kategori->delete(); // Hapus kategori dari database
    
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil dihapus!' // Kirim respons sukses dalam format JSON
            ]);
        } catch (\Exception $e) {
            \log("error", "Gagal menghapus kategori: " . $e->getMessage()); // Catat error di log jika gagal
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus kategori!' // Kirim respons error dalam format JSON
            ], 500);
        }
    }    
}
