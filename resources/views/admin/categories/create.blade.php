@extends('admin.layout')

@section('title', __('ui.create_category'))

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ================= PAGE HEADER ================= --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">{{ __('ui.create_category') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('ui.create_category_desc') }}
                    </p>
                </div>

                <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                    ← {{ __('ui.back') }}
                </a>
            </div>

            {{-- ================= ERRORS ================= --}}
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm">
                    <div class="fw-semibold mb-2">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        {{ __('ui.fix_errors') }}
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ================= CARD ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        {{-- CATEGORY NAME --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('ui.category_name') }} <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g. Drinks, Main Course, Desserts" required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- SHOP --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('ui.shop') }} <span class="text-danger">*</span>
                            </label>

                            <select name="restaurant_id" class="form-select @error('restaurant_id') is-invalid @enderror"
                                required>
                                <option value="">— {{ __('ui.select_shop') }} —</option>

                                @foreach ($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}"
                                        {{ old('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('restaurant_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="form-text">
                                {{ __('ui.category_shop_help') }}
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ACTIONS --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                {{ __('ui.category_plan_note') }}
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                                    {{ __('ui.cancel') }}
                                </a>

                                <button class="btn btn-outline-warning px-4">
                                    <i class="bi bi-plus-circle"></i>
                                    {{ __('ui.create_category') }}
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
