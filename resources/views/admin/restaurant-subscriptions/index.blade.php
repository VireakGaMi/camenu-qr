@extends('admin.layout')

@section('title', __('ui.shop_subscriptions'))

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ __('ui.shop_subscriptions') }}</h3>
            <p class="text-muted mb-0">
                {{ __('ui.shop_subscriptions_desc') }}
            </p>
        </div>

        <form class="d-flex gap-2" method="get" action="{{ route('admin.restaurant-subscriptions.index') }}">
            <input name="q" class="form-control" placeholder="{{ __('ui.search_shop_or_plan') }}"
                value="{{ request('q') }}">
            <button class="btn btn-outline-warning">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:70px;">#</th>
                            <th>{{ __('ui.shop') }}</th>
                            <th>{{ __('ui.plan') }}</th>
                            <th>{{ __('ui.status') }}</th>
                            <th>{{ __('ui.ends_at') }}</th>
                            <th>{{ __('ui.created') }}</th>
                            <th class="text-end" style="width:220px;">
                                {{ __('ui.actions') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($subscriptions as $sub)
                            <tr>
                                <td class="text-muted">{{ $sub->id }}</td>

                                <td class="fw-semibold">
                                    {{ $sub->restaurant->name ?? '—' }}
                                </td>

                                <td class="text-muted">
                                    {{ $sub->plan->name ?? ($sub->subscriptionPlan->name ?? '—') }}
                                </td>

                                <td>
                                    @if ($sub->is_active)
                                        <span class="badge bg-success-subtle text-success">
                                            {{ __('ui.active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            {{ __('ui.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted">
                                    {{ optional(\Carbon\Carbon::parse($sub->ends_at))->format('Y-m-d') ?? '—' }}
                                </td>

                                <td class="text-muted">
                                    {{ $sub->created_at->format('Y-m-d') }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.restaurant-subscriptions.show', $sub->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.view') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.restaurant-subscriptions.edit', $sub->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.restaurant-subscriptions.toggle', $sub->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('ui.confirm_toggle_subscription') }}');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                {{ $sub->is_active ? __('ui.deactivate') : __('ui.activate') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    {{ __('ui.no_subscriptions_found') }}
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
                    {{ $subscriptions->firstItem() ?? 0 }}
                    – {{ $subscriptions->lastItem() ?? 0 }}
                    {{ __('ui.of') }} {{ $subscriptions->total() ?? 0 }}
                </div>

                <div>
                    {{ $subscriptions->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

@endsection
