@extends('admin.layout')

@section('title', 'Create Menu')

@section('content')
<h1>Create Menu</h1>

<form action="{{ route('admin.menus.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="restaurant_id" class="form-label">Restaurant</label>
        <select name="restaurant_id" id="restaurant_id" class="form-select">
            @foreach($restaurants as $restaurant)
            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create</button>
</form>
@endsection
