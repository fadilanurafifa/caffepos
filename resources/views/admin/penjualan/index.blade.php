@extends('admin.layouts.base')
@section('title', 'Transaksi Penjualan')

@section('content')
    <div class="container">
        <h2 class="title">Transaksi Penjualan</h2>

        <!-- Pilih Pelanggan -->
        <div class="form-group">
            <label>Pelanggan</label>
            <select id="pelanggan" class="form-control">
                @foreach ($pelanggan as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tambah Produk -->
        <div class="form-group">
            <label>Produk</label>
            <select id="produk" class="form-control" onchange="updateFotoProduk()">
                @foreach ($produk as $p)
                    <option value="{{ $p->id }}" 
                        data-harga="{{ $p->harga }}" 
                        data-foto="{{ asset('storage/produk_fotos/' . $p->foto) }}">
                        {{ $p->nama_produk }} - Rp{{ number_format($p->harga, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
            
            <!-- Foto Produk -->
            <img id="fotoProduk" src="" alt="Foto Produk" class="img-thumbnail mt-2" style="max-width: 150px; display: none;">
            
            <input type="number" id="jumlah" class="form-control mt-2" placeholder="Jumlah" min="1" value="1">
            <button onclick="tambahProduk()" class="btn btn-primary mt-2">Tambah Produk</button>
        </div>
        <!-- Keranjang -->
        <h3 class="sub-title">Keranjang</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="keranjang"></tbody>
        </table>

        <!-- Total dengan Box -->
        <div class="total-box">
            <strong>Total: Rp <span id="totalBayar">0</span></strong>
        </div>

        <!-- Tombol Simpan -->
        <button onclick="simpanTransaksi()" class="btn btn-success">Simpan Transaksi</button>
    </div>
@endsection

@push('styles')
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
    </style>
@endpush

@push('script')
    <script>
        let keranjang = [];

        function tambahProduk() {
            let produk = document.getElementById('produk');
            let jumlah = document.getElementById('jumlah').value;

            if (jumlah <= 0) {
                alert("Jumlah produk harus lebih dari 0.");
                return;
            }

            let harga = produk.options[produk.selectedIndex].getAttribute('data-harga');
            let nama = produk.options[produk.selectedIndex].text;

            let item = {
                id: produk.value,
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
                <button onclick="hapusProduk(${index})" class="btn btn-danger">Hapus</button>
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
            let pelangganId = document.getElementById('pelanggan').value;
            // let metodePembayaran = document.getElementById('metode_pembayaran').value;

            if (keranjang.length === 0) {
                alert("Keranjang masih kosong!");
                return;
            }

            let produkData = keranjang.map(item => ({
                produk_id: item.id,
                jumlah: item.jumlah
            }));

            let requestData = {
                pelanggan_id: pelangganId,
                produk: produkData,
                // metode_pembayaran: metodePembayaran
            };

            console.log("Data yang dikirim:", requestData); // Debugging sebelum dikirim

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
                        alert(`Transaksi berhasil!\nNo Faktur: ${data.no_faktur}\nTotal Bayar: Rp ${data.total_bayar}`);
                        location.reload();
                    } else {
                        alert("Gagal: " + data.error);
                    }
                })
                .catch(err => console.error("Error:", err));
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
@endpush
