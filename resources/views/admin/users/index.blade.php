@extends('admin.layout')

@section('title', __('ui.users'))

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">{{ __('ui.users') }}</h3>
            <p class="text-muted mb-0">
                {{ __('ui.manage_users') }}
            </p>
        </div>

        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-warning">
            <i class="bi bi-plus-circle"></i> {{ __('ui.new_user') }}
        </a>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            {{-- Search --}}
            <form class="row g-2 align-items-center mb-4" method="GET" action="{{ route('admin.users.index') }}">
                <div class="col-md-10">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control"
                        placeholder="{{ __('ui.search_users') }}">
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
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>{{ __('ui.name') }}</th>
                            <th>{{ __('ui.email') }}</th>
                            <th>{{ __('ui.role') }}</th>
                            <th>{{ __('ui.created') }}</th>
                            <th class="text-end" style="width:160px;">{{ __('ui.actions') }}</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="text-muted">{{ $user->id }}</td>

                                <td class="fw-semibold">
                                    {{ $user->name }}
                                </td>

                                <td class="text-muted">
                                    {{ $user->email }}
                                </td>

                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">
                                        {{ $user->role?->name ?? '—' }}
                                    </span>
                                </td>

                                <td class="text-muted">
                                    {{ $user->created_at?->format('Y-m-d') ?? '—' }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.view') }}">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="{{ __('ui.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('{{ __('ui.delete_user_confirm') }}');">
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
                                <td colspan="6" class="text-center py-5 text-muted">
                                    {{ __('ui.no_users_found') }}
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
                    {{ $users->firstItem() ?? 0 }}
                    – {{ $users->lastItem() ?? 0 }}
                    {{ __('ui.of') }}
                    {{ $users->total() ?? 0 }}
                </div>

                <div>
                    {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

@endsection
