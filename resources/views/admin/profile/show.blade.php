@extends('admin.layout')

@section('title', 'My Profile')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ================= PAGE HEADER ================= --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">My Profile</h3>
                    <p class="text-muted mb-0">
                        View your personal account information
                    </p>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.profile.edit') }}"
                        class="btn btn-outline-warning d-flex align-items-center gap-1">
                        <i class="bi bi-pencil"></i>
                        Edit Profile
                    </a>

                    <a href="{{ route('admin.dashboard') }}"
                        class="btn btn-outline-secondary d-flex align-items-center gap-1">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="alert alert-success shadow-sm mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ================= PROFILE CARD ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- Header --}}
                    <div class="d-flex align-items-center gap-4 mb-4">

                        {{-- Avatar --}}
                        <img src="{{ $user->avatar
                            ? asset('storage/avatars/' . $user->avatar)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=ffc107&color=212529' }}"
                            class="rounded-circle border" style="width:110px;height:110px;object-fit:cover;"
                            alt="{{ $user->name }}">

                        {{-- Name & Role --}}
                        <div>
                            <h4 class="fw-bold mb-1">{{ $user->name }}</h4>

                            <div class="text-muted mb-2">
                                {{ $user->email }}
                            </div>

                            <span class="badge bg-warning-subtle text-warning">
                                {{ ucfirst($user->role->name ?? 'User') }}
                            </span>
                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- ================= INFO GRID ================= --}}
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-muted mb-1">Name</div>
                                <div class="fw-semibold">{{ $user->name }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-muted mb-1">Email</div>
                                <div class="fw-semibold">{{ $user->email }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-muted mb-1">Role</div>
                                <div class="fw-semibold">
                                    {{ ucfirst($user->role->name ?? '—') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="small text-muted mb-1">Account Status</div>
                                <div class="fw-semibold text-success">
                                    Active
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
