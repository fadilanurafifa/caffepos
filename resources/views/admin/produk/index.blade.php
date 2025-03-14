@extends('admin.layouts.base')

@section('title', 'Manajemen Produk')

@section('content')
@include('style')
    <style>
        .produk-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-start;
            margin-top: 20px;
        }
        .produk-card {
            width: 140px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            padding: 10px;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }
        .produk-card:hover {
            transform: scale(1.05);
        }
        .produk-card img {
            width: 100%;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .produk-card h5 {
            margin: 8px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .produk-card p {
            font-size: 12px;
            color: #666;
        }
        .card {
            width: 100%;
            max-width: 320px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }
        .cart-list {
            max-height: 250px;
            overflow-y: auto;
            padding: 0;
        }
        .cart-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            font-size: 14px;
        }
        .cart-list img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 5px;
        }
        .cart-list button {
            width: 28px;
            height: 28px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        #save-order {
            font-size: 12px;
            padding: 5px 8px;
        }
        .cart-list input {
            width: 35px;
            text-align: center;
            font-size: 12px;
            padding: 2px;
        }
        /* .card-footer {
            font-size: 14px;
            padding: 10px;
        }
        .card-footer .btn {
            font-size: 13px;
            padding: 8px;
            flex: 1;
            white-space: nowrap;
        } */
        .d-flex.gap-2 {
            display: flex;
            gap: 5px;
            flex-wrap: nowrap;
        }
        #subtotal {
            font-size: 16px;
            font-weight: bold;
        }
        .btn-custom {
            height: 38px;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 15px;
        }
        .form-control-custom {
            height: 38px;
            font-size: 14px;
        }
        .input-group {
            max-width: 250px;
        }
        .btn-customs {
        background-color: #007bff; 
        color: white;
        border: none;
        padding: 8px 14px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        white-space: nowrap; 
    }

    .btn-customs:hover,
    .btn-customs:focus,
    .btn-customs:active {
        background-color: #007bff !important; 
        color: white !important; 
        box-shadow: none !important; 
        outline: none !important; 
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

    <div class="container">
        <h1 class="h3 mb-4 text-gray-800">
            <i class="fas fa-box"></i> Manajemen Produk
        </h1>
        <div class="d-flex justify-content-end">
        <button class="btn btn-customs" data-toggle="modal" data-target="#tambahProdukModal" style="width: 150px; margin-bottom: 15px; border-radius: 5px; margin-top: -55px;">
            <i class="fas fa-plus"></i> Tambah Produk
        </button>

        </div>
        @if(session('success'))
        <script>
            Swal.fire({
                title: "Sukses!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK"
            });
        </script>
        @endif        
        <!-- Filter Kategori dan Input Pencarian (Sejajar) -->
        <div class="row mb-2">
            <div class="col-lg-9">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <!-- Filter Kategori -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-primary kategori-filter active" data-filter="all">Semua</button>
                        @foreach ($kategori as $kat)
                            <button class="btn btn-sm btn-outline-primary kategori-filter"
                                data-filter="{{ strtolower($kat->nama_kategori) }}">
                                {{ $kat->nama_kategori }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Input Filter Nama Produk -->
                    <div class="input-group">
                        <button class="btn btn-light border btn-custom" id="btnFilter">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <input type="text" id="searchProduk" class="form-control form-control-custom"
                            placeholder="Cari produk...">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Daftar Produk (Kiri) -->
            <div class="col-lg-12">
                <div class="row row-cols-3 row-cols-sm-4 row-cols-md-5 row-cols-lg-6 g-2">
                    @foreach ($produk as $prd)
                        <div class="col" data-id="{{ $prd->id }}"
                            data-nama="{{ strtolower($prd->nama_produk) }}" data-harga="{{ $prd->harga }}"
                            data-foto="{{ asset('assets/produk_fotos/' . $prd->foto) }}"
                            data-kategori="{{ strtolower($prd->kategori ? $prd->kategori->nama_kategori : 'tanpa kategori') }}">
            
                            <div class="produk-card border-0 shadow-sm h-100" style="max-width: 120px;">
                                <img src="{{ asset('assets/produk_fotos/' . $prd->foto) }}"
                                    class="card-img-top img-fluid rounded-top"
                                    style="height: 100px; object-fit: cover;">
                                <div class="card-body text-center p-1">
                                    <h6 class="card-title text-truncate" style="font-size: 12px;">{{ $prd->nama_produk }}</h6>
                                    <h6 class="card-title text-truncate" style="font-size: 12px;">Stok: {{ $prd->stok }}</h6>
                                    <h5 class="text-muted mt-1" style="font-size: 10px;">
                                        {{ $prd->kategori ? $prd->kategori->nama_kategori : 'Tanpa Kategori' }}
                                    </h5>
                                    <p class="card-text text-danger fw-bold" style="font-size: 12px;">
                                        Rp{{ number_format($prd->harga, 0, ',', '.') }}</p>
            
                                    <!-- Tombol Hapus -->
                                    <button class="btn btn-danger btn-sm btn-hapus" data-id="{{ $prd->id }}" style="font-size: 10px; padding: 2px 5px;">
                                        <i class="fas fa-trash-alt"></i> 
                                    </button>
                                                <!-- Tombol Edit Stok -->
                                    <button class="btn btn-warning btn-sm btn-edit-stok" data-id="{{ $prd->id }}" data-stok="{{ $prd->stok }}"
                                            style="font-size: 10px; padding: 2px 5px;">
                                            <i class="fas fa-edit"></i> 
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>                    
            </div>
        </div>              
    </div>

    <div class="modal fade" id="editStokModal" tabindex="-1" aria-labelledby="editStokLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStokLabel">Edit Stok Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editStokForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="produkId" name="produk_id">
                        <div class="form-group">
                            <label for="stokBaru">Stok Baru</label>
                            <input type="number" id="stokBaru" name="stok" class="form-control" required>
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
    
    <div class="modal fade" id="tambahProdukModal" tabindex="-1" aria-labelledby="tambahProdukLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahProdukLabel">Tambah Menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Menu</label>
                            <input type="text" name="nama_produk" placeholder="Masukkan Nama Produk" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" name="stok" placeholder="Masukkan Stok" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="kategori">Kategori</label>
                            <select name="kategori_id" id="kategori" class="form-control">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $kat)
                                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="harga">Harga Menu</label>
                            <input type="number" name="harga" placeholder="Masukkan Harga" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label>Foto Menu</label>
                            <input type="file" name="foto" class="form-control" accept="image/*">
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

@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('kategoriFilter').addEventListener('change', function() {
            let selectedCategory = this.value.toLowerCase();
            document.querySelectorAll('.produk-card').forEach(function(card) {
                let cardCategory = card.getAttribute('data-kategori').toLowerCase();
                if (selectedCategory === "" || cardCategory === selectedCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterButtons = document.querySelectorAll(".kategori-filter");
            const produkCards = document.querySelectorAll(".produk-card");

            filterButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const filter = this.getAttribute("data-filter");

                    // Hapus class 'active' dari semua tombol dan tambahkan ke tombol yang diklik
                    filterButtons.forEach(btn => btn.classList.remove("active", "btn-primary"));
                    this.classList.add("active", "btn-primary");
                    this.classList.remove("btn-outline-primary");

                    // Loop semua produk dan filter berdasarkan kategori
                    produkCards.forEach(card => {
                        const kategori = card.getAttribute("data-kategori");

                        if (filter === "all" || kategori === filter) {
                            card.style.display = "block"; // Tampilkan produk yang sesuai
                        } else {
                            card.style.display =
                            "none"; // Sembunyikan produk yang tidak sesuai
                        }
                    });
                });
            });
        });
    </script>
    <script>
        document.getElementById("searchProduk").addEventListener("keyup", function() {
            let searchValue = this.value.toLowerCase();
            let produkCards = document.querySelectorAll(".produk-card");

            produkCards.forEach(function(card) {
                let namaProduk = card.getAttribute("data-nama");

                if (namaProduk.includes(searchValue)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('.btn-hapus').forEach(button => {
                button.addEventListener('click', function () {
                    let produkId = this.getAttribute('data-id');
    
                    Swal.fire({
                        title: "Yakin ingin menghapus?",
                        text: "Data tidak dapat dikembalikan setelah dihapus!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`/admin/produk/${produkId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    "Content-Type": "application/json"
                                }
                            }).then(response => response.json())
                              .then(data => {
                                  if (data.status === "success") {
                                      Swal.fire("Terhapus!", "Produk telah dihapus.", "success")
                                          .then(() => location.reload());
                                  } else {
                                      Swal.fire("Gagal!", "Terjadi kesalahan saat menghapus.", "error");
                                  }
                              }).catch(error => {
                                  Swal.fire("Error!", "Tidak dapat terhubung ke server.", "error");
                              });
                        }
                    });
                });
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".btn-edit-stok").forEach(button => {
                button.addEventListener("click", function () {
                    let produkId = this.getAttribute("data-id");
                    let stokSaatIni = this.getAttribute("data-stok");
    
                    document.getElementById("produkId").value = produkId;
                    document.getElementById("stokBaru").value = stokSaatIni;
    
                    $('#editStokModal').modal('show');
                });
            });
    
            document.getElementById("editStokForm").addEventListener("submit", function (event) {
                event.preventDefault();
    
                let produkId = document.getElementById("produkId").value;
                let stokBaru = document.getElementById("stokBaru").value;
    
                fetch(`/admin/produk/${produkId}/update-stok`, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                    },
                    body: JSON.stringify({ stok: stokBaru })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire("Sukses!", data.message, "success").then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire("Error!", "Terjadi kesalahan, coba lagi.", "error");
                });
            });
        });
    </script>
    
    
@endpush
@endsection