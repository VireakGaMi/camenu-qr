@extends('admin.layout')

@section('title', 'Edit Menu')

@section('content')
<h1>Edit Menu</h1>

<form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="restaurant_id" class="form-label">Restaurant</label>
        <select name="restaurant_id" id="restaurant_id" class="form-select">
            @foreach($restaurants as $restaurant)
            <option value="{{ $restaurant->id }}" {{ $menu->restaurant_id == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>
@endsection
