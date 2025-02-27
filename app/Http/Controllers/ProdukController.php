<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    // Menampilkan daftar produk
    public function index(Request $request)
    {
        // dd('test');
        $kategori = Kategori::all(); // Ambil semua kategori

        $produk = Produk::with('kategori') // Pastikan relasi kategori dimuat
            ->when($request->kategori_id, function ($query) use ($request) {
                return $query->where('kategori_id', $request->kategori_id);
            })
            ->get();

            // dd($produk);

        return view('admin.produk.index', compact('produk', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'foto' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            'kategori_id' => 'nullable|exists:kategori,id',
        ]);

        // dd($request->all());

        // Produk::create($request->all());

        // Menangani upload foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/produk_fotos'), $fileName);
            Produk::create([
                'nama_produk' => $request->nama_produk,
                'stok' => $request->stok,
                'harga' => $request->harga,
                'foto' => $fileName,
                'kategori_id' => $request->kategori_id
            ]);
        }


        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil ditambahkan');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $produk = Produk::find($id);
        $produk->nama_produk = $request->nama_produk;
        $produk->harga = $request->harga;

        // Menangani update foto jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($produk->foto) {
                Storage::delete('public/foto_produk/' . $produk->foto);
            }

            $file = $request->file('foto');
            $path = $file->store('public/foto_produk');
            $produk->foto = basename($path); // Menyimpan nama file foto
        }

        $produk->save();

        return redirect()->route('admin.produk.index')->with('success', 'Produk berhasil diperbarui');
    }


    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Hapus foto produk jika ada
        if ($produk->foto) {
            Storage::delete('public/' . $produk->foto);
        }

        $produk->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil dihapus!'
        ]);
    }
}
