@extends('admin.layout')

@section('title', __('ui.extend_subscription'))

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">
                {{ __('ui.extend_subscription') }} #{{ $subscription->id }}
            </h3>
            <p class="text-muted mb-0">
                {{ __('ui.extend_subscription_desc') }}
            </p>
        </div>

        <a href="{{ route('admin.restaurant-subscriptions.show', $subscription->id) }}"
            class="btn btn-outline-warning btn-sm">
            <i class="bi bi-arrow-left"></i> {{ __('ui.back') }}
        </a>
    </div>

    {{-- ================= ERRORS ================= --}}
    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            <form method="POST" action="{{ route('admin.restaurant-subscriptions.update', $subscription->id) }}"
                onsubmit="return confirm('{{ __('ui.confirm_extend_subscription') }}');">
                @csrf
                @method('PATCH')

                {{-- Shop --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        {{ __('ui.shop') }}
                    </label>
                    <input class="form-control" value="{{ $subscription->restaurant->name ?? '—' }}" disabled>
                </div>

                {{-- Current Plan --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        {{ __('ui.current_plan') }}
                    </label>
                    <input class="form-control"
                        value="{{ $subscription->plan->name ?? ($subscription->subscriptionPlan->name ?? '—') }}" disabled>
                </div>

                {{-- Current Expiration Date --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        {{ __('ui.current_expiration_date') }}
                    </label>
                    <input class="form-control"
                        value="{{ optional(\Carbon\Carbon::parse($subscription->ends_at))->format('Y-m-d') ?? '—' }}" disabled>
                </div>

                <hr class="my-4">

                {{-- Select New Plan --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        {{ __('ui.select_plan_to_extend') }}
                        <span class="text-danger">*</span>
                    </label>

                    <select name="subscription_plan_id" class="form-select" required>
                        <option value="">— {{ __('ui.select_plan') }} —</option>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->id }}"
                                {{ $subscription->subscription_plan_id == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }}
                                @if (isset($plan->duration_days))
                                    ({{ $plan->duration_days }} {{ __('ui.days') ?? 'days' }})
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <div class="form-text">
                        {{ __('ui.extend_plan_help') }}
                    </div>
                </div>

                <hr class="my-4">

                {{-- Actions --}}
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        {{ __('ui.subscription_id') }} #{{ $subscription->id }}
                    </div>

                    <button class="btn btn-outline-warning px-4">
                        <i class="bi bi-arrow-repeat"></i>
                        {{ __('ui.extend_subscription') }}
                    </button>
                </div>

            </form>

        </div>
    </div>

@endsection
