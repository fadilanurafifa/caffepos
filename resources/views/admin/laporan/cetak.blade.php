<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        /* Styling Umum */
        body {
            font-family: "Arial", sans-serif;
            font-size: 12px;
            background-color: #f4f4f4;
            padding: 20px;
        }
        
        /* Kop Surat */
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            position: relative;
            padding: 15px;
        }
        
        .kop-surat img {
            width: 80px;
            height: auto;
            position: absolute;
            left: 20px;
            top: 15px;
        }
        
        .kop-surat h1 {
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
            font-weight: bold;
            color: #333;
        }
        
        .kop-surat p {
            margin: 2px 0;
            font-size: 12px;
            color: #555;
        }
        
        .garis {
            border-bottom: 3px solid black;
            margin-top: 10px;
        }

        /* Styling Tabel */
        h2 {
            text-align: center;
            color: #333;
            font-size: 18px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: #fff;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        /* Header Tabel */
        th {
            background-color: #aec3c7;
            color: #333;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Baris Bergantian */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Hover Efek */
        tbody tr:hover {
            background-color: #f1f1f1;
            transition: 0.3s;
        }
            @media print {
        th {
            background: #aec3c7 !important; /* Warna tetap dicetak */
            color: #333 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
        
    </script>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop-surat">
        <img src="{{ asset('assets/img/kasir.png') }}" alt="Logo">
        <h1>Cash Caffe POS</h1>
        <p>Jl. Merdeka Belajar No. 10, Kota Bandung, Jawa Barat - Indonesia</p>
        <p>Email: info@CashPOS.com | Telp: (021) 123456</p>
    </div>
    <div class="garis"></div>

    <h2>Laporan Penjualan Bulanan</h2>
    <p style="text-align: center; font-weight: bold;">
        Periode: {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}
    </p>    
    <table>
        <thead>
            <tr class="bg-secondary text-white">
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
