@extends('admin.layout')

@section('title', __('ui.subscription_details'))

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                {{ __('ui.subscription') }} #{{ $subscription->id }}
            </h3>
            <p class="text-muted mb-0">
                {{ __('ui.view_subscription_details') }}
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.restaurant-subscriptions.edit', $subscription->id) }}"
                class="btn btn-outline-warning btn-sm">
                <i class="bi bi-pencil"></i> {{ __('ui.edit') }}
            </a>

            <a href="{{ route('admin.restaurant-subscriptions.index') }}" class="btn btn-outline-warning btn-sm">
                ← {{ __('ui.back') }}
            </a>
        </div>
    </div>

    {{-- Success --}}
    @if (session('success'))
        <div class="alert alert-success mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            <dl class="row mb-4">

                <dt class="col-sm-3 text-muted">{{ __('ui.subscription_id') }}</dt>
                <dd class="col-sm-9 fw-semibold">
                    #{{ $subscription->id }}
                </dd>

                <dt class="col-sm-3 text-muted">{{ __('ui.shop') }}</dt>
                <dd class="col-sm-9 fw-semibold">
                    {{ $subscription->restaurant->name ?? '—' }}
                </dd>

                <dt class="col-sm-3 text-muted">{{ __('ui.plan') }}</dt>
                <dd class="col-sm-9">
                    {{ $subscription->plan->name ?? ($subscription->subscriptionPlan->name ?? '—') }}
                </dd>

                <dt class="col-sm-3 text-muted">{{ __('ui.status') }}</dt>
                <dd class="col-sm-9">
                    @if ($subscription->is_active)
                        <span class="badge bg-success-subtle text-success">
                            {{ __('ui.active') }}
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary">
                            {{ __('ui.inactive') }}
                        </span>
                    @endif
                </dd>

                <dt class="col-sm-3 text-muted">{{ __('ui.starts_at') }}</dt>
                <dd class="col-sm-9 text-muted">
                    {{ optional(\Carbon\Carbon::parse($subscription->starts_at))->format('Y-m-d') ?? '—' }}
                </dd>

                <dt class="col-sm-3 text-muted">{{ __('ui.ends_at') }}</dt>
                <dd class="col-sm-9 text-muted">
                    {{ optional(\Carbon\Carbon::parse($subscription->ends_at))->format('Y-m-d') ?? '—' }}
                </dd>

                <dt class="col-sm-3 text-muted">{{ __('ui.created_at') }}</dt>
                <dd class="col-sm-9 text-muted">
                    {{ $subscription->created_at->format('Y-m-d H:i') }}
                </dd>

            </dl>

            {{-- ================= ACTION ================= --}}
            <form action="{{ route('admin.restaurant-subscriptions.toggle', $subscription->id) }}" method="POST"
                onsubmit="return confirm('{{ __('ui.confirm_toggle_subscription') }}');">
                @csrf
                <button class="btn btn-outline-warning">
                    {{ $subscription->is_active ? __('ui.deactivate_subscription') : __('ui.activate_subscription') }}
                </button>
            </form>

        </div>
    </div>

@endsection
