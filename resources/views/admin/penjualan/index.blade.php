@extends('admin.layouts.base')
@section('title', 'Transaksi Penjualan')

@section('content')
    <div class="container">
        <h2 class="title">Transaksi Penjualan</h2>

        <!-- Pilih Tipe Pelanggan -->
        <div class="d-flex gap-4 w-100">
            <div class="flex-grow-1">
                <label for="tipe_pelanggan">Pilih Tipe Pelanggan :</label>
                <select id="tipe_pelanggan" class="form-control" onchange="togglePelangganForm()">
                    <option value="member">Pelanggan Member</option>
                    <option value="lain">Pelanggan Lain</option>
                </select>
            </div>

            <div class="flex-grow-1" id="form_member">
                <label for="pelanggan">Pelanggan Member</label>
                <select id="pelanggan" class="form-control">
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($pelanggan as $p)
                        <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Tambah Produk -->
        <div class="form-group">
            <label>Produk :</label>
            <div class="d-flex flex-wrap gap-3">
                @foreach ($produk as $p)
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset('storage/produk_fotos/' . $p->foto) }}" class="card-img-top" alt="{{ $p->nama_produk }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $p->nama_produk }}</h5>
                            <p class="card-text">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="produk" id="produk{{ $p->id }}" value="{{ $p->id }}" data-harga="{{ $p->harga }}" data-foto="{{ asset('storage/produk_fotos/' . $p->foto) }}" onchange="updateFotoProduk()">
                                <label class="form-check-label" for="produk{{ $p->id }}">
                                    Pilih Produk
                                </label>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- <!-- Foto Produk -->
            <img id="fotoProduk" src="" alt="Foto Produk" class="img-thumbnail mt-2"
                style="max-width: 150px; display: none;"> --}}

            <input type="number" id="jumlah" class="form-control mt-2" placeholder="Jumlah" min="1"
                value="1">
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
        <button onclick="simpanTransaksi()" class="btn btn-success">Simpan Keranjang</button>
    </div>
@endsection



@push('script')
    <script>
        let keranjang = [];

        function tambahProduk() {
            let produkElements = document.getElementsByName('produk');
            let produkId;
            produkElements.forEach(element => {
                if (element.checked) {
                    produkId = element.value;
                }
            });

            if (!produkId) {
                alert("Produk harus dipilih.");
                return;
            }

            let jumlah = document.getElementById('jumlah').value;

            if (jumlah <= 0) {
                alert("Jumlah produk harus lebih dari 0.");
                return;
            }

            let harga = document.querySelector(`#produk${produkId}`).getAttribute('data-harga');
            let nama = document.querySelector(`#produk${produkId}`).nextElementSibling.textContent;

            let item = {
                id: produkId,
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
                            html: `No Faktur: <b>${data.no_faktur}</b><br>Total Bayar: <b>Rp ${data.total_bayar.toLocaleString()}</b>`
                        }).then(() => {
                            window.location.href =
                                `{{ route('admin.pembayaran.show', ['no_faktur' => '__NO_FAKTUR__']) }}`
                                .replace('__NO_FAKTUR__', data.no_faktur);
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
