<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;

class PelangganController extends Controller
{
    // Menampilkan daftar pelanggan
    public function index()
    {
        $pelanggan = Pelanggan::orderBy('created_at', 'desc')->get();
        return view('admin.pelanggan.index', compact('pelanggan'));
    }

    // Menyimpan pelanggan baru
   // Menyimpan pelanggan baru
public function store(Request $request)
{
    // Membuat kode pelanggan unik
    $kode_pelanggan = 'P-' . str_pad(Pelanggan::count() + 1, 5, '0', STR_PAD_LEFT);

    $request->validate([
        'nama' => 'required|string|max:255',
        'alamat' => 'required|string',
        'no_telp' => 'nullable|string',
        'email' => 'nullable|email',
    ]);

    // Menambahkan kode pelanggan sebelum menyimpan data
    Pelanggan::create([
        'kode_pelanggan' => $kode_pelanggan,  // Tambahkan kode pelanggan di sini
        'nama' => $request->nama,
        'alamat' => $request->alamat,
        'no_telp' => $request->no_telp,
        'email' => $request->email,
    ]);

    return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan');
}

    // Menampilkan form untuk edit
    public function edit($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return response()->json($pelanggan);
    }

    // Memperbarui data pelanggan
    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $pelanggan->update($request->all());

        return response()->json(['success' => true]);
    }

    // Menghapus data pelanggan
    public function destroy($id)
    {
        Pelanggan::destroy($id);

        return response()->json(['success' => true]);
    }
}
