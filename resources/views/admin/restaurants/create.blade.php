@extends('admin.layout')

@section('title', __('ui.create_shop'))

@section('content')
    <style>
        .owner-input {
            height: 46px;
            padding-right: 44px;
            border-radius: .65rem;
            font-weight: 500;
        }

        .owner-input:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 .15rem var(--brand-soft);
        }

        .owner-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ================= PAGE HEADER ================= --}}
            <div class="mb-4">
                <h3 class="fw-bold mb-1">{{ __('ui.create_shop') }}</h3>
                <p class="text-muted mb-0">
                    {{ __('ui.create_shop_desc') }}
                </p>
            </div>

            {{-- ================= CARD ================= --}}
            <div class="card">
                <div class="card-body p-4 p-lg-5">

                    <form action="{{ route('admin.restaurants.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Shop Name --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('ui.shop_name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Golden Spoon"
                                value="{{ old('name') }}" required>
                        </div>

                        {{-- Address --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('ui.address') }}</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Street, city, country">{{ old('address') }}</textarea>
                        </div>

                        {{-- Phone --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('ui.phone') }}</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. +855 12 345 678"
                                value="{{ old('phone') }}">
                        </div>

                        {{-- Owner --}}
                        @if (auth()->user()->role_id === \App\Models\Role::ADMIN)
                            <div class="mb-4">
                                <label class="form-label fw-semibold mb-2 d-flex align-items-center gap-2">
                                    <i class="bi bi-person-badge text-warning"></i>
                                    {{ __('ui.shop_owner') }} <span class="text-danger">*</span>
                                </label>

                                <div class="position-relative">
                                    <input list="owners-list" id="owner-search" class="form-control owner-input"
                                        placeholder="{{ __('ui.search_owner') }}" autocomplete="off" required>
                                    <span class="owner-icon"><i class="bi bi-search"></i></span>
                                </div>

                                <input type="hidden" name="owner_id" id="owner-id">

                                <datalist id="owners-list">
                                    @foreach ($owners as $owner)
                                        <option value="{{ $owner->name }} ({{ $owner->email }})"
                                            data-id="{{ $owner->id }}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                        @endif

                        {{-- Subscription --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                {{ __('ui.subscription_plan') }} <span class="text-danger">*</span>
                            </label>

                            <select name="subscription_plan_id" class="form-select" required>
                                <option value="">— {{ __('ui.select_plan') }} —</option>
                                @foreach ($plans as $plan)
                                    <option value="{{ $plan->id }}"
                                        {{ old('subscription_plan_id') == $plan->id ? 'selected' : '' }}>
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="form-text">
                                {{ auth()->user()->role_id === \App\Models\Role::ADMIN
                                    ? __('ui.plan_active_immediately')
                                    : __('ui.plan_active_after_payment') }}
                            </div>
                        </div>

                        {{-- Logo --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">{{ __('ui.shop_logo') }}</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div class="form-text">
                                Optional • JPG / PNG • Max 2MB • Recommended square image
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center pt-4 border-top">
                            <a href="{{ route('admin.restaurants.index') }}" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-left"></i> {{ __('ui.back') }}
                            </a>

                            <button class="btn btn-outline-warning px-4">
                                <i class="bi bi-check-circle"></i> {{ __('ui.create') }} {{ __('ui.shops') }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('owner-search').addEventListener('input', function() {
            const input = this.value;
            const options = document.querySelectorAll('#owners-list option');
            const hiddenInput = document.getElementById('owner-id');
            hiddenInput.value = '';
            options.forEach(option => {
                if (option.value === input) hiddenInput.value = option.dataset.id;
            });
        });
    </script>
@endsection
