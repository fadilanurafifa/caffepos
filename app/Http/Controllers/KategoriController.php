<?php
namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Menampilkan daftar kategori
    public function index()
    {
        $kategori = Kategori::all();
        return view('admin.kategori.index', compact('kategori'));
    }

    // Menyimpan kategori ke database
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'nama_kategori' => 'required|unique:kategori|max:100',
        ]);

        try {
            Kategori::create([
                'nama_kategori' => $request->nama_kategori,
            ]);

            return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()->route('kategori.index')->with('error', 'Gagal menambahkan kategori!');
        }
    }   

    // Menghapus kategori
    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id); // Cek apakah kategori ada
            $kategori->delete(); // Hapus kategori
    
            return response()->json([
                'status' => 'success',
                'message' => 'Kategori berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            \log("error", "Gagal menghapus kategori: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus kategori!'
            ], 500);
        }
    }    

    
}
