<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Hassan's Koekjes</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <style>
        :root {
            --brand-bg: #fcf4eb;
            --brand-primary: #e65c00;
            --brand-primary-hover: #cc5200;
            --brand-text: #4a3b32;
        }

        body {
            background-color: var(--brand-bg);
            font-family: 'Poppins', sans-serif;
            color: var(--brand-text);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            background-image: radial-gradient(circle at top right, rgba(230, 92, 0, 0.05), transparent 300px),
                              radial-gradient(circle at bottom left, rgba(230, 92, 0, 0.05), transparent 300px);
        }

        .login-card {
            max-width: 400px;
            width: 100%;
            padding: 2.5rem 2rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(230, 92, 0, 0.08);
            background: white;
            border: 1px solid rgba(230, 92, 0, 0.1);
        }

        .brand-logo {
            font-family: 'Dancing Script', cursive;
            color: var(--brand-primary);
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .btn-brand {
            background-color: var(--brand-primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-brand:hover {
            background-color: var(--brand-primary-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(230, 92, 0, 0.2);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e2d9d1;
            background-color: #faf8f5;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(230, 92, 0, 0.15);
            border-color: var(--brand-primary);
            background-color: white;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--brand-text);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="brand-logo">Hassan's Koekjes</div>
    <p class="text-center text-muted small mb-4">Sistem Manajemen & Back-Office</p>
    
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 border-0 small">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email Admin</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border-color: #e2d9d1;">
                    <i class="bi bi-envelope text-muted"></i>
                </span>
                <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
            </div>
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0" style="border-radius: 10px 0 0 10px; border-color: #e2d9d1;">
                    <i class="bi bi-lock text-muted"></i>
                </span>
                <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" placeholder="••••••••" required>
            </div>
        </div>
        <button type="submit" class="btn btn-brand w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Dasbor
        </button>
    </form>
    
    <div class="text-center mt-4">
        <a href="{{ url('/') }}" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Toko
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
