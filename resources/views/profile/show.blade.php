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
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-lg mb-3"
                        style="width: 120px; height: 120px; font-size: 3rem; font-weight: bold;">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h3 class="card-title fw-bold mt-2">{{ $user->name }}</h3>
                    <p class="text-muted mb-1">{{ $user->email }}</p>
                    <span class="badge bg-success mt-2 fs-6 rounded-pill px-3 py-2">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>

        {{-- Update Password Form --}}
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header fw-bold fs-5 bg-light border-0">
                    <i class="bi bi-pencil-square me-2 text-primary"></i> ផ្លាស់ប្តូរពាក្យសម្ងាត់
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Name (readonly) --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-medium">ឈ្មោះ</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" readonly>
                        </div>

                        {{-- Email (readonly) --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-medium">អ៊ីមែល</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}" readonly>
                        </div>

                        <hr class="my-4">

                        {{-- Password --}}
                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">ពាក្យសម្ងាត់ថ្មី</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Leave blank to keep current password">
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-medium">បញ្ជាក់ពាក្យសម្ងាត់ថ្មី</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4 py-2 me-2">
                                <i class="bi bi-x-circle me-2"></i> ត្រឡប់ក្រោយ
                            </a>

                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="bi bi-save me-2"></i> រក្សាទុក
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
