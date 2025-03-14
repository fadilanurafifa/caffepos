@extends('admin.layouts.base')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Pesanan</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Produk</th>
                <th>Status</th>
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
                    @if($order->status_pesanan == 'pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($order->status_pesanan == 'proses memasak')
                        <span class="badge bg-primary">Proses Memasak</span>
                    @else
                        <span class="badge bg-success">Selesai</span>
                    @endif
                </td>
                <td>
                    <!-- Tombol Modal -->
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $order->id }}">Detail</button>

                    @if($order->status_pesanan == 'pending')
                        <form action="{{ route('chef.updateOrder', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status_pesanan" value="proses memasak">
                            <button type="submit" class="btn btn-primary">Mulai Memasak</button>
                        </form>
                    @elseif($order->status_pesanan == 'proses memasak')
                        <form action="{{ route('chef.updateOrder', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status_pesanan" value="selesai">
                            <button type="submit" class="btn btn-success">Tandai Selesai</button>
                        </form>
                    @endif
                </td>
            </tr>

            <!-- Modal Detail Pesanan -->
            <div class="modal fade" id="detailModal{{ $order->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $order->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Detail Pesanan #{{ $order->no_faktur }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Kode Pesanan:</strong> {{ $order->no_faktur }}</p>
                            <p><strong>Status Pembayaran:</strong> {{ ucfirst($order->status_pembayaran) }}</p>
                            <p><strong>Status Pesanan:</strong> {{ ucfirst($order->status_pesanan) }}</p>
                            <p><strong>Produk:</strong></p>
                            <ul>
                                @foreach($order->detail_penjualan as $detail)
                                    <li>{{ $detail->produk->nama_produk }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Akhir Modal -->
            
            @endforeach
        </tbody>
    </table>
</div>
@endsection
