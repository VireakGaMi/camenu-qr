<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Admin</title>

    <link rel="icon" href="{{ asset('img/logo.png') }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Minimal Auth Styling -->
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top, rgba(139, 69, 19, .08), transparent 60%),
                linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
        }

        .auth-card .card {
            border-radius: 1rem;
            border: 1px solid rgba(0, 0, 0, .06);
            box-shadow: 0 20px 40px rgba(0, 0, 0, .08);
        }

        .auth-logo {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(139, 69, 19, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
            color: #8B4513;
        }

        .form-control {
            border-radius: .6rem;
        }

        .form-control:focus {
            border-color: #8B4513;
            box-shadow: 0 0 0 .15rem rgba(139, 69, 19, .2);
        }

        .btn-primary {
            background: #8B4513;
            border-color: #8B4513;
            border-radius: .6rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background: #6f360f;
            border-color: #6f360f;
        }

        .auth-footer {
            text-align: center;
            font-size: .85rem;
            color: #9ca3af;
            margin-top: 1rem;
        }
    </style>

    @stack('styles')
</head>

<body>

    <div class="auth-wrapper">
        <div class="auth-card px-3">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>
