{{-- @extends('admin.layouts.base')

@section('title', 'Barang')

@section('content')
@include('style')
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
.btn-custom {
    background-color: #007bff; 
    color: white;
    border-radius: 5px;
    padding: 10px 20px;
    font-size: 14px;
    border: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    width: 18%;
    margin-top: 20px;
    margin-left: 20px;
}
.btn-custom:hover,
.btn-custom:focus,
.btn-custom:active {
    background-color: #007bff !important;
    color: white !important;
    box-shadow: none !important;
    outline: none !important;
    border: none !important;
    opacity: 1 !important;
}
</style>

<div class="container">
    <div class="card table-container">
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
        @endif

        @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 2000
            });
        </script>
        @endif

        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-box"></i> Daftar Barang
            </h3>
        </div>

        <button class="btn btn-custom mb-3" data-toggle="modal" data-target="#tambahBarangModal">
            <i class="fas fa-plus"></i> Tambah Barang
        </button> 

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="barangTable">
                    <thead>
                        <tr class="text-center">
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Produk</th>
                            <th>Satuan</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($barang as $brg)
                        <tr class="text-center" id="row-{{ $brg->id }}">
                            <td>{{ $brg->kode_barang }}</td>
                            <td>{{ $brg->nama_barang }}</td>
                            <td>{{ $brg->produk->nama_produk ?? 'Tidak Ada' }}</td>
                            <td>{{ $brg->satuan }}</td>
                            <td>Rp {{ number_format($brg->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $brg->stok }}</td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm hapusBarang" data-id="{{ $brg->id }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>                            
                            </td>
                        </tr>
                        @endforeach                                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div> 

<!-- Modal Tambah Barang -->
<div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;"> 
                    <div class="row">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" name="kode_barang" class="form-control" value="{{ $kodeBarang ?? '' }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Pilih Produk</label>
                            <select name="produk_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produk as $prd)
                                    <option value="{{ $prd->id }}">{{ $prd->nama_produk }}</option>
                                @endforeach
                            </select>                            
                        </div>                        

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" placeholder="Masukkan Nama Barang" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Satuan</label>
                            <select name="satuan" class="form-control">
                                <option value="Liter">Liter</option>
                                <option value="Gram">Gram</option>
                                <option value="Kilogram">Kilogram</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Botol">Botol</option>
                                <option value="Dus">Dus</option>
                                <option value="Lusin">Lusin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Harga Jual</label>
                            <input type="number" name="harga_jual" placeholder="Masukkan Harga Jual" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" name="stok" placeholder="Masukkan Stok" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>            
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    console.log("✅ JavaScript Loaded!");

    $(document).on('click', '.hapusBarang', function(event) {
        event.preventDefault();

        let barangId = $(this).data('id');
        let token = $('meta[name="csrf-token"]').attr('content');

        console.log("🟡 Klik tombol hapus! ID:", barangId);

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Barang akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("🔵 Mengirim AJAX DELETE ke server...");

                $.ajax({
                    url: '/admin/barang/' + barangId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: token
                    },
                    success: function(response) {
                        console.log("✅ Response dari server:", response);

                        if (response.status === 'success') {
                            Swal.fire('Sukses!', response.message, 'success');

                            $("#row-" + barangId).fadeOut(500, function() {
                                $(this).remove();
                            });
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.log("❌ Error Response:", xhr);
                        Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus barang.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush --}}
@extends('admin.layouts.base')

@section('title', 'Barang')

@section('content')

@push('style')
<style>
    .btn-custom {
        background-color: #007bff;
        color: white;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 14px;
        border: none;
        display: flex;
        align-items: center;
        gap: 5px;
        float: right;
    }
    .btn-custom:hover {
        background-color: #0056b3;
    }
    .table-container {
        padding: 20px;
    }
    .table th, .table td {
        vertical-align: middle !important;
    }
    .search-container {
        margin-bottom: 15px;
    }
</style>
@endpush

<div class="container">
    <div class="card table-container">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-box"></i> Daftar Barang
            </h3>
            <button class="btn btn-custom" data-toggle="modal" data-target="#tambahBarangModal">
                <i class="fas fa-plus"></i> Tambah Barang
            </button> 
        </div>
        @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif
    
        <div class="card-body">
            <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari barang...">
            </div>

            <div class="table-responsive">
                <table id="barangTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Produk</th>
                            <th>Satuan</th>
                            <th>Harga Jual</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $brg)
                        <tr class="text-center" id="row-{{ $brg->id }}">
                            <td>{{ $brg->kode_barang }}</td>
                            <td>{{ $brg->nama_barang }}</td>
                            <td>{{ $brg->produk->nama_produk ?? 'Tidak Ada' }}</td>
                            <td>{{ $brg->satuan }}</td>
                            <td>Rp {{ number_format($brg->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $brg->stok }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger hapusBarang" data-id="{{ $brg->id }}">
                                    <i class="fas fa-trash"></i> 
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>    
</div> 

<!-- Modal Tambah Barang -->
<div class="modal fade" id="tambahBarangModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;"> 
                    <div class="row">
                        <div class="form-group">
                            <label>Kode Barang</label>
                            <input type="text" name="kode_barang" class="form-control" value="{{ $kodeBarang ?? '' }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Pilih Produk</label>
                            <select name="produk_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produk as $prd)
                                    <option value="{{ $prd->id }}">{{ $prd->nama_produk }}</option>
                                @endforeach
                            </select>                            
                        </div>                        

                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang" placeholder="Masukkan Nama Barang" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Satuan</label>
                            <select name="satuan" class="form-control">
                                <option value="Liter">Liter</option>
                                <option value="Gram">Gram</option>
                                <option value="Kilogram">Kilogram</option>
                                <option value="Pcs">Pcs</option>
                                <option value="Botol">Botol</option>
                                <option value="Dus">Dus</option>
                                <option value="Lusin">Lusin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Harga Jual</label>
                            <input type="number" name="harga_jual" placeholder="Masukkan Harga Jual" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" name="stok" placeholder="Masukkan Stok" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>            
        </div>
    </div>
</div>

@endsection

@push('script')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    let table = $('#barangTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    $(document).on('click', '.hapusBarang', function(event) {
        event.preventDefault();
        let barangId = $(this).data('id');
        let token = $('meta[name="csrf-token"]').attr('content');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/admin/barang/' + barangId,
                    type: 'DELETE',
                    data: { _token: token },
                    success: function(response) {
                        Swal.fire('Dihapus!', 'Barang berhasil dihapus.', 'success');
                        $("#row-" + barangId).fadeOut(500, function() {
                            $(this).remove();
                        });
                    },
                    error: function() {
                        Swal.fire('Gagal!', 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    });
});
</script>

@endpush

