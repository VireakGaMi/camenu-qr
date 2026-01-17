@extends('admin.layout')

@section('title', __('ui.subscription_plan'))

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                {{ $plan->name }}
            </h3>
            <p class="text-muted mb-0">
                {{ __('ui.subscription_plan_details') }}
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.subscription-plans.edit', $plan->id) }}" class="btn btn-outline-warning btn-sm">
                <i class="bi bi-pencil"></i> {{ __('ui.edit') }}
            </a>

            <a href="{{ route('admin.subscription-plans.index') }}"
                class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left"></i>
                {{ __('ui.back') }}
            </a>
        </div>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            <div class="row g-4">

                {{-- Left column --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('ui.plan_id') }}</div>
                        <div>#{{ $plan->id }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('ui.plan_name') }}</div>
                        <div class="fw-semibold">{{ $plan->name }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('ui.price') }}</div>
                        <div class="fw-semibold">
                            ${{ number_format($plan->price, 2) }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('ui.duration') }}</div>
                        <span class="badge bg-secondary-subtle text-secondary">
                            {{ $plan->duration_days }} {{ __('ui.days') }}
                        </span>
                    </div>
                </div>

                {{-- Right column --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('ui.menu_limit') }}</div>
                        <div class="fw-semibold">{{ $plan->menu_limit }}</div>
                        <div class="text-muted small">
                            {{ __('ui.zero_disable_menu') }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small fw-semibold">{{ __('ui.staff_limit') }}</div>
                        <div class="fw-semibold">{{ $plan->staff_limit }}</div>
                        <div class="text-muted small">
                            {{ __('ui.zero_disable_staff') }}
                        </div>
                    </div>
                </div>

            </div>

            {{-- Divider --}}
            <hr class="my-4">

            {{-- Features --}}
            <div>
                <div class="text-muted small fw-semibold mb-2">
                    {{ __('ui.features') }}
                </div>

                @if (is_array($plan->features) && count($plan->features))
                    <ul class="mb-0">
                        @foreach ($plan->features as $feature)
                            <li>{{ $feature }}</li>
                        @endforeach
                    </ul>
                @else
                    <div class="text-muted">
                        {{ __('ui.no_features_defined') }}
                    </div>
                @endif
            </div>

        </div>
    </div>

@endsection
