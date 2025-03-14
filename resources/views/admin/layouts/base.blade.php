<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <title> Cash POS - @yield('title')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <!-- Custom fonts for this template-->
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Custom styles for this template-->
    <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

    @stack('style')
    <style>
    #accordionSidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh; 
        overflow-y: auto; 
        z-index: 1000; 
    }
    .notification-icon {
        font-size: 16px; 
    }

    .notification-badge {
        top: 5px !important;  
        right: 4px !important; 
        font-size: 8px; 
        padding: 2px 4px;
        transform: translate(50%, -50%);
        min-width: 14px;
        min-height: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .notification-dropdown {
    width: 350px; /* Lebarkan dropdown agar lebih rapi */
    border-radius: 10px;
}

.dropdown-header {
    font-size: 14px;
    font-weight: bold;
    text-transform: uppercase;
}

.notification-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.notification-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.notification-text {
    flex: 1;
}

.notification-text small {
    display: block;
}
.notification-dropdown {
    width: 400px; /* Lebarkan dropdown agar lebih luas */
    max-height: 500px; /* Tambahkan batas tinggi jika banyak notifikasi */
    overflow-y: auto; /* Tambahkan scroll jika notifikasi terlalu banyak */
    border-radius: 10px;
    padding: 10px;
}

.dropdown-header {
    font-size: 16px; /* Perbesar ukuran teks header */
    font-weight: bold;
    text-transform: uppercase;
    padding: 10px;
}

.notification-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 8px; /* Tambahkan padding agar lebih rapi */
}

.notification-avatar {
    width: 45px; /* Perbesar avatar */
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.notification-text {
    flex: 1;
    font-size: 14px; /* Perbesar teks */
}

.notification-text small {
    display: block;
    font-size: 12px;
}
.notification-dropdown {
    width: 400px !important;  /* Lebarkan dropdown */
    max-height: 500px; /* Batasi tinggi untuk mencegah terlalu panjang */
    overflow-y: auto; /* Tambahkan scroll jika notifikasi terlalu banyak */
    border-radius: 10px;
    padding: 10px;
}

.notification-header {
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
    padding: 10px;
    background-color: #007bff; /* Warna biru seperti gambar */
    color: white;
    border-radius: 8px;
}

.notification-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
}

.notification-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.notification-text {
    flex: 1;
    font-size: 14px;
}

.notification-text small {
    display: block;
    font-size: 12px;
    color: #6c757d;
}

