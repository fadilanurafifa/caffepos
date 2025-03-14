{{-- @extends('admin.layouts.base')

@section('title', 'Manajemen Barang')

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
    .filter-container {
        display: flex;
        align-items: center;
        gap: 10px; 
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .filter-container input {
        padding: 6px 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        width: 310px; 
    }
    .filter-container .separator {
        font-weight: bold;
        color: #555;
        font-size: 14px;
    }
    .btn-filter {
        background-color: #ff8c00;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap; 
    }
    .btn-filter:hover {
        background-color: #e67e00;
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
    <form method="GET" action="{{ route('barang.index') }}" class="filter-container">
        <label for="filter_tanggal">Filter Tanggal Pembelian:</label>
        
        <input type="date" name="tanggal_awal" class="form-control" 
               value="{{ request('tanggal_awal') }}" required>
    
        <span class="separator">Sampai :</span>
    
        <input type="date" name="tanggal_akhir" class="form-control" 
               value="{{ request('tanggal_akhir') }}" required>
    
        <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Filter
        </button>
    </form>
    
            <div class="table-responsive">
                <table id="barangTable" class="table table-bordered">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Produk</th>
                            <th>Satuan</th>
                            <th>Harga Beli</th>
                            <th>Stok</th>
                            <th>Tanggal Pembelian</th>
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
                            <td>{{ $brg->tanggal_pembelian ? \Carbon\Carbon::parse($brg->tanggal_pembelian)->format('d-m-Y') : '-' }}</td>
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
                            <label>Kode Barang :</label>
                            <input type="text" name="kode_barang" class="form-control" value="{{ $kodeBarang ?? '' }}" readonly>
                        </div>
                        
                        <div class="form-group">
                            <label>Pilih Produk :</label>
                            <select name="produk_id" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($produk as $prd)
                                    <option value="{{ $prd->id }}">{{ $prd->nama_produk }}</option>
                                @endforeach
                            </select>                            
                        </div>                        

                        <div class="form-group">
                            <label>Nama Barang :</label>
                            <input type="text" name="nama_barang" placeholder="Masukkan Nama Barang" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Satuan :</label>
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
                            <label>Harga Jual :</label>
                            <input type="number" name="harga_jual" placeholder="Masukkan Harga Jual" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Stok :</label>
                            <input type="number" name="stok" placeholder="Masukkan Stok" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_pembelian">Tanggal Pembelian :</label>
                            <input type="date" name="tanggal_pembelian" class="form-control" required>
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

    $(document).ready(function() {
    var table = $('#yourTableID').DataTable();

    // Menambahkan placeholder ke input pencarian bawaan DataTables
    $('.dataTables_filter input').attr('placeholder', 'Cari data barang...');
    });
     // SweetAlert untuk notifikasi proses filtering
     $('.filter-container').on('submit', function(event) {
        event.preventDefault(); // Mencegah form langsung dikirim

        Swal.fire({
            title: 'Memproses Filter...',
            text: 'Mohon tunggu sebentar.',
            icon: 'info',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                
                // Submit form setelah delay agar user melihat animasi
                setTimeout(() => {
                    event.target.submit(); // Kirim form setelah delay
                }, 1000);
            }
        });
    });

});
</script>
@endpush
 --}}
