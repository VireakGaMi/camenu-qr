@extends('admin.layout')

@section('title', __('ui.subscription_plans'))

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ __('ui.subscription_plans') }}</h3>
            <p class="text-muted mb-0">
                {{ __('ui.subscription_plans_desc') }}
            </p>
        </div>

        <a href="{{ route('admin.subscription-plans.create') }}" class="btn btn-outline-warning">
            <i class="bi bi-plus-circle"></i> {{ __('ui.create_plan') }}
        </a>
    </div>

    {{-- Success --}}
    @if (session('success'))
        <div class="alert alert-success shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>{{ __('ui.plan') }}</th>
                            <th>{{ __('ui.price') }}</th>
                            <th>{{ __('ui.duration') }}</th>
                            <th>{{ __('ui.menu_limit') }}</th>
                            <th>{{ __('ui.staff_limit') }}</th>
                            <th class="text-end" style="width:200px;">
                                {{ __('ui.actions') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td class="text-muted">{{ $plan->id }}</td>

                                {{-- Plan --}}
                                <td>
                                    <div class="fw-semibold">{{ $plan->name }}</div>
                                    <div class="small text-muted">
                                        {{ $plan->duration_days ? $plan->duration_days . ' ' . __('ui.days_validity') : __('ui.no_expiration') }}
                                    </div>
                                </td>

                                {{-- Price --}}
                                <td class="fw-semibold">
                                    ${{ number_format($plan->price, 2) }}
                                </td>

                                {{-- Duration --}}
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        {{ $plan->duration_days }} {{ __('ui.days') ?? 'days' }}
                                    </span>
                                </td>

                                {{-- Menu limit --}}
                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $plan->menu_limit }}
                                    </span>
                                </td>

                                {{-- Staff limit --}}
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $plan->staff_limit }}
                                    </span>
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.subscription-plans.show', $plan->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.view') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.subscription-plans.edit', $plan->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.subscription-plans.destroy', $plan->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('ui.confirm_delete_plan') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-warning btn-delete"
                                                title="{{ __('ui.delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    {{ __('ui.no_subscription_plans') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    {{ __('ui.showing') }}
                    {{ $plans->firstItem() ?? 0 }}
                    – {{ $plans->lastItem() ?? 0 }}
                    {{ __('ui.of') }} {{ $plans->total() ?? 0 }}
                </div>

                <div>
                    {{ $plans->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

@endsection
