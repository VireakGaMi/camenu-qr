@extends('admin.layout')

@section('title', __('ui.categories'))

@section('content')

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ __('ui.categories') }}</h3>
            <p class="text-muted mb-0">
                {{ __('ui.manage_categories') }}
            </p>
        </div>

        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-warning">
            <i class="bi bi-plus-lg me-1"></i> {{ __('ui.new_category') }}
        </a>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="card card-lift border-0">
        <div class="card-body">

            {{-- ================= SEARCH ================= --}}
            <form method="GET" action="{{ route('admin.categories.index') }}" class="row g-2 align-items-center mb-4">
                <div class="col-md-9">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="{{ __('ui.search_categories') }}">
                </div>

                <div class="col-md-3 d-grid">
                    <button class="btn btn-outline-warning" type="submit">
                        <i class="bi bi-search me-1"></i> {{ __('ui.search') }}
                    </button>
                </div>
            </form>

            {{-- ================= TABLE ================= --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr class="text-muted small">
                            <th style="width:60px;">#</th>
                            <th>{{ __('ui.name') }}</th>
                            <th>{{ __('ui.shop') }}</th>
                            <th>{{ __('ui.created') }}</th>
                            <th class="text-end" style="width:150px;">{{ __('ui.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="text-muted">{{ $category->id }}</td>

                                <td class="fw-semibold">
                                    {{ $category->name }}
                                </td>

                                <td class="text-muted">
                                    {{ $category->restaurant?->name ?? '—' }}
                                </td>

                                <td class="text-muted">
                                    {{ $category->created_at?->format('Y-m-d') ?? '—' }}
                                </td>

                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">

                                        <a href="{{ route('admin.categories.show', $category->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.view') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.categories.edit', $category->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('ui.delete_category_confirm') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="{{ __('ui.delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    {{ __('ui.no_categories_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ================= FOOTER ================= --}}
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
                <div class="text-muted small">
                    {{ __('ui.showing') }}
                    {{ $categories->firstItem() ?? 0 }}
                    – {{ $categories->lastItem() ?? 0 }}
                    {{ __('ui.of') }}
                    {{ $categories->total() ?? 0 }}
                </div>

                <div>
                    {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

@endsection
