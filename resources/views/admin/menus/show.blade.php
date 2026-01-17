@extends('admin.layout')

@section('title', 'Menu Details')

@section('content')
    <h1>Menu Details</h1>

{{-- <p><strong>ID:</strong> {{ $menu->id }}</p> --}}
<p><strong>Restaurant:</strong> {{ $menu->restaurant->name }}</p>
<p><strong>Created At:</strong> {{ $menu->created_at }}</p>

{{-- <h2>Menu Items</h2> --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Menu Items</h3>
    <div>
        <a href="{{ route('admin.menu-items.create', ['menu_id' => $menu->id]) }}" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Create Item</a>
        <a href="{{ route('admin.menu-items.index', ['menu_id' => $menu->id]) }}" class="btn btn-sm btn-outline-primary">Manage All Items</a>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menu->menuItems as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category->name ?? '—' }}</td>
                    <td>{{ number_format($item->price,2) }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.menu-items.show', $item->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.menu-items.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.menu-items.destroy', $item->id) }}" method="post" class="d-inline" onsubmit="return confirm('Delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">No items for this menu.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('admin.menus.index') }}" class="btn btn-secondary mt-3">Back to Menus</a>
@endsection
