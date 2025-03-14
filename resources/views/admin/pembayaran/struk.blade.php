<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran</title>
    <style>
        body { 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 12px; 
            text-align: center; 
        }
        .container { 
            width: 72mm; /* Lebih kecil dari 80mm agar tidak terpotong */
            margin: auto; 
            padding: 5px;
        }
        .title { 
            font-size: 14px; 
            font-weight: bold; 
        }
        .line { 
            border-top: 1px dashed black; 
            margin: 5px 0; 
        }
        table { 
            width: 100%; 
            text-align: left; 
            font-size: 11px;
        }
        td { 
            vertical-align: top; 
        }

        /* Atur ukuran cetak */
        @media print {
            @page {
                size: 80mm 80mm; /* Ukuran kertas thermal */
                margin: 0; /* Hapus margin agar sesuai */
            }
            body {
                font-size: 12px; 
                width: 80mm; 
            }
            .container {
                width: 72mm; /* Lebar lebih kecil agar tidak terpotong */
                padding: 5px;
            }
            .title {
                font-size: 14px;
            }
            .line {
                border-top: 1px dashed black;
            }
            table {
                font-size: 11px;
            }
            td {
                vertical-align: top;
            }
        }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 1000);">  
    <div class="container">
        <p class="title">Kasir Caffe</p>
        <p>Jalan Merdeka Belajar No.12<br>Bandung - Jawa Barat</p>
        <div class="line"></div>

        @if(isset($transaksi) && isset($detail_penjualan))
            <p>No Faktur: {{ $transaksi->no_faktur ?? 'Tidak tersedia' }}</p>
            <p>Tanggal: {{ isset($transaksi->created_at) ? date('d-m-Y H:i', strtotime($transaksi->created_at)) : 'Tidak tersedia' }}</p>
            <div class="line"></div>

            <table>
                @foreach ($detail_penjualan as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                        <td>{{ $detail->jumlah }} x Rp {{ number_format($detail->sub_total / max($detail->jumlah, 1), 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </table>

            <div class="line"></div>
            <p><strong>Total: Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</strong></p>
            <p>Bayar: Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}</p>
            <p>Kembalian: Rp 0</p>
        @else
            <p>Data tidak tersedia.</p>
        @endif
        
        <div class="line"></div>
        <p>Terima Kasih atas kunjungan Anda!<br>~ Kasir Caffe ~</p>
    </div>
</body>
</html>
