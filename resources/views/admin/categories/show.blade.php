@extends('admin.layout')

@section('title', __('ui.category_details'))

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- Page Header --}}
            <div class="mb-4 d-flex justify-content-between align-items-start">
                <div>
                    <h3 class="fw-bold mb-1">{{ __('ui.category_details') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('ui.view_category_info') }}
                    </p>
                </div>

                <a href="{{ route('admin.categories.index') }}" class="btn btn-light">
                    ← {{ __('ui.back') }}
                </a>
            </div>

            {{-- Card --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    {{-- Category Name --}}
                    <div class="mb-4">
                        <label class="text-muted small fw-semibold">
                            {{ __('ui.category_name') }}
                        </label>
                        <div class="fs-5 fw-bold">
                            {{ $category->name }}
                        </div>
                    </div>

                    {{-- Shop --}}
                    <div class="mb-4">
                        <label class="text-muted small fw-semibold">
                            {{ __('ui.shop') }}
                        </label>
                        <div class="fs-6">
                            {{ $category->restaurant?->name ?? '—' }}
                        </div>
                    </div>

                    {{-- Divider --}}
                    <hr class="my-4">

                    {{-- Actions --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            {{ __('ui.category_id') }} #{{ $category->id }}
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                                class="btn btn-outline-secondary">
                                {{ __('ui.edit') }}
                            </a>

                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('ui.delete_category_confirm') }}');">
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
