@extends('admin.layouts.base')

@section('title', 'Pemasok')

@section('content')
@include('style')

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
    margin-top: 20px;
    margin-left: 20px;
}
.btn-custom:hover {
    background-color: #0056b3;
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

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-truck"></i> Daftar Pemasok
            </h3>
            <button class="btn btn-custom" data-toggle="modal" data-target="#tambahPemasokModal">
                <i class="fas fa-plus"></i> Tambah Pemasok
            </button>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="dataTable">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>ID</th>
                            <th>Nama Pemasok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pemasok as $p)
                        <tr class="text-center" id="row-{{ $p->id }} ">
                            <td>{{ $p->id }}</td>
                            <td id="nama-{{ $p->id }}">{{ $p->nama_pemasok }}</td>
                            <td>
                                <button class="btn btn-warning btn-sm editPemasok" data-id="{{ $p->id }}" data-nama="{{ $p->nama_pemasok }}">
                                    <i class="fas fa-edit"></i> 
                                </button>
                                <button class="btn btn-danger btn-sm hapusPemasok" data-id="{{ $p->id }}">
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
<!-- Modal Tambah Pemasok -->
<div class="modal fade" id="tambahPemasokModal" tabindex="-1" aria-labelledby="tambahPemasokModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahPemasokModalLabel">Tambah Pemasok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tambahPemasokForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_pemasok">Nama Pemasok</label>
                        <input type="text" class="form-control" id="nama_pemasok" name="nama_pemasok" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Pemasok -->
<div class="modal fade" id="editPemasokModal" tabindex="-1" aria-labelledby="editPemasokModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPemasokModalLabel">Edit Pemasok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editPemasokForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" id="edit_id">
                    <div class="form-group">
                        <label for="edit_nama_pemasok">Nama Pemasok</label>
                        <input type="text" class="form-control" id="edit_nama_pemasok" name="nama_pemasok" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function() {
        // Tambah Pemasok
        $('#tambahPemasokForm').submit(function(event) {
            event.preventDefault();
            let nama_pemasok = $('#nama_pemasok').val();
    
            $.ajax({
                url: '/pemasok',
                type: 'POST',
                data: {_token: '{{ csrf_token() }}', nama_pemasok: nama_pemasok},
                success: function(response) {
                    location.reload();
                }
            });
        });
    });
    </script>

<script>
$(document).ready(function() {
    $('#dataTable').DataTable();

    // Edit Pemasok
    $(document).on('click', '.editPemasok', function() {
        let id = $(this).data('id');
        let nama = $(this).data('nama');
        
        $('#edit_id').val(id);
        $('#edit_nama_pemasok').val(nama);
        $('#editPemasokModal').modal('show');
    });

    $('#editPemasokForm').submit(function(event) {
        event.preventDefault();
        let id = $('#edit_id').val();
        let nama = $('#edit_nama_pemasok').val();

        $.ajax({
            url: '/pemasok/' + id,
            type: 'PUT',
            data: {_token: '{{ csrf_token() }}', nama_pemasok: nama},
            success: function(response) {
                $('#nama-' + id).text(nama);
                $('#editPemasokModal').modal('hide');
                Swal.fire('Sukses!', response.message, 'success');
            }
        });
    });

    // Hapus Pemasok
    $(document).on('click', '.hapusPemasok', function() {
        let id = $(this).data('id');

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
                    url: '/pemasok/' + id,
                    type: 'DELETE',
                    data: {_token: '{{ csrf_token() }}'},
                    success: function(response) {
                        $('#row-' + id).fadeOut(500, function() {
                            $(this).remove();
                        });
                        Swal.fire('Sukses!', response.message, 'success');
                    }
                });
            }
        });
    });
});
</script>
@endpush
