@extends('admin.layouts.base')

@section('title', 'Manajemen Kategori')

@section('content')
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
        background-color: #007bff !important; 
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
    .search-container {
        margin-bottom: 15px;
    }
    .modal-dialog {
        max-width: 400px; 
    }
    .modal-content {
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }
    .modal-body {
        padding: 15px;
        background: #f8f9fa;
    }
    .form-group label {
        font-weight: bold;
        font-size: 13px;
        color: #333;
        margin-bottom: 4px;
    }
    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 6px 10px; 
        font-size: 13px; 
        height: 32px; 
    }
</style>
@endpush
<div class="container">
    <h1 class="h3 mb-4 text-gray-800">
        <i class="fas fa-folder"></i> Manajemen Kategori
    </h1>
    
    <div class="d-flex justify-content-end">
        <button class="btn btn-custom" data-toggle="modal" data-target="#tambahKategoriModal" style="width: 150px; margin-bottom: 15px; border-radius: 5px; margin-top: -55px;">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>        
    <div class="card table-container">
        {{-- <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-folder-open"></i> Data Kategori
            </h3>
        </div> --}}
        <div class="card-body">
            <div class="table-responsive">
                <table id="kategoriTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategori as $kat)
                        <tr class="text-center" id="row-{{ $kat->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $kat->nama_kategori }}</td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-danger hapusKategori" data-id="{{ $kat->id }}">
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

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required placeholder="Masukkan Nama Kategori">
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
     @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>
<script>
$(document).ready(function() {
    let table = $('#kategoriTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false
    });

    $(document).ready(function() {
    var table = $('#yourTableID').DataTable();

    // Menambahkan placeholder ke input pencarian bawaan DataTables
    $('.dataTables_filter input').attr('placeholder', 'Cari data kategori...');
    });

    $(document).on('click', '.hapusKategori', function(event) {
        event.preventDefault();
        let kategoriId = $(this).data('id');
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
                    url: '/admin/kategori/' + kategoriId,
                    type: 'DELETE',
                    data: { _token: token },
                    success: function(response) {
                        Swal.fire('Dihapus!', 'Kategori berhasil dihapus.', 'success');
                        $("#row-" + kategoriId).fadeOut(500, function() {
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
