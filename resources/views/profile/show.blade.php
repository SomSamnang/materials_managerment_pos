@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Profile Info --}}
        <div class="col-lg-4">
            <div class="card text-center h-100">
                <div class="card-body d-flex flex-column align-items-center justify-content-center p-4">
                    <img id="image-preview" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="rounded-circle shadow-lg mb-3" style="width: 120px; height: 120px; object-fit: cover;">
                    <h3 class="card-title fw-bold mt-2">{{ $user->name }}</h3>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <span class="badge bg-success mt-2 fs-6 rounded-pill px-3 py-2">{{ __(ucfirst($user->role)) }}</span>
                </div>
            </div>
        </div>

        {{-- Update Password Form --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header fw-bold fs-5 bg-light border-0">
                    <i class="bi bi-pencil-square me-2 text-primary"></i> {{ __('Change Password') }}
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Profile Photo Input --}}
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label fw-medium">{{ __('Profile Photo') }}</label>
                            <div class="d-flex gap-2">
                                <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(event)">
                                @if($user->profile_photo)
                                    <button type="button" class="btn btn-outline-danger" onclick="if(confirm('{{ __('Are you sure you want to remove your profile photo?') }}')) document.getElementById('remove-photo-form').submit();">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Name (readonly) --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">{{ __('Name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly>
                        </div>

                        {{-- Email (readonly) --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">{{ __('Email') }}</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
                        </div>

                        <hr class="my-4">

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">{{ __('New Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="{{ __('Leave blank to keep current password') }}">
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium">{{ __('Confirm New Password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4 py-2 me-2">
                                <i class="bi bi-x-circle me-2"></i> {{ __('Back') }}
                            </a>

                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-save me-2"></i> {{ __('Save') }}
                            </button>
                        </div>
                    </form>

                    {{-- Hidden Remove Photo Form --}}
                    <form id="remove-photo-form" action="{{ route('profile.remove_photo') }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var input = event.target;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('image-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
