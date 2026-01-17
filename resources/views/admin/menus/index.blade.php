@extends('admin.layout')

@section('title', 'Menus')

@section('content')

    {{-- ================= PAGE HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Menus</h3>
            <p class="text-muted mb-0">
                Manage menus linked to restaurants
            </p>
        </div>

        <a href="{{ route('admin.menus.create') }}" class="btn btn-outline-warning">
            <i class="bi bi-plus-circle"></i> New Menu
        </a>
    </div>

    {{-- ================= CARD ================= --}}
    <div class="card">
        <div class="card-body p-4">

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width:80px;">ID</th>
                            <th>Restaurant</th>
                            <th>Created</th>
                            <th class="text-end" style="width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $menu)
                            <tr>
                                <td class="text-muted">
                                    {{ $menu->id }}
                                </td>

                                <td class="fw-semibold">
                                    {{ $menu->restaurant->name ?? '—' }}
                                </td>

                                <td class="text-muted">
                                    {{ $menu->created_at->format('Y-m-d') }}
                                </td>

                                {{-- Actions --}}
                                <td class="text-end">
                                    <div class="d-inline-flex gap-1">
                                        <a href="{{ route('admin.menus.show', $menu->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                            class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this menu?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-warning btn-delete" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No menus found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing {{ $menus->firstItem() ?? 0 }}
                    – {{ $menus->lastItem() ?? 0 }}
                    of {{ $menus->total() ?? 0 }}
                </div>

                <div>
                    {{ $menus->links('pagination::bootstrap-5') }}
                </div>
            </div>

        </div>
    </div>

@endsection
