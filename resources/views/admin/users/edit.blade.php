@extends('admin.layout')

@section('title', __('ui.edit_user'))

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ================= PAGE HEADER ================= --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">{{ __('ui.edit_user') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('ui.edit_user_desc') }}
                    </p>
                </div>

                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-light">
                    ← {{ __('ui.back') }}
                </a>
            </div>

            {{-- ================= CARD ================= --}}
            <div class="card">
                <div class="card-body p-4">

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('ui.name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required>
                        </div>

                        {{-- Email --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                {{ __('ui.email') }} <span class="text-danger">*</span>
                            </label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        {{-- Passwords --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    {{ __('ui.new_password') }}
                                </label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="{{ __('ui.password_keep_note') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">
                                    {{ __('ui.confirm_password') }}
                                </label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="{{ __('ui.repeat_new_password') }}">
                            </div>
                        </div>

                        {{-- ================= SHOP (OWNER ONLY) ================= --}}
                        @if (auth()->user()->role_id === \App\Models\Role::OWNER)
                            <div class="mb-4">
                                <label class="form-label fw-semibold d-flex align-items-center gap-2">
                                    <i class="bi bi-shop text-warning"></i>
                                    {{ __('ui.shop') }} <span class="text-danger">*</span>
                                </label>

                                <select name="restaurant_id" class="form-select" required>
                                    <option value="">— {{ __('ui.select_shop') }} —</option>

                                    @foreach ($restaurants as $restaurant)
                                        <option value="{{ $restaurant->id }}"
                                            {{ old('restaurant_id', $user->restaurant_id) == $restaurant->id ? 'selected' : '' }}>
                                            {{ $restaurant->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="form-text">
                                    {{ __('ui.change_user_shop_help') }}
                                </div>
                            </div>
                        @endif

                        {{-- Role --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('ui.role') }} <span class="text-danger">*</span>
                            </label>
                            <select name="role_id" class="form-select" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                        {{ ucfirst($role->name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr class="my-4">

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                {{ __('ui.user_id') }} #{{ $user->id }}
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-light">
                                    {{ __('ui.cancel') }}
                                </a>

                                <button class="btn btn-outline-warning px-4">
                                    <i class="bi bi-save"></i>
                                    {{ __('ui.save_changes') }}
                                </button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
