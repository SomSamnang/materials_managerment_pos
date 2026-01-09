@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> {{ __('Edit User') }}</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="profile_photo" class="form-label fw-bold">{{ __('Profile Photo') }}</label>
                            <div class="mb-2 d-flex align-items-center">
                                <img id="image-preview" src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" alt="Profile Photo" class="rounded-circle shadow-sm me-3" width="80" height="80" style="object-fit: cover;">
                                @if($user->profile_photo)
                                    <button type="submit" form="remove-photo-form" class="btn btn-outline-danger btn-sm" onclick="return confirm('{{ __('Are you sure?') }}')">
                                        <i class="bi bi-trash me-1"></i> {{ __('Remove') }}
                                    </button>
                                @endif
                            </div>
                            <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(event)">
                            @error('profile_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">{{ __('Name') }}</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">{{ __('Email') }}</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label fw-bold">{{ __('Phone') }}</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label fw-bold">{{ __('Role') }}</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>{{ __('User') }}</option>
                                <option value="manager" {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>{{ __('Manager') }}</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label fw-bold">{{ __('Status') }}</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>{{ __('Banned') }}</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">{{ __('Password') }} <small class="text-muted fw-normal">({{ __('Leave blank to keep current password') }})</small></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-bold">{{ __('Confirm Password') }}</label>
                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>

                    {{-- Hidden form for removing photo --}}
                    <form id="remove-photo-form" action="{{ route('users.remove_photo', $user->id) }}" method="POST" class="d-none">
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