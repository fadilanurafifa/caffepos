<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan
    public function index()
    {
        $pelanggan = Pelanggan::orderBy('created_at', 'desc')->get(); // Ambil semua pelanggan, diurutkan dari yang terbaru
        return view('admin.pelanggan.index', compact('pelanggan')); // Kirim data pelanggan ke tampilan
    }

    // Menyimpan pelanggan baru
    public function store(Request $request)
    {
        // Membuat kode pelanggan unik, misalnya "P-00001"
        $kode_pelanggan = 'P-' . str_pad(Pelanggan::count() + 1, 5, '0', STR_PAD_LEFT);

        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        // Simpan data pelanggan ke database
        Pelanggan::create([
            'kode_pelanggan' => $kode_pelanggan, // Simpan kode pelanggan unik
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan');
    }

    // Menampilkan data pelanggan untuk diedit
    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id); // Cari pelanggan berdasarkan ID
        return response()->json($pelanggan); // Kembalikan data pelanggan dalam format JSON
    }

    // Memperbarui data pelanggan
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id); // Cari pelanggan berdasarkan ID

        $pelanggan->update($request->all()); // Perbarui semua data pelanggan

        return response()->json(['success' => true]); // Kembalikan respons sukses dalam format JSON
    }

    // Menghapus data pelanggan
    public function destroy($id)
    {
        Pelanggan::destroy($id); // Hapus pelanggan berdasarkan ID

        return response()->json(['success' => true]); // Kembalikan respons sukses dalam format JSON
    }
}
