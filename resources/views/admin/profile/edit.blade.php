@extends('admin.layout')

@section('title', 'Edit Profile')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-8">

            {{-- ================= PAGE HEADER ================= --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Edit Profile</h3>
                    <p class="text-muted mb-0">
                        Update your personal information and security settings
                    </p>
                </div>

                <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
            </div>

            {{-- ================= VALIDATION ERRORS ================= --}}
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ================= CARD ================= --}}
            <div class="card shadow-sm border-0">
                <div class="card-body p-4 p-lg-5">

                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        {{-- ================= BASIC INFO ================= --}}
                        <div class="mb-4">
                            <h6 class="fw-semibold text-muted mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle text-warning"></i>
                                Basic Information
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Name</label>
                                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                        required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input name="email" type="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ================= SECURITY ================= --}}
                        <div class="mb-4">
                            <h6 class="fw-semibold text-muted mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock text-warning"></i>
                                Security
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        New Password
                                    </label>
                                    <input name="password" type="password" class="form-control">
                                    <div class="form-text">
                                        Leave blank to keep your current password
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        Confirm Password
                                    </label>
                                    <input name="password_confirmation" type="password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- ================= AVATAR ================= --}}
                        <div class="mb-4">
                            <h6 class="fw-semibold text-muted mb-3 d-flex align-items-center gap-2">
                                <i class="bi bi-image text-warning"></i>
                                Profile Picture
                            </h6>

                            <div class="d-flex flex-column flex-md-row align-items-start gap-4">

                                {{-- Current Avatar --}}
                                <div class="text-center">
                                    <div class="small fw-semibold text-muted mb-2">
                                        Current Avatar
                                    </div>

                                    <img id="avatar-preview"
                                        src="{{ $user->avatar
                                            ? asset('storage/avatars/' . $user->avatar)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=ffc107&color=212529' }}"
                                        class="rounded-circle border shadow-sm"
                                        style="width:120px;height:120px;object-fit:cover;">
                                </div>

                                {{-- Upload --}}
                                <div class="flex-grow-1">
                                    <label class="form-label fw-semibold">
                                        Upload New Avatar
                                    </label>
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                    <div class="form-text">
                                        JPG / PNG • Max 2MB • Square image recommended
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ================= ACTIONS ================= --}}
                        <div class="d-flex justify-content-end gap-2 pt-4 border-top">
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>

                            <button class="btn btn-outline-warning px-4">
                                <i class="bi bi-save"></i>
                                Save Changes
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    {{-- ================= AVATAR LIVE PREVIEW ================= --}}
    @push('scripts')
        <script>
            const input = document.querySelector('input[name="avatar"]');
            const preview = document.getElementById('avatar-preview');

            if (input && preview) {
                input.addEventListener('change', (e) => {
                    const file = e.target.files?.[0];
                    if (!file) return;
                    preview.src = URL.createObjectURL(file);
                });
            }
        </script>
    @endpush

@endsection
