@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-danger text-white p-4">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-shield-lock-fill me-2"></i> {{ __('Password Expired') }}</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-warning mb-4">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ __('Your password has expired because it has not been changed in 90 days. Please update your password to continue.') }}
                    </div>

                    <form method="POST" action="{{ route('password.update_expired') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="current_password" class="form-label fw-bold">{{ __('Current Password') }}</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">{{ __('New Password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label fw-bold">{{ __('Confirm New Password') }}</label>
                            <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class="bi bi-arrow-repeat me-2"></i> {{ __('Update Password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection