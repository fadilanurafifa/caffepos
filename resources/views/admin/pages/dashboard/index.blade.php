@extends('admin.layouts.base')
@section('title', 'Dashboard')
@section('content')
<div class="d-flex justify-content-between align-items-center">
    <div>
        <h1 class="h3 text-gray-800">
            Dashboard
        </h1>
        <p class="text-muted">
            <a href="{{ route('dashboard') }}" class="text-custom text-decoration-none">Home</a> / 
            <a href="#" class="text-custom text-decoration-none">Pages</a>
        </p>                
    </div>
</div>    
<div class="row">
    <!-- Member (Customer) Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Member (Customer)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ $totalPelanggan }} Member
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>            
        </div>
    </div>

    <!-- Transaksi Penjualan Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Transaksi Penjualan
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    {{ number_format($persentase, 0) }}%
                                </div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: {{ $persentase }}%" aria-valuenow="{{ $persentase }}" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>            
        </div>
    </div>

    <!-- Income (Pemasukan) Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Income (Pemasukan)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Rp {{ number_format($totalIncome, 0, ',', '.') }}
                        </div>
                        <small class="text-muted">{{ number_format($incomePercentage, 2) }}% dari target</small>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>

<!-- Chart Section -->
<div class="row">
    <div class="col-xl-12 col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">STATISTIKA PENJUALAN</h6>
            </div>
            <div class="card-body">
                <canvas id="salesChart" style="height: 400px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('salesChart').getContext('2d');

    // Buat efek gradient untuk garis chart
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(54, 162, 235, 0.5)');  // Warna awal (biru transparan)
    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');   // Warna akhir (transparan)

    var salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($months) !!}, // Data bulan dari Controller
            datasets: [{
                label: 'Total Penjualan',
                data: {!! json_encode($salesByMonth) !!}, // Data penjualan dari DB
                backgroundColor: gradient, // Efek gradient di area bawah garis
                borderColor: 'rgba(54, 162, 235, 1)', // Warna garis
                borderWidth: 3, // Ketebalan garis
                pointBackgroundColor: 'rgba(54, 162, 235, 1)', // Warna titik data
                pointBorderColor: '#fff', // Border putih di titik data
                pointRadius: 6, // Ukuran titik data
                pointHoverRadius: 8, // Ukuran titik saat hover
                fill: true, // Aktifkan efek area di bawah garis
                tension: 0.3, // Membuat garis lebih melengkung
                shadowOffsetX: 4, // Efek shadow
                shadowOffsetY: 4,
                shadowBlur: 10,
                shadowColor: 'rgba(0, 0, 0, 0.2)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: "#333",
                        font: {
                            size: 14,
                            weight: "bold"
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: "rgba(0,0,0,0.8)",
                    titleFont: { size: 14, weight: "bold" },
                    bodyFont: { size: 12 },
                    padding: 10,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: "#666",
                        font: { size: 12, weight: "bold" }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)'
                    },
                    ticks: {
                        color: "#666",
                        font: { size: 12, weight: "bold" }
                    }
                }
            },
            animation: {
                duration: 1500,
                easing: 'easeInOutQuart'
            }
        }
    });
</script>
@endpush