.notification-badge {
    top: -5px !important;  /* Sesuaikan agar lebih dekat ke ikon */
    right: -5px !important; /* Geser lebih dekat ke ikon */
    font-size: 10px;
    padding: 3px 6px;
    min-width: 16px;
    min-height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}


    </style>

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Cash POS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            @if (Auth::user()->role === 'admin' || (Auth::user()->role === 'owner'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>            
            @endif
            <!-- Divider -->
            @if (Auth::user()->role === 'admin' || (Auth::user()->role === 'kasir'))
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                MENU
            </div>
            @endif

            <!-- Nav Item - Pages Collapse Menu -->
            @if (Auth::user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                 <i class="fas fa-fw fa-tags"></i>
                 <span>Kategori</span>
             </a>
             
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Kategori:</h6>
                        <a class="collapse-item" href="{{ route('kategori.index') }}">Kategori</a>
                    </div>
                </div>
            </li>
            @endif
            <!-- Nav Item - Utilities Collapse Menu -->
             {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBarang"
                aria-expanded="true" aria-controls="collapseUtilities">
                <i class="fas fa-box"></i> <!-- Ganti ikon di sini -->
                <span>Manajemen Barang</span>
            </a>            
                <div id="collapseBarang" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Pembelajaan Barang</h6>
                        <a class="collapse-item" href="{{ route('barang.index') }}">Daftar Barang</a>
                    </div>
                </div>
            </li>  --}}
            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'kasir')
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProduk"
                aria-expanded="true" aria-controls="collapseProduk">
                <i class="fas fa-coffee"></i> <!-- Ganti ikon di sini -->
                <span>Produk Menu</span>
            </a>            
                <div id="collapseProduk" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Menu</h6>
                        <a class="collapse-item" href="{{ route('admin.produk.index') }}">Sistem Produk</a>
                    </div>
                </div>
            </li>
            @endif
            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSupplier"
                aria-expanded="true" aria-controls="collapseUtilities">
                 <i class="fas fa-fw fa-truck"></i>
                 <span>Supplier</span>
             </a>             
                <div id="collapseSupplier" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Supplier</h6>
                        <a class="collapse-item" href="{{ route('pemasok.index') }}">Data Supplier</a>
                    </div>
                </div>
            </li> --}}
            @if (Auth::user()->role === 'kasir' || Auth::user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePelanggan"
                aria-expanded="true" aria-controls="collapseUtilities">
                 <i class="fas fa-fw fa-address-book "></i>
                 <span>Member</span>
             </a>
                <div id="collapsePelanggan" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Pelanggan</h6>
                        <a class="collapse-item" href="{{ route('pelanggan.index') }}">Data Member</a>
                    </div>
                </div>
            </li>
            @endif
            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                TRANSAKSI
            </div>
            <!-- Nav Item - Pages Collapse Menu -->
            @if (Auth::user()->role === 'kasir' || Auth::user()->role === 'admin')
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePenjualan"
                aria-expanded="true" aria-controls="collapsePenjualan">
                 <i class="fas fa-fw fa-shopping-cart"></i>
                 <span>Manajemen Menu</span>
             </a>             
                <div id="collapsePenjualan" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Sistem Transaksi</h6>
                        <a class="collapse-item" href="{{ route('penjualan.index') }}">Transaksi Penjualan</a>
                    </div>
                </div>
            </li>
            @endif
            @if (Auth::user()->role === 'admin' || (Auth::user()->role === 'owner'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHistory"
                aria-expanded="true" aria-controls="collapsePenjualan">
                <i class="fas fa-receipt"></i>
                 <span>Laporan Transaksi</span>
             </a>             
                <div id="collapseHistory" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Transaksi</h6>
                        <a class="collapse-item" href="{{ route('history.penjualan') }}">Laporan Transaksi </a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporan"
                aria-expanded="true" aria-controls="collapsePenjualan">
                <i class="fas fa-chart-bar"></i>
                 <span>Laporan Penjualan</span>
             </a>             
                <div id="collapseLaporan" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Penjualan</h6>
                        <a class="collapse-item" href="{{ route('admin.laporan.penjualan') }}">Laporan Penjualan </a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporanProduk"
                aria-expanded="true" aria-controls="collapsePenjualan">
                <i class="fas fa-clipboard-list"></i>
                 <span>Laporan Data Produk</span>
             </a>             
                <div id="collapseLaporanProduk" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Produk</h6>
                        <a class="collapse-item" href="{{ route('laporan.produk') }}">Laporan Produk </a>
                    </div>
                </div>
            </li>
            @endif

            @if (Auth::user()->role === 'chef')
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseChef"
                aria-expanded="true" aria-controls="collapsePenjualan">
                <i class="fas fa-clipboard-list"></i>
                 <span>Daftar Pesanan</span>
             </a>             
                <div id="collapseChef" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manajemen Chef</h6>
                        <a class="collapse-item" href="{{ route('chef.index') }}">Pesanan</a>
                    </div>
                </div>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAkses"
                aria-expanded="true" aria-controls="collapsePenjualan">
                 <i class="fas fa-lock"></i>
                 <span>Sistem Akses</span>
                </a>   
                <div id="collapseAkses" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screen</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item cursor-pointer" href="#" 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                         Logout
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>    
                        </a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            {{-- <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
            </li> --}}

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="margin-left: 250px;">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn" type="button" style="background-color: #2c3e50; color: white;">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        @if (Auth::user()->role === 'kasir' || Auth::user()->role === 'admin')
                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center position-relative" href="#" id="notifikasiDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="position-relative">
                                    <i class="fas fa-bell fa-fw notification-icon"></i>
                                    @if(Auth::user()->unreadNotifications->count() > 0)
                                        <span class="badge rounded-pill bg-danger position-absolute notification-badge">
                                            {{ Auth::user()->unreadNotifications->count() }}
                                        </span>
                                    @endif
                                </span>
                            </a>
                        
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg p-3 notification-dropdown" aria-labelledby="notifikasiDropdown">
                                <li class="dropdown-header fw-bold text-white bg-primary p-2 rounded d-flex align-items-center">
                                    <i class="bi bi-app-indicator me-2"></i> STATUS NOTIFIKASI
                                </li>                                
                                @forelse(Auth::user()->unreadNotifications as $notification)
                                    <li class="notification-item d-flex align-items-center p-2">
                                        <div class="notification-text">
                                            <a href="#" class="dropdown-item text-wrap fw-bold text-dark">{{ $notification->data['message'] }}
                                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                        </a>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                @empty
                                    <li class="dropdown-item text-muted text-center">Tidak ada notifikasi</li>
                                @endforelse
                            
                                <li class="text-center">
                                    <form action="{{ route('kasir.readNotifications') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-primary">Tandai Semua Dibaca</button>
                                    </form>
                                </li>
                            </ul>
                            
                        </li>
                        @endif
                        <!-- Nav Item - Messages -->
                        {{-- <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ asset('assets/img/undraw_profile_1.svg') }}"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ asset('assets/img/undraw_profile_2.svg') }}"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="{{ asset('assets/img/undraw_profile_3.svg') }}"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li> --}}

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                {{ Auth::user()->name }} Cash POS
                            </span>
                            <img class="img-profile rounded-circle"
                                src="{{ asset('assets/img/undraw_profile.svg') }}">
                            </a>                        
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container bg-white">

                    <!-- Page Heading -->
                    @yield('content')
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            {{-- <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer> --}}
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    @stack('script')

</body>

</html>