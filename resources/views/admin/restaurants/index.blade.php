@extends('admin.layout')

@section('title', __('ui.shops'))

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ __('ui.shops') }}</h3>
            <p class="text-muted mb-0">
                {{ __('ui.manage_shops') }}
            </p>
        </div>

        <a href="{{ route('admin.restaurants.create') }}" class="btn btn-outline-warning">
            <i class="bi bi-plus-circle"></i> {{ __('ui.new_shop') }}
        </a>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            {{-- Search --}}
            <form class="row g-2 align-items-center mb-4" method="GET" action="{{ route('admin.restaurants.index') }}">
                <div class="col-md-10">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="{{ __('ui.search_shop_placeholder') }}">
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-warning">
                        <i class="bi bi-search"></i> {{ __('ui.search') }}
                    </button>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th style="width:60px;">#</th>
                            <th style="width:70px;">{{ __('ui.logo') }}</th>
                            <th>{{ __('ui.name') }}</th>
                            <th>{{ __('ui.address') }}</th>
                            <th>{{ __('ui.phone') }}</th>
                            <th>{{ __('ui.created') }}</th>
                            <th class="text-end" style="width:160px;">{{ __('ui.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($restaurants as $restaurant)
                            <tr class="table-row-hover">
                                <td class="text-muted">{{ $restaurant->id }}</td>

                                {{-- Logo --}}
                                <td>
                                    @if ($restaurant->logo)
                                        <img src="{{ asset('storage/logos/' . $restaurant->logo) }}"
                                            class="rounded-circle border" style="width:42px;height:42px;object-fit:cover;"
                                            alt="{{ $restaurant->name }}">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                            style="width:42px;height:42px;">
                                            <i class="bi bi-shop text-muted"></i>
                                        </div>
                                    @endif
                                </td>

                                {{-- Name --}}
                                <td class="fw-semibold">
                                    {{ $restaurant->name }}
                                </td>

                                <td class="text-muted">{{ $restaurant->address ?? '—' }}</td>
                                <td class="text-muted">{{ $restaurant->phone ?? '—' }}</td>
                                <td class="text-muted">{{ $restaurant->created_at?->format('Y-m-d') ?? '—' }}</td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.restaurants.show', $restaurant->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.view') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.restaurants.edit', $restaurant->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.restaurants.destroy', $restaurant->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('ui.delete_confirm_shop') }}');">
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
                                    {{ __('ui.no_shops_found') }}
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
                    {{ $restaurants->firstItem() ?? 0 }}
                    – {{ $restaurants->lastItem() ?? 0 }}
                    {{ __('ui.of') }}
                    {{ $restaurants->total() ?? 0 }}
                </div>

                <div>
                    {{ $restaurants->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

@endsection
