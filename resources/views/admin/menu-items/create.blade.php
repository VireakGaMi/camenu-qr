@extends('admin.layout')

@section('title', __('ui.create_menu_item'))

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- ================= PAGE HEADER ================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">{{ __('ui.create_menu_item') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('ui.create_menu_item_desc') }}
                </p>
            </div>

            <a href="{{ route('admin.restaurants.show', $restaurant->id) }}" class="btn btn-light">
                ← {{ __('ui.back') }}
            </a>
        </div>

        {{-- ================= ERRORS ================= --}}
        @if ($errors->has('limit') || $errors->any())
            <div class="alert alert-danger shadow-sm">
                <div class="fw-semibold mb-2">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ __('ui.fix_errors') }}
                </div>

                <ul class="mb-0 ps-3">
                    @if ($errors->has('limit'))
                        <li>{{ $errors->first('limit') }}</li>
                    @endif

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= CARD ================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                {{-- Context --}}
                <div class="alert alert-light border d-flex align-items-center gap-3 mb-4">
                    @if ($restaurant->logo)
                        <img
                            src="{{ asset('storage/logos/' . $restaurant->logo) }}"
                            class="rounded"
                            style="width:48px;height:48px;object-fit:cover;"
                        >
                    @endif

                    <div>
                        <div class="fw-semibold">{{ $restaurant->name }}</div>
                        <div class="small text-muted">
                            {{ __('ui.menu_id') }} #{{ $menu->id }}
                        </div>
                    </div>
                </div>

                <form
                    action="{{ route('admin.menu-items.store') }}"
                    method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">

                    {{-- ITEM NAME --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ __('ui.item_name') }} <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}"
                            placeholder="{{ __('ui.item_name_placeholder') }}"
                            required
                        >

                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            {{ __('ui.description') }}
                        </label>

                        <textarea
                            name="description"
                            class="form-control"
                            rows="3"
                            placeholder="{{ __('ui.item_description_placeholder') }}"
                        >{{ old('description') }}</textarea>
                    </div>

                    {{-- PRICE & CATEGORY --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('ui.price') }} <span class="text-danger">*</span>
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input
                                    type="number"
                                    name="price"
                                    step="0.01"
                                    min="0"
                                    class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price') }}"
                                    required
                                >
                            </div>

                            @error('price')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('ui.category') }}
                            </label>

                            <select name="category_id" class="form-select">
                                <option value="">
                                    — {{ __('ui.no_category') }} —
                                </option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- IMAGE --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            {{ __('ui.item_image') }}
                        </label>

                        <input type="file" name="image" class="form-control">

                        <div class="form-text">
                            {{ __('ui.image_hint') }}
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- ACTIONS --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ __('ui.menu_id') }} #{{ $menu->id }}
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.restaurants.show', $restaurant->id) }}"
                               class="btn btn-light">
                                {{ __('ui.cancel') }}
                            </a>

                            <button class="btn btn-outline-warning px-4">
                                <i class="bi bi-plus-circle"></i>
                                {{ __('ui.create_item') }}
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection
