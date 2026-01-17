@extends('admin.layout')

@section('title', __('ui.user'))

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Page Header --}}
            <div class="mb-4 d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="fw-bold mb-1">{{ __('ui.user_details') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('ui.view_user_info') }}
                    </p>
                </div>

                <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                    ← {{ __('ui.back') }}
                </a>
            </div>

            {{-- ERRORS --}}
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Success --}}
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- User Header --}}
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="rounded-circle bg-primary-subtle text-primary
                                d-flex align-items-center justify-content-center"
                            style="width:56px;height:56px;font-size:22px;font-weight:600;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                        <div>
                            <div class="fs-5 fw-bold">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>

                    {{-- Info Grid --}}
                    <div class="row g-3 mb-4">

                        <div class="col-md-6">
                            <div class="text-muted small fw-semibold">
                                {{ __('ui.email') }}
                            </div>
                            <div>{{ $user->email }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="text-muted small fw-semibold">
                                {{ __('ui.role') }}
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary">
                                {{ $user->role?->name ?? '—' }}
                            </span>
                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- Actions --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ __('ui.user_id') }} #{{ $user->id }}
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-outline-secondary">
                                {{ __('ui.edit') }}
                            </a>

                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('ui.confirm_delete_user') ?? 'Delete this user?' }}');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger">
                                    {{ __('ui.delete') }}
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection
