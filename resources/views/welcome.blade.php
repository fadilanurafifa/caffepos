@extends('admin.layouts.base')
@section('title', 'Transaksi Penjualan')

@section('content')
{{-- @push('styles')
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: bold;
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        .table th {
            background: #f5f5f5;
        }

        .total-box {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border: 3px solid #28a745;
            border-radius: 8px;
            color: #28a745;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-success {
            background: #28a745;
            color: white;
            border: none;
        }

        .btn-success:hover {
            background: #1e7e34;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
            border: none;
        }

        .btn-danger:hover {
            background: #bd2130;
        }
        .customer-selection {
            background: #f8f9fa; /* Warna latar belakang */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #333;
        }

        .custom-select {
            border-radius: 8px;
            border: 1px solid #ccc;
            padding: 10px;
            transition: all 0.3s ease-in-out;
        }

        .custom-select:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .custom-select:hover {
            border-color: #0056b3;
        }
    </style>
@endpush --}}
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-user"></i> Pilih Tipe Pelanggan</h5>
        </div>
        <div class="card-body">
            <div class="customer-selection">
                <div class="d-flex flex-column flex-md-row gap-3 w-100">
                    <div class="flex-grow-1">
                        <label for="tipe_pelanggan" class="form-label fw-bold">Tipe Pelanggan :</label>
                        <select id="tipe_pelanggan" class="form-control custom-select" onchange="togglePelangganForm()">
                            <option value="member">Pelanggan Member</option>
                            <option value="Biasa">Pelanggan Biasa</option>
                        </select>
                    </div>
            
                    <div class="flex-grow-1" id="form_member">
                        <label for="pelanggan" class="form-label fw-bold">Pelanggan Member :</label>
                        <select id="pelanggan" class="form-control custom-select">
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach ($pelanggan as $p)
                                <option value="{{ $p->id }}">{{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Bagian Produk -->
        <div class="col-md-8" style="margin-top: 20px; margin-bottom: 30px;">
            <h1 class="h5 mb-3 text-gray-800">
                <i class="fas fa-shopping-basket"></i> Pilih Produk
            </h1>
            <div class="d-flex flex-wrap" id="produk-container" style="gap: 6px;"> 
                @foreach ($produk as $p)
                    <div class="produk-item" style="width: 130px;">
                        <div class="card" style="padding: 5px;">
                            <img src="{{ asset('assets/produk_fotos/' . $p->foto) }}" class="card-img-top"
                                 alt="{{ $p->nama_produk }}" 
                                 style="width: 100%; height: 110px; object-fit: cover;">
                            <div class="card-body text-center" style="padding: 6px;">
                                <h5 class="card-title" style="font-size: 11px; margin-bottom: 4px;">{{ $p->nama_produk }}</h5>
                                <p class="card-text" style="font-size: 11px; margin-bottom: 4px;">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                                <input type="number" id="jumlah-{{ $p->id }}" class="form-control mb-1" placeholder="Jumlah" min="1" value="1" 
                                       style="font-size: 11px; padding: 3px; height: 24px; text-align: center;">
                                <button onclick="tambahProduk({{ $p->id }}, '{{ $p->nama_produk }}', {{ $p->harga }})" 
                                        class="btn btn-primary btn-sm" style="font-size: 11px; padding: 2px 8px;">
                                    <i class="fas fa-shopping-cart"></i> 
                                </button>                            
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Bagian Keranjang -->
        <div class="col-md-4" style="margin-top: 60px;">
            <div class="card shadow-sm border-0" style="font-size: 12px;">
                <div class="card-header text-white" style="background-color: #2c3e50;">
                    <h6 class="mb-0"><i class="fas fa-shopping-cart"></i> Keranjang</h6>
                </div>
                
                <div class="card-body" style="padding: 10px;">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="keranjang">
                            <!-- Data keranjang akan masuk di sini -->
                        </tbody>
                    </table>
                    
                    <!-- Total -->
                    <div class="total-box text-right" style="font-size: 16px; margin-bottom: 10px;">
                        <strong>Total : Rp <span id="totalBayar">0</span></strong>
                    </div>

                    <!-- Tombol Simpan -->
                    <button type="button" id="btnSimpan" onclick="simpanTransaksi()" class="btn btn-sm text-white" 
                    style="width: 100%; background-color: #89AC46; border-color: #89AC46;">
                    <i class="fas fa-shopping-cart"></i> Simpan Keranjang
                </button>                
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
         let keranjang = [];

function tambahProduk(id, nama, harga) {
    let jumlah = document.getElementById(jumlah-${id}).value;
    if (jumlah <= 0) {
        alert("Jumlah produk harus lebih dari 0.");
        return;
    }

    let item = {
        id: id,
        nama: nama,
        harga: parseFloat(harga),
        jumlah: parseInt(jumlah),
        subtotal: parseFloat(harga) * parseInt(jumlah)
    };

    keranjang.push(item);
    renderKeranjang();
}

function renderKeranjang() {
    let tbody = document.getElementById('keranjang');
    tbody.innerHTML = "";
    let total = 0;

    keranjang.forEach((item, index) => {
        total += item.subtotal;
        tbody.innerHTML += `
            <tr>
                <td>${item.nama}</td>
                <td>Rp${item.harga.toLocaleString('id-ID')}</td>
                <td>${item.jumlah}</td>
                <td>Rp${item.subtotal.toLocaleString('id-ID')}</td>
                <td>
                    <button onclick="hapusProduk(${index})" 
                        style="
                            background: #dc3545; 
                            border: none; 
                            padding: 4px 6px; 
                            font-size: 12px; 
                            color: white; 
                            border-radius: 3px; 
                            transition: 0.3s ease-in-out;
                        " 
                        onmouseover="this.style.background='#bd2130'" 
                        onmouseout="this.style.background='#dc3545'">
                        <i class="fas fa-trash" style="font-size: 10px;"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    document.getElementById('totalBayar').innerText = total.toLocaleString('id-ID');
}
function hapusProduk(index) {
    keranjang.splice(index, 1);
    renderKeranjang();
}
        function simpanTransaksi() {
        let tipePelanggan = document.getElementById("tipe_pelanggan").value;
        let pelangganId = tipePelanggan === "member" ? document.getElementById("pelanggan").value : 0;

        if (keranjang.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Keranjang kosong!',
            text: 'Silakan tambahkan produk terlebih dahulu.',
        });
        return;
        }

        let produkData = keranjang.map(item => ({
        produk_id: item.id,
        jumlah: item.jumlah
        }));

        let requestData = {
        pelanggan_id: parseInt(pelangganId), // Jika pelanggan lain, otomatis = 0
        produk: produkData,
        };

        fetch("{{ route('penjualan.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(requestData)
        })
        .then(res => res.json())
        .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Transaksi Berhasil!',
                html: No Faktur: <b>${data.no_faktur}</b><br>Total Bayar: <b>Rp ${data.total_bayar.toLocaleString()}</b>
            }).then(() => {
                window.location.href = {{ route('admin.pembayaran.show', ['no_faktur' => '__NO_FAKTUR__']) }}.replace('_NO_FAKTUR_', data.no_faktur);
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.error,
            });
        }
        })
    .catch(err => {
        console.error("Error:", err);
        Swal.fire({
            icon: 'error',
            title: 'Terjadi Kesalahan',
            text: 'Silakan coba lagi.',
        });
    });
}
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let checkoutData = JSON.parse(localStorage.getItem("checkoutData")) || [];
            let keranjangTable = document.getElementById("keranjang");
            let totalBayar = 0;

            checkoutData.forEach((item) => {
                let subtotal = item.harga * item.jumlah;
                totalBayar += subtotal;

                let row = `
            <tr>
                <td>${item.nama_produk}</td>
                <td>Rp${item.harga.toLocaleString()}</td>
                <td>${item.jumlah}</td>
                <td>Rp${subtotal.toLocaleString()}</td>
                <td><button class="btn btn-danger btn-sm" onclick="hapusItem('${item.id}')">Hapus</button></td>
            </tr>
        `;
                keranjangTable.innerHTML += row;
            });

            document.getElementById("totalBayar").innerText = totalBayar.toLocaleString();
        });
    </script>
    <script>
        function updateFotoProduk() {
            let produk = document.getElementById("produk");
            let foto = produk.options[produk.selectedIndex].getAttribute("data-foto");
            let imgElement = document.getElementById("fotoProduk");

            if (foto && foto !== "null" && foto !== "") {
                imgElement.src = foto;
                imgElement.style.display = "block";
            } else {
                imgElement.style.display = "none";
            }
        }
    </script>
    <script>
        function togglePelangganForm() {
    let tipePelanggan = document.getElementById("tipe_pelanggan").value;
    let formMember = document.getElementById("form_member");

    if (tipePelanggan === "member") {
        formMember.style.display = "block";
    } else {
        formMember.style.display = "none";
    }
}
    </script>
@endpush