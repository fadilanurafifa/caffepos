@extends('admin.layouts.base')

@section('title', 'Transaksi Pembayaran')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-money-bill-wave"></i> Transaksi Pembayaran</h3>
        </div>
        <div class="card-body">
            @if($transaksi->isEmpty())
                <p class="text-center">Tidak ada transaksi yang perlu dibayar.</p>
            @else
                <table class="table table-striped" id="transaksiTable">
                    <thead>
                        <tr>
                            <th>No Faktur</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total Bayar</th>
                            <th>Metode Pembayaran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaksi as $p)
                        <tr>
                            <td>{{ $p->no_faktur }}</td>
                            <td>{{ $p->tgl_faktur }}</td>
                            <td>{{ $p->pelanggan ? $p->pelanggan->nama : 'Tidak Ada' }}</td>
                            <td class="total-transaksi" data-total="{{ $p->total_bayar }}">
                                Rp {{ number_format($p->total_bayar, 0, ',', '.') }}
                            </td>
                            <td>Cash</td>
                            <td>
                                <span class="badge {{ $p->status_pembayaran == 'pending' ? 'bg-warning' : 'bg-success' }}">
                                    {{ ucfirst($p->status_pembayaran) }}
                                </span>
                            </td>
                            <td>
                                @if($p->status_pembayaran == 'pending')
                                    <button class="btn btn-success btn-sm" onclick="prosesPembayaran('{{ $p->id }}', '{{ $p->total_bayar }}')">Bayar</button>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>Lunas</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Form Pembayaran -->
                <div id="formPembayaran" class="mt-4" style="display: none;">
                    <h3>Pembayaran</h3>

                    <div class="form-group">
                        <label for="uangDiberikan">Uang yang Diberikan:</label>
                        <input type="number" id="uangDiberikan" class="form-control" oninput="hitungKembalian()">
                    </div>

                    <div class="form-group">
                        <label for="kembalian">Kembalian:</label>
                        <input type="text" id="kembalian" class="form-control" value="Rp 0" readonly>
                    </div>

                    <button class="btn btn-primary mt-2" id="btnKonfirmasi" onclick="konfirmasiPembayaran()" disabled>Konfirmasi Pembayaran</button>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#transaksiTable').DataTable();
});

let selectedTransactionId = null;
let totalPembayaran = 0;

function prosesPembayaran(transaksiId, total) {
    selectedTransactionId = transaksiId;
    totalPembayaran = parseFloat(total);
    document.getElementById('formPembayaran').style.display = 'block';
    document.getElementById('uangDiberikan').value = '';
    document.getElementById('kembalian').value = 'Rp 0';
    document.getElementById('btnKonfirmasi').disabled = true;
}

function hitungKembalian() {
    let uangDiberikan = parseFloat(document.getElementById('uangDiberikan').value) || 0;
    let kembalian = uangDiberikan - totalPembayaran;

    document.getElementById('kembalian').value = kembalian.toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

    if (uangDiberikan < totalPembayaran) {
        document.getElementById('btnKonfirmasi').disabled = true;
    } else {
        document.getElementById('btnKonfirmasi').disabled = false;
    }
}

function konfirmasiPembayaran() {
    let uangDiberikan = parseFloat(document.getElementById('uangDiberikan').value) || 0;

    if (uangDiberikan < totalPembayaran) {
        Swal.fire({
            title: 'Uang Tidak Cukup!',
            text: 'Silakan masukkan jumlah yang lebih besar.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return;
    }

    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        text: "Apakah Anda yakin ingin menyelesaikan transaksi ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Bayar!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "/admin/transaksi/bayar/" + selectedTransactionId;
        }
    });
}
</script>
@endpush
