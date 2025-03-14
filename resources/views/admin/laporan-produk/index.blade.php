@extends('admin.layouts.base')

@section('title', 'Laporan Data Produk')

@push('style')
<!-- Tambahkan CSS DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<style>
    .table-container {
        padding: 20px;
    }
    .table th, .table td {
        vertical-align: middle !important;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-folder"></i> Manajemen Kategori
    </h1>

   
    <!-- Tombol Download PDF -->
    <div class="d-flex justify-content-end mt-2">
        <a href="{{ route('laporan.produk.pdf') }}" class="btn btn-danger btn-sm" role="button" style="display: inline-block; text-align: center; border: none; margin-bottom: 20px;">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>
    
    

    <div class="card table-container">
        <div class="card-body">
            <div class="table-responsive">
                <table id="produkTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Kategori ID</th>
                            <th>Nama Produk</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Tanggal Dibuat</th>
                            <th>Tanggal Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk as $item)
                        <tr class="text-center">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->kategori_id }}</td>
                            <td>{{ $item->nama_produk }}</td>
                            <td>Rp {{ number_format($item->harga, 2, ',', '.') }}</td>
                            <td>{{ $item->stok }}</td>
                            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
                            <td>{{ $item->updated_at->format('d-m-Y H:i') }}</td>
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
<!-- Tambahkan Script DataTables -->
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#produkTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "language": {
            "lengthMenu": "Tampilkan _MENU_ entri per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "infoEmpty": "Tidak ada data tersedia",
            "infoFiltered": "(disaring dari _MAX_ total entri)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});

</script>
@endpush
