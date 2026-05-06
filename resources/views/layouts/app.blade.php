<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Dashboard - Hassan's Koekjes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --admin-bg: #fcf4eb;
            --admin-primary: #e65c00;
            --admin-secondary: #d35400;
            --admin-text: #4a3b32;
            --sidebar-width: 250px;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--admin-bg);
            color: var(--admin-text);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 1040;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #eee;
            background-color: var(--admin-secondary);
            color: white;
        }

        .nav-sidebar .nav-link {
            color: #555;
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
        }

        .nav-sidebar .nav-link:hover, .nav-sidebar .nav-link.active {
            background-color: rgba(211, 84, 0, 0.08);
            color: var(--admin-primary);
            border-left: 4px solid var(--admin-primary);
        }

        .nav-sidebar .nav-link i {
            width: 25px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Mobile specific adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1030;
                display: none;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }
        }

        /* Card Enhancements */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #f0f0f0;
            padding: 15px 20px;
            font-weight: 600;
        }

        .table {
            vertical-align: middle;
        }
        
        .table thead th {
            border-bottom: none;
            background-color: #f8f9fa;
            color: #555;
            font-weight: 600;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .slide-up {
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4 class="mb-0 fw-bold">Admin Panel</h4>
        <small class="text-white-50">Hassan's Koekjes</small>
    </div>
    <div class="py-3">
        <ul class="nav flex-column nav-sidebar">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('stocks.dashboard') ? 'active' : '' }}" href="{{ route('stocks.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="bi bi-box-seam"></i> Katalog Produk
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}" href="{{ route('orders.index') }}">
                    <i class="bi bi-cart-check"></i> Pesanan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('productions.*') ? 'active' : '' }}" href="{{ route('productions.report') }}">
                    <i class="bi bi-journal-text"></i> Produksi & Laporan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}" href="{{ route('employees.index') }}">
                    <i class="bi bi-people"></i> Karyawan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('attendances.*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
                    <i class="bi bi-calendar-check"></i> Absensi
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('payroll.*') ? 'active' : '' }}" href="{{ route('payroll.index') }}">
                    <i class="bi bi-cash-stack"></i> Penggajian
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link {{ request()->routeIs('banners.*') ? 'active' : '' }}" href="{{ route('banners.index') }}">
                    <i class="bi bi-sliders"></i> Pengaturan Website
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    
    <!-- Top Navbar -->
    <div class="top-navbar slide-up">
        <div class="d-flex align-items-center">
            <button class="btn btn-light d-lg-none me-3 shadow-sm border" id="sidebarToggle">
                <i class="bi bi-list fs-5"></i>
            </button>
            <h5 class="mb-0 fw-bold d-none d-sm-block text-dark">
                {{ date('l, d F Y') }}
            </h5>
            <h5 class="mb-0 fw-bold d-block d-sm-none text-dark">
                Dashboard
            </h5>
        </div>
        
        <div class="d-flex align-items-center">
            <span class="me-3 fw-medium d-none d-sm-block"><i class="bi bi-person-circle text-muted"></i> Administrator</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                    <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Page Content -->
    <div class="slide-up" style="animation-delay: 0.1s;">
        @yield('content')
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }

        sidebarToggle.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);

        // SweetAlert2 Toasts for Session Messages
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        // Global Delete Confirmation using SweetAlert2
        const deleteForms = document.querySelectorAll('.form-delete');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = this.getAttribute('data-confirm-message') || 'Yakin ingin menghapus data ini?';
                
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
</body>
</html>
