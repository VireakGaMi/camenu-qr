@extends('admin.layout')

@section('title', __('ui.dashboard'))

@section('content')

{{-- ================= HEADER ================= --}}
<div class="mb-4">
    <h2 class="fw-bold mb-1">{{ __('ui.dashboard') }}</h2>
    <p class="text-muted mb-0">
        {{ __('ui.welcome_back') }}, {{ auth()->user()->name }} 👋
        {{ __('ui.dashboard_overview') }}
    </p>
</div>

{{-- ================= QUICK OVERVIEW ================= --}}
<div class="row g-3 mb-4">

    {{-- Shops --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-lift">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-circle">
                    <i class="bi bi-shop"></i>
                </div>
                <div>
                    <div class="small text-muted">{{ __('ui.shops') }}</div>
                    <div class="fw-bold fs-5">{{ $stats['restaurants'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-lift">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-circle">
                    <i class="bi bi-tags"></i>
                </div>
                <div>
                    <div class="small text-muted">{{ __('ui.categories') }}</div>
                    <div class="fw-bold fs-5">{{ $stats['categories'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Users --}}
    <div class="col-12 col-md-6 col-xl-3">
        <div class="card card-lift">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="icon-circle">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <div class="small text-muted">{{ __('ui.users') }}</div>
                    <div class="fw-bold fs-5">{{ $stats['users'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Subscriptions --}}
    @if ($role->name === 'admin')
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-lift">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="icon-circle">
                        <i class="bi bi-card-checklist"></i>
                    </div>
                    <div>
                        <div class="small text-muted">{{ __('ui.subscriptions') }}</div>
                        <div class="fw-bold fs-5">{{ $stats['subscriptions'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- ================= MAIN MODULES ================= --}}
<div class="row g-4">

    {{-- Shops --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-lift h-100">
            <div class="card-body text-center p-4">
                <div class="icon-circle-lg mb-3">
                    <i class="bi bi-shop"></i>
                </div>
                <h5 class="fw-semibold">{{ __('ui.shops') }}</h5>
                <p class="text-muted small mb-2">
                    {{ $stats['restaurants'] ?? 0 }} {{ __('ui.total_shops') }}
                </p>
                <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-warning btn-sm">
                    {{ __('ui.open_shops') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Categories --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-lift h-100">
            <div class="card-body text-center p-4">
                <div class="icon-circle-lg mb-3">
                    <i class="bi bi-tags"></i>
                </div>
                <h5 class="fw-semibold">{{ __('ui.categories') }}</h5>
                <p class="text-muted small mb-2">
                    {{ $stats['categories'] ?? 0 }} {{ __('ui.total_categories') }}
                </p>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-warning btn-sm">
                    {{ __('ui.open_categories') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Users --}}
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card card-lift h-100">
            <div class="card-body text-center p-4">
                <div class="icon-circle-lg mb-3">
                    <i class="bi bi-people"></i>
                </div>
                <h5 class="fw-semibold">{{ __('ui.users') }}</h5>
                <p class="text-muted small">
                    {{ $role->name === 'admin'
                        ? __('ui.manage_owners_staff')
                        : __('ui.manage_staff') }}
                </p>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-warning btn-sm">
                    {{ __('ui.open_users') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Subscriptions & Plans --}}
    @if ($role->name === 'admin')
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card card-lift h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-circle-lg mb-3">
                        <i class="bi bi-card-checklist"></i>
                    </div>
                    <h5 class="fw-semibold">{{ __('ui.subscriptions') }}</h5>
                    <p class="text-muted small">{{ __('ui.active_plans') }}</p>
                    <a href="{{ route('admin.restaurant-subscriptions.index') }}"
                       class="btn btn-outline-warning btn-sm">
                        {{ __('ui.open_subscriptions') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="card card-lift h-100">
                <div class="card-body text-center p-4">
                    <div class="icon-circle-lg mb-3">
                        <i class="bi bi-stickies"></i>
                    </div>
                    <h5 class="fw-semibold">{{ __('ui.plans') }}</h5>
                    <p class="text-muted small">{{ __('ui.pricing_plans') }}</p>
                    <a href="{{ route('admin.subscription-plans.index') }}"
                       class="btn btn-outline-warning btn-sm">
                        {{ __('ui.open_plans') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

</div>

@endsection
