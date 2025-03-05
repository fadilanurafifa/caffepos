@extends('admin.layouts.base')

@section('title', 'History Penjualan')

@push('style')
<style>
    .btn-custom {
        background-color: #007bff;
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-custom:hover,
    .btn-custom:focus,
    .btn-custom:active {
        background-color: #0056b3 !important;
        color: white !important;
        box-shadow: none !important;
        outline: none !important;
    }
    .table-container {
        padding: 20px;
    }
    .table th, .table td {
        vertical-align: middle !important;
    }
    .hidden {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container mt-4">
    <div class="card table-container">
        <div class="card-header border-bottom fw-bold">
            <h4 class="mb-0 text-dark"><i class="fas fa-history me-2"></i> History Penjualan</h4>
        </div>
        
        <div class="card-body">
            <div class="mb-3 d-flex gap-3">
                <button class="btn btn-custom btn-member" onclick="showTable('memberTable')">
                    <i class="fas fa-user-check"></i> Tampilkan Pelanggan Member
                </button>
                <button class="btn btn-custom btn-nonmember" onclick="showTable('nonMemberTable')">
                    <i class="fas fa-user-times"></i> Tampilkan Pelanggan Lain
                </button>
            </div>
            
            <div class="table-responsive" id="memberTableContainer">
                <h5 class="text-dark fw-bold">Pelanggan Member</h5>
                <table id="memberTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID Penjualan</th>
                            <th>Nama Pelanggan</th>
                            <th>Metode Pembayaran</th>
                            <th>Action</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        @foreach($detailTransaksi as $penjualan_id => $details)
                        @php $penjualan = $transaksi[$penjualan_id]; @endphp
                        @if($penjualan->pelanggan)
                        <tr>
                            <td class="text-center align-middle">{{ $penjualan_id }}</td>
                            <td class="align-middle">{{ $penjualan->pelanggan->nama }}</td>
                            <td class="align-middle">Cash</td>
                            <td class="text-center align-middle">
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $penjualan_id }}">
                                    <i class="fas fa-eye"></i> Detail
                                </button>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive hidden" id="nonMemberTableContainer">
                <h5 class="text-dark fw-bold">Pelanggan Lain</h5>
                <table id="nonMemberTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID Penjualan</th>
                            <th>Nama Pelanggan</th>
                            <th>Metode Pembayaran</th>
                            <th>Action</th>
                        </tr>
                    </thead>                    
                    <tbody>
                        @foreach($detailTransaksi as $penjualan_id => $details)
                        @php $penjualan = $transaksi[$penjualan_id]; @endphp
                        @if(!$penjualan->pelanggan)
                        <tr>
                            <td class="text-center align-middle">{{ $penjualan_id }}</td>
                            <td class="align-middle">Pelanggan Lain</td>
                            <td class="align-middle">Cash</td>
                            <td class="text-center align-middle">
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $penjualan_id }}">
                                    <i class="fas fa-eye"></i> Detail
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
@php $penjualan = $transaksi[$penjualan_id]; @endphp
<div class="modal fade" id="modalDetail{{ $penjualan_id }}" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($details as $detail)
                        <tr>
                            <td>{{ $detail->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <p><strong>Status:</strong> {{ $penjualan->status }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function() {
    $('#memberTable').DataTable();
    $('#nonMemberTable').DataTable();
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
@endpush
