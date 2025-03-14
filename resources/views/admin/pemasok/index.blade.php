{{-- @extends('admin.layouts.base')

@section('title', 'Pemasok')

@section('content')
@include('style')

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
                        <input type="text" class="form-control" id="nama_pemasok" placeholder="Masukkan Nama Pemasok" name="nama_pemasok" required>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
 $(document).ready(function() {
    let dataTable = $('#dataTable').DataTable(); // Inisialisasi DataTable
    
    $(document).ready(function() {
    var table = $('#yourTableID').DataTable();

    // Menambahkan placeholder ke input pencarian bawaan DataTables
    $('.dataTables_filter input').attr('placeholder', 'Cari data pemasok...');
    });

    // Tambah Pemasok
    $('#tambahPemasokForm').submit(function(event) {
        event.preventDefault();
        let formData = $(this).serialize(); // Mengambil data dari form

        $.ajax({
            url: '/pemasok',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Tambahkan data baru ke DataTable
                    dataTable.row.add([
                        response.data.id,
                        response.data.nama_pemasok,
                        `<button class="btn btn-warning btn-sm editPemasok" data-id="${response.data.id}" data-nama="${response.data.nama_pemasok}">
                            <i class="fas fa-edit"></i> 
                        </button>
                        <button class="btn btn-danger btn-sm hapusPemasok" data-id="${response.data.id}">
                            <i class="fas fa-trash"></i> 
                        </button>`
                    ]).draw(false); // Tambahkan dan perbarui tabel tanpa refresh

                    $('#tambahPemasokModal').modal('hide'); // Tutup modal
                    $('#tambahPemasokForm')[0].reset(); // Reset form

                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: 'Pemasok berhasil ditambahkan!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan!',
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal menambahkan pemasok. Silakan coba lagi.',
                });
            }
        });
    });

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
        let formData = $(this).serialize();

        $.ajax({
            url: '/pemasok/' + id,
            type: 'PUT',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Perbarui data di DataTable
                    let rowIndex = dataTable.row($(`button[data-id="${id}"]`).parents('tr')).index();
                    dataTable.row(rowIndex).data([
                        response.data.id,
                        response.data.nama_pemasok,
                        `<button class="btn btn-warning btn-sm editPemasok" data-id="${response.data.id}" data-nama="${response.data.nama_pemasok}">
                            <i class="fas fa-edit"></i> 
                        </button>
                        <button class="btn btn-danger btn-sm hapusPemasok" data-id="${response.data.id}">
                            <i class="fas fa-trash"></i> 
                        </button>`
                    ]).draw(false);

                    $('#editPemasokModal').modal('hide'); // Tutup modal

                    Swal.fire({
                        icon: 'success',
                        title: 'Sukses!',
                        text: 'Data pemasok berhasil diperbarui!',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message || 'Terjadi kesalahan!',
                    });
                }
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
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            let rowIndex = dataTable.row($(`button[data-id="${id}"]`).parents('tr')).index();
                            dataTable.row(rowIndex).remove().draw(false);

                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses!',
                                text: 'Data pemasok berhasil dihapus!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message || 'Terjadi kesalahan!',
                            });
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush --}}
