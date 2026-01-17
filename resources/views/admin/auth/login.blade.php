@extends('admin.auth.layout')

@section('title', 'Admin Login')

@section('content')
    <div class="card">
        <div class="card-body p-4">

            {{-- Logo / Icon --}}
            <div class="auth-logo">
                <i class="bi bi-shield-lock"></i>
            </div>

            {{-- Title --}}
            <h4 class="text-center fw-bold mb-1">Admin Sign In</h4>
            <p class="text-center text-muted small mb-4">
                Access the CaMenu-QR admin panel
            </p>

            {{-- Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger small">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Login Form --}}
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                        placeholder="admin@email.com" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small" for="remember">
                            Remember me
                        </label>
                    </div>

                    <a href="#" class="small text-decoration-none">
                        Forgot password?
                    </a>
                </div>

                <button class="btn btn-primary w-100">
                    Sign In
                </button>
            </form>

        </div>
    </div>

    <div class="auth-footer">
        © {{ date('Y') }} CaMenu-QR
    </div>
@endsection
