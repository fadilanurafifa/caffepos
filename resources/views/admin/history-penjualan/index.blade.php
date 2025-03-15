@extends('admin.layouts.base')

@section('title', 'Laporan Transaksi')

@push('style')
<style>
   .btn-custom {
    background-color: #89AC46; /* Warna hijau sesuai permintaan */
    border: none;
    color: white;
    padding: 8px 12px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 6px;
    }

    .btn-custom:hover {
        background-color: #89AC46 !important; /* Tetap hijau saat hover */
        color: white !important;
    }
    .table-container {
        padding: 20px;
    }
    .table {
        font-size: 16px; 
    }
    .table th, .table td {
        padding: 6px; 
    }
    .modal-dialog {
        max-width: 400px; 
    }
    .modal-content {
        padding: 8px; 
    }
    .modal-body {
        padding: 10px;
    }
    .modal-footer {
        padding: 5px;
    }
    .hidden {
        display: none;
    }
</style>
@endpush
@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 text-gray-800">
                <i class="fas fa-receipt"></i> Laporan Transaksi
            </h1>
            <p class="text-muted">
                <a href="{{ route('dashboard') }}" class="text-custom text-decoration-none">Home</a> / 
                <a href="#" class="text-custom text-decoration-none">Laporan Transaksi</a>
            </p>                
        </div>
    </div>       
    @php
    $totalIncome = $transaksi->where('status_pembayaran', 'lunas')->sum('total_bayar');
    @endphp
    
    <div class="d-flex justify-content-between align-items-center mb-4">
       <!-- Button Filter (Kiri) -->
        <div class="d-flex gap-3" style="margin-top: -30px;">
            <button class="btn btn-custom d-flex align-items-center" onclick="filterTable('all')">
                <i class="fas fa-list me-2"></i> Semua Transaksi
            </button>
            <button class="btn btn-custom d-flex align-items-center" onclick="filterTable('member')">
                <i class="fas fa-user-check me-2"></i> Pelanggan Member
            </button>
            <button class="btn btn-custom d-flex align-items-center" onclick="filterTable('biasa')">
                <i class="fas fa-user me-2"></i> Pelanggan Biasa
            </button>
        </div>
        <!-- Box Total Income (Kanan) -->
        <div class="card shadow-sm border-0" style="background: white; border-radius: 10px; padding: 15px; border-left: 4px solid #28a745; min-width: 250px;">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-wallet fa-lg text-success"></i>
                </div>
                <div>
                    <h6 class="mb-1 text-muted" style="font-size: 14px;">Total Pemasukan</h6>
                    <h4 class="fw-bold m-0 text-dark">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>    
    <div class="card table-container">
        <div class="card-body">
            <div class="table-responsive">
                <table id="transaksiTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID Penjualan</th>
                            <th>Nama Pelanggan</th>
                            <th>Tipe Pelanggan</th> <!-- Tambahkan ini -->
                            <th>Metode Pembayaran</th>
                            <th>Total Harga</th>
                            <th>Tanggal Transaksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>                                 
                    <tbody>
                        @foreach($detailTransaksi as $penjualan_id => $details)
                        @php $penjualan = $transaksi[$penjualan_id] ?? null; @endphp
                        @if($penjualan)
                        <tr class="transaksi-row" data-type="{{ $penjualan->pelanggan ? 'member' : 'biasa' }}">
                            <td class="text-center align-middle">{{ $penjualan_id }}</td>
                            <td class="align-middle">
                                {{ $penjualan->pelanggan ? $penjualan->pelanggan->nama : 'Pelanggan Biasa' }}
                            </td>
                            <td class="align-middle">
                                <span style="font-weight: bold; color: {{ $penjualan->pelanggan ? 'green' : 'red' }};">
                                    {{ $penjualan->pelanggan ? 'Member' : 'Non-Member' }}
                                </span>
                            </td>                            
                            <td class="align-middle">Cash</td>
                            <td class="align-middle">Rp.{{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                            <td class="align-middle">{{ $penjualan->created_at->format('d-m-Y H:i') }}</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $penjualan_id }}">
                                    <i class="fas fa-eye"></i> 
                                </button>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($detailTransaksi as $penjualan_id => $details)
@php $penjualan = $transaksi[$penjualan_id] ?? null; @endphp
@if($penjualan)
<div class="modal fade" id="modalDetail{{ $penjualan_id }}" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 500px;">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                        <tr>
                            <td>{{ optional($detail->produk)->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="mt-2" style="font-size: 14px; background-color: #d1ecf1; padding: 6px 12px; border-radius: 5px; color: #0c5460; font-weight: bold;">
                    <strong>Status Pembayaran :</strong> {{ ucfirst($penjualan->status_pembayaran) }}
                </p>                              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#transaksiTable').DataTable({
        "language": {
            "searchPlaceholder": "Cari Transaksi...",
            "zeroRecords": "Tidak ada transaksi ditemukan"
        }
    });
});

function filterTable(type) {
    if (type === 'all') {
        $('.transaksi-row').show();
    } else {
        $('.transaksi-row').hide();
        $('.transaksi-row[data-type="' + type + '"]').show();
    }
}
</script>
@endpush


{{-- @push('script')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#memberTable').DataTable();
    $('#nonMemberTable').DataTable();

    // Tambahkan placeholder "Cari Transaksi" pada input search DataTables
    $('.dataTables_filter input').attr("placeholder", "Cari Transaksi...");
});

function showTable(tableId) {
    if (tableId === 'memberTable') {
        $('#memberTableContainer').removeClass('hidden');
        $('#nonMemberTableContainer').addClass('hidden');
    } else {
        $('#memberTableContainer').addClass('hidden');
        $('#nonMemberTableContainer').removeClass('hidden');
    }
}
</script>
@endpush --}}
