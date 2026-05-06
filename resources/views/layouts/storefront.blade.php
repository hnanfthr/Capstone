<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hassan's Koekjes - Premium Cookies</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #fcf4eb;
            --accent-color: #e65c00;
            --accent-hover: #cc5200;
            --text-dark: #4a3b32;
        }

        body { 
            background-color: var(--bg-color); 
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .font-script {
            font-family: 'Dancing Script', cursive;
        }

        .navbar {
            background-color: rgba(252, 244, 235, 0.95) !important;
            backdrop-filter: blur(10px);
        }

        .navbar-brand { 
            font-size: 1.8rem;
            color: var(--accent-color) !important; 
        }

        .btn-theme {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 24px;
            transition: all 0.3s ease;
        }

        .btn-theme:hover {
            background-color: var(--accent-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(230, 92, 0, 0.3);
        }

        .product-card {
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
        }

        .product-card img { 
            height: 220px; 
            object-fit: cover; 
            transition: transform 0.5s ease;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .brush-accent {
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0,10 Q25,0 50,10 T100,10 L100,20 L0,20 Z" fill="%23e65c00"/></svg>') no-repeat bottom;
            background-size: 100% 40%;
            display: inline-block;
            padding-bottom: 5px;
        }

        /* Animation classes */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }

        .fade-in-up.visible {
            opacity: 1;
            transform: translateY(0);
        }

        .badge-theme {
            background-color: var(--accent-color);
        }

        .star-rating i {
            color: #ffc107;
        }

        footer {
            background-color: #4a3b32;
            color: #fcf4eb;
        }
    </style>
</head>
<body>

<!-- Desktop Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top mb-4 d-none d-lg-block">
    <div class="container">
        <a class="navbar-brand font-script fw-bold" href="{{ route('storefront.index') }}">
            Hassan's Koekjes
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('storefront.index') }}">Katalog Kue</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item me-3">
                    <a class="nav-link fw-bold text-dark" href="{{ route('storefront.track') }}">
                        <i class="bi bi-search"></i> Lacak Pesanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-theme position-relative" href="{{ route('cart.index') }}">
                        <i class="bi bi-cart3"></i> Keranjang
                        @if(session('cart') && count(session('cart')) > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Top Header -->
<div class="d-block d-lg-none bg-white shadow-sm sticky-top py-2 px-3 mb-3 d-flex justify-content-between align-items-center" style="z-index: 1030;">
    <a class="font-script fw-bold text-decoration-none" href="{{ route('storefront.index') }}" style="font-size: 1.5rem; color: var(--accent-color);">
        Hassan's Koekjes
    </a>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="d-block d-lg-none fixed-bottom bg-white shadow-lg border-top" style="z-index: 1040;">
    <div class="d-flex justify-content-around py-2">
        <a href="{{ route('storefront.index') }}" class="text-decoration-none text-center flex-fill pb-1 {{ request()->routeIs('storefront.index') ? 'text-primary' : 'text-muted' }}" style="color: {{ request()->routeIs('storefront.index') ? 'var(--accent-color) !important' : '' }}">
            <i class="bi bi-shop fs-5 d-block mb-1"></i>
            <span style="font-size: 0.7rem; font-weight: 500;">Beranda</span>
        </a>
        <a href="{{ route('storefront.track') }}" class="text-decoration-none text-center flex-fill pb-1 {{ request()->routeIs('storefront.track') ? 'text-primary' : 'text-muted' }}" style="color: {{ request()->routeIs('storefront.track') ? 'var(--accent-color) !important' : '' }}">
            <i class="bi bi-search fs-5 d-block mb-1"></i>
            <span style="font-size: 0.7rem; font-weight: 500;">Lacak</span>
        </a>
        <a href="{{ route('cart.index') }}" class="text-decoration-none text-center flex-fill pb-1 position-relative {{ request()->routeIs('cart.index') ? 'text-primary' : 'text-muted' }}" style="color: {{ request()->routeIs('cart.index') ? 'var(--accent-color) !important' : '' }}">
            <div class="position-relative d-inline-block">
                <i class="bi bi-cart3 fs-5 d-block mb-1"></i>
                @if(session('cart') && count(session('cart')) > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white" style="font-size: 0.55rem; padding: 0.25em 0.4em;">
                        {{ count(session('cart')) }}
                    </span>
                @endif
            </div>
            <span style="font-size: 0.7rem; font-weight: 500; display: block;">Keranjang</span>
        </a>
    </div>
</nav>

<div class="container mb-5 min-vh-100">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @yield('content')
</div>

<footer class="text-center py-4 mt-auto">
    <div class="container fade-in-up">
        <h4 class="font-script text-warning mb-3">Hassan's Koekjes</h4>
        <p class="mb-2">Dibuat dengan bahan berkualitas dan penuh cinta.</p>
        <p class="small text-white-50 mb-0">&copy; {{ date('Y') }} Hassan's Koekjes. Hak Cipta Dilindungi.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Intersection Observer for scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in-up').forEach((el) => {
            observer.observe(el);
        });
    });
</script>
</body>
</html>
