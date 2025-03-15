
@extends('admin.layouts.base')

@section('title', 'Pembayaran - ' . $transaksi->no_faktur)

@section('content')
@push('style')
    <style>
    .btn-cetak-struk {
        background-color: #007bff; 
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap; 
        margin-top: 15px;
    }
    .btn-cetak-struk:hover,
    .btn-cetak-struk:focus,
    .btn-cetak-struk:active {
        background-color: #007bff !important; 
        color: white !important; 
        box-shadow: none !important; 
        outline: none !important; 
    }
    .badge-custom {
        /* background-color: #89AC46 !important;  */
        color: #89AC46 !important; 
        padding: 6px 12px;
        font-size: 25px;
        border-radius: 8px; 
    }
        /* Custom Success Button */
    .btn-custom-success {
        background-color: #89AC46 !important; /* Warna hijau */
        color: white !important; /* Warna teks tetap terlihat */
        border: none;
        padding: 10px;
        font-size: 16px;
        border-radius: 6px;
        /* cursor: not-allowed; /* Indikasi tombol nonaktif */
        /* opacity: 0.8; */
    }
    </style>
@endpush
<div class="container mt-4">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-receipt"></i> Pembayaran Nomor Faktur -> {{ $transaksi->no_faktur }}
        <span class="badge {{ $transaksi->status_pembayaran == 'pending' ? 'badge-custom text-dark' : 'badge-custom' }} float-end">
            {{ ucfirst($transaksi->status_pembayaran) }}
        </span>             
    </h1>
 <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="pelanggan" class="form-label"><strong>Pelanggan:</strong></label>
                    <input type="text" id="pelanggan" class="form-control" value="{{ $transaksi->pelanggan->nama ?? 'Pelanggan Biasa' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="total_bayar" class="form-label"><strong>Total Bayar:</strong></label>
                    <input type="text" id="total_bayar" class="form-control" value="Rp {{ number_format($transaksi->total_bayar, 0, ',', '.') }}" readonly>
                </div>
            </div>            

            <div class="table-responsive">
                <table id="detailTable" class="table table-striped table-bordered mt-2" style="width:100%">
                    <thead>
                        <tr>
                            <th>Nama Produk</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detail_penjualan as $detail)
                            @php
                                $harga_satuan = $detail->sub_total / $detail->jumlah;
                            @endphp
                            <tr>
                                <td>
                                    @if($detail->produk)
                                        {{ $detail->produk->nama_produk }}
                                    @else
                                        <span class="text-danger">Produk tidak ditemukan</span>
                                    @endif
                                </td>                                                                                  
                                <td>{{ $detail->jumlah }}</td>
                                <td>Rp {{ number_format($harga_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($transaksi->status_pembayaran == 'pending')
                <h4 class="mt-4"><i class="fas fa-cash-register"></i> Form Pembayaran</h4>
                <form id="pembayaranForm" action="{{ route('admin.pembayaran.bayar', $transaksi->no_faktur) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="jumlah_bayar" class="form-label">Jumlah Bayar:</label>
                            <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control" required min="{{ $transaksi->total_bayar }}">
                        </div>
                        <div class="col-md-6">
                            <label for="kembalian" class="form-label">Kembalian:</label>
                            <input type="text" id="kembalian" class="form-control" readonly>
                        </div>
                    </div>
                    <button type="submit" class="btn w-100 mt-3" style="background-color: #89AC46; border-color: #89AC46; color: white;">
                        <i class="fas fa-check-circle"></i> Bayar Sekarang
                    </button>                    
                </form>
            @else
            <button class="btn btn-custom-success w-100 mt-3" disabled>
                <i class="fas fa-check"></i> Sukses melakukan pembayaran!
            </button>
            <a href="{{ route('admin.pembayaran.print', $transaksi->no_faktur) }}" 
                class="btn btn-cetak-struk" 
                target="_blank">
                <i class="fas fa-print"></i> Cetak Struk
             </a>                                                   
                @endif
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('#detailTable').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            ordering: false,
            info: false,
        });

        const bayarInput = document.getElementById("jumlah_bayar");
        const kembalianInput = document.getElementById("kembalian");
        const totalBayar = parseInt("{{ $transaksi->total_bayar }}");

        bayarInput.addEventListener("input", function () {
            let bayar = parseInt(bayarInput.value) || 0;
            let kembalian = bayar - totalBayar;
            kembalianInput.value = kembalian >= 0 ? kembalian : 0;
        });

        $("#pembayaranForm").submit(function(event) {
            event.preventDefault();
            Swal.fire({
                title: "Konfirmasi Pembayaran",
                text: "Apakah Anda yakin ingin melakukan pembayaran?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28a745",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, bayar sekarang!"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                    Swal.fire(
                        "Berhasil!",
                        "Pembayaran telah berhasil dilakukan.",
                        "success"
                    );
                }
            });
        });
    });
</script>
@endpush
