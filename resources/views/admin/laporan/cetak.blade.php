<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .kop-surat { text-align: center; margin-bottom: 10px; position: relative; }
        .kop-surat img { width: 70px; height: auto; position: absolute; left: 20px; top: 10px; }
        .kop-surat h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop-surat p { margin: 2px 0; font-size: 12px; }
        .garis { border-bottom: 3px solid black; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        h2 { text-align: center; }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <img src="{{ public_path('assets/img/kasir.png') }}" alt="Logo">
        <h1>Cash Caffe POS</h1>
        <p>Jl. Merdeka Belajar No. 10, Kota Bandung, Jawa Barat - Indonesia</p>
        <p>Email: info@CashPOS.com | Telp: (021) 123456</p>
    </div>
    <div class="garis"></div>

    <h2>Laporan Penjualan Bulanan</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Stok Awal</th>
                <th>Terjual</th>
                <th>Keuntungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($laporan as $produk)
            <tr>
                <td>{{ $produk->nama_produk }}</td>
                <td>{{ $produk->stok_awal }}</td>
                <td>{{ $produk->terjual }}</td>
                <td>Rp{{ number_format($produk->keuntungan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
