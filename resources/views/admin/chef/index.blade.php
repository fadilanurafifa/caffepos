@extends('admin.layouts.base')

@section('title', 'Daftar Pesanan')

@section('content')
@push('style')
<style>
    .table {
        background: #ffffff;
        border-radius: 8px;
    }
    .table th {
        background: #f8f9fa;
    }
    .btn-danger {
        background: #dc3545;
        border: none;
    }
    .btn-danger:hover {
        background: #c82333;
    }
    .pagination .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
    }
</style>
@endpush

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h3 text-gray-800">
                <i class="fas fa-clipboard-list"></i> Daftar Pesanan
            </h1>
            <p class="text-muted">
                <a href="{{ route('dashboard') }}" class="text-custom text-decoration-none">Home</a> / 
                <a href="#" class="text-custom text-decoration-none">Daftar Pesanan</a>
            </p>                
        </div>
    </div>            

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Produk</th>
                <th>Status Pembayaran</th>
                <th>Status Pesanan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->no_faktur }}</td>
                <td>
                    @foreach ($order->detail_penjualan as $detail)
                        <p>{{ $detail->produk->nama_produk }}</p>
                    @endforeach
                </td>
                <td>
                    <span style="color: {{ $order->status_pembayaran == 'lunas' ? 'green' : 'red' }}">
                        {{ ucfirst($order->status_pembayaran) }}
                    </span>                    
                </td>
                <td>
                    <span style="color: 
                        {{ $order->status_pesanan == 'pending' ? 'orange' : 
                        ($order->status_pesanan == 'proses memasak' ? 'red' : 'green') }}">
                        {{ ucfirst($order->status_pesanan) }}
                    </span>
                </td>
                <td>
                    <!-- Tombol Modal -->
                    <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}" 
                        class="d-flex justify-content-center align-items-center">
                         <i class="fas fa-eye text-dark fa-lg"></i> <!-- Ikon sedikit lebih besar -->
                     </a>                                                         
                </td>
            </tr>

        
            <!-- Modal Detail Pesanan -->
            <div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1" 
                    aria-labelledby="modalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg"> 
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title fw-bold">Detail Pesanan -> {{ $order->no_faktur }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <!-- Kode Pesanan -->
                                <div class="row mb-3 d-flex align-items-center">
                                    <div class="col-5 fw-bold text-muted">Kode Pesanan</div>
                                    <div class="col-auto">:</div>
                                    <div class="col-6">{{ $order->no_faktur }}</div>
                                </div>

                                <!-- Status Pembayaran -->
                                <div class="row mb-3 d-flex align-items-center">
                                    <div class="col-5 fw-bold text-muted">Status Pembayaran</div>
                                    <div class="col-auto">:</div>
                                    <div class="col-6 text-{{ $order->status_pembayaran == 'lunas' ? 'success' : 'danger' }}">
                                        {{ ucfirst($order->status_pembayaran) }}
                                    </div>
                                </div>

                                <!-- Status Pesanan -->
                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-5 fw-bold text-muted">Status Pesanan</div>
                                    <div class="col-auto">:</div>
                                    <div class="col-6 text-{{ 
                                        $order->status_pesanan == 'pending' ? 'warning' : 
                                        ($order->status_pesanan == 'proses memasak' ? 'primary' : 'success') }}">
                                        {{ ucfirst($order->status_pesanan) }}
                                    </div>
                                </div>

                                <!-- Produk -->
                                <h6 class="fw-bold border-bottom pb-2 mb-3">Produk</h6>
                                <ul class="list-group list-group-flush">
                                    @foreach($order->detail_penjualan as $detail)
                                        <li class="list-group-item border-bottom d-flex justify-content-between">
                                            <span>{{ $detail->produk->nama_produk }}</span>
                                            <span class="fw-bold text-danger">x{{ $detail->jumlah }}</span>
                                        </li>
                                    @endforeach
                                </ul>                                
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-end gap-2">
                            @if($order->status_pesanan == 'pending')
                                <form action="{{ route('chef.updateOrder', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_pesanan" value="proses memasak">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-fire"></i>  Mulai Memasak
                                    </button>                                    
                                </form>
                            @elseif($order->status_pesanan == 'proses memasak')
                                <form action="{{ route('chef.updateOrder', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status_pesanan" value="selesai">
                                    <button type="submit" class="btn" style="background-color: #89AC46; border-color: #89AC46; color: white;">
                                        <i class="fas fa-check"></i> Tandai Selesai
                                    </button>
                                                                     
                                </form>
                            @endif
                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Tutup
                            </button> --}}
                        </div>                        
                    </div>
                </div>
            </div>
            <!-- Akhir Modal -->
            @endforeach
        </tbody>
    </table>
</div>
@push('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function (toastEl) {
            return new bootstrap.Toast(toastEl, { delay: 3000 })
        });
        toastList.forEach(toast => toast.show());
    });
</script>

@endpush
@endsection
