@extends('admin.layouts.base')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-chart-bar"></i> Laporan Penjualan
    </h1>
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.penjualan') }}" style="margin-bottom: 20px;">
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <label class="fw-bold mb-0" style="font-size: 14px;">Filter Kategori:</label>
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <select name="kategori_id" class="form-control custom-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm d-flex align-items-center" 
                        style="background-color: #89AC46; border-color: #789C40; color: white;">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="cetakLaporan()">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </button>
                </div>
            </form>
            
            <!-- Tabel Laporan dengan DataTables -->
            <div class="table-responsive" id="laporanArea">
                <table id="laporanTable" class="table table-bordered table-striped">
                    <thead class="bg-secondary text-white">
                        <tr>
                            <th>Nama Produk</th>
                            <th>Stok Awal</th>
                            <th>Terjual</th>
                            <th>Keuntungan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporan as $produk)
                            @php
                                $stok_awal = 100; // Stok awal per bulan
                                $terjual = $produk->detailPenjualan
                                    ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                                    ->sum('jumlah');

                                // Perhitungan keuntungan (total terjual * harga produk)
                                $keuntungan = $terjual * $produk->harga;
                            @endphp
                            <tr>
                                <td>{{ $produk->nama_produk }}</td>
                                <td>{{ $stok_awal }}</td>
                                <td>{{ $terjual }}</td>
                                <td>Rp{{ number_format($keuntungan, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')

<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
   $(document).ready(function() {
    var table = $('#laporanTable').DataTable({
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Tidak ada data tersedia",
            "paginate": {
                "first": "Awal",
                "last": "Akhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Menunggu elemen pencarian selesai dirender, lalu menambahkan placeholder
    setTimeout(function() {
        $('.dataTables_filter input').attr("placeholder", "Cari Menu...");
    }, 100);
});


    // Fungsi Cetak Laporan
    function cetakLaporan() {
        let printContents = document.getElementById("laporanArea").innerHTML;
        let originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload(); // Refresh halaman setelah cetak
    }
</script>

<!-- CSS untuk mode cetak -->
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #laporanArea, #laporanArea * {
            visibility: visible;
        }
        #laporanArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .btn {
            display: none !important;
        }
    }
</style>

<script>
    function cetakLaporan() {
        let kategoriId = document.querySelector('[name="kategori_id"]').value;
        let url = "{{ route('admin.laporan.cetak') }}?kategori_id=" + kategoriId;
        window.location.href = url;
    }
</script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $("form").on("submit", function (event) {
            event.preventDefault(); // Mencegah pengiriman form langsung
            
            // Tampilkan SweetAlert loading selama 3 detik
            Swal.fire({
                title: "Sedang Memproses...",
                text: "Mohon tunggu sebentar!",
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 1500, // 2 detik
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Setelah 3 detik, kirimkan form secara normal
            setTimeout(() => {
                this.submit();
            }, 1500);
        });
    });
</script>

@endpush
