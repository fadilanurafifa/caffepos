<?php

// namespace App\Http\Controllers;

// use App\Models\Barang;
// use App\Models\Kategori;
// use App\Models\Produk;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;

// class BarangController extends Controller
// {
  
//     public function index(Request $request)
//     {
      
//         $query = Barang::with(['kategori', 'produk']);

      
//         if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
//             $query->whereBetween('tanggal_pembelian', [$request->tanggal_awal, $request->tanggal_akhir]);
//         }

  
//         $barang = $query->get();

   
//         $kategori = Kategori::all();
//         $produk = Produk::all();

//         $lastBarang = Barang::latest()->first();
//         $nextNumber = $lastBarang ? ((int)substr($lastBarang->kode_barang, -4)) + 1 : 1;
//         $kodeBarang = 'BRG-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

   
//         Log::info('Data Barang:', $barang->toArray());

//         return view('admin.barang.index', compact('barang', 'kategori', 'kodeBarang', 'produk'));
//     }


//     public function store(Request $request)
//     {
//         $request->validate([
//             'produk_id' => 'required|exists:produk,id',
//             'nama_barang' => 'required',
//             'satuan' => 'required',
//             'harga_jual' => 'required|numeric',
//             'stok' => 'required|numeric',
//             'tanggal_pembelian' => 'required|date',
//         ]);

   
//         $kodeBarang = $this->generateKodeBarang();

//         $barang = new Barang();
//         $barang->kode_barang = $kodeBarang;
//         $barang->produk_id = $request->produk_id;
//         $barang->nama_barang = $request->nama_barang;
//         $barang->satuan = $request->satuan;
//         $barang->harga_jual = $request->harga_jual;
//         $barang->stok = $request->stok;
//         $barang->tanggal_pembelian = $request->tanggal_pembelian; 
//         $barang->save();

//         return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
//     }

//     private function generateKodeBarang()
//     {
//         $lastBarang = Barang::latest()->first();
//         $nextNumber = $lastBarang ? ((int)substr($lastBarang->kode_barang, -4)) + 1 : 1;
//         return 'BRG-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
//     }

//     public function destroy($id)
//     {
//         Log::info("🔍 Menerima permintaan hapus barang ID: " . $id);
    
//         try {
//             $barang = Barang::find($id);
    
//             if (!$barang) {
//                 Log::warning("⚠️ Barang dengan ID $id tidak ditemukan!");
//                 return response()->json([
//                     'status' => 'error',
//                     'message' => "Barang dengan ID $id tidak ditemukan!"
//                 ], 404);
//             }
    
//             $barang->delete();
//             Log::info("✅ Barang ID $id berhasil dihapus!");
    
//             return response()->json([
//                 'status' => 'success',
//                 'message' => 'Barang berhasil dihapus!'
//             ]);
//         } catch (\Exception $e) {
//             Log::error("❌ Gagal menghapus barang: " . $e->getMessage());
    
//             return response()->json([
//                 'status' => 'error',
//                 'message' => 'Gagal menghapus barang! ' . $e->getMessage()
//             ], 500);
//         }
//     }
// }
