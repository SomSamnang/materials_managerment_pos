@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0 rounded-4">

                <!-- Card Header -->
                <div class="card-header bg-gradient-primary text-white text-center py-3 rounded-top-4">
                    <h4 class="mb-0">បង្កើតអ្នកប្រើថ្មី</h4>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">

                    <!-- Form -->
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <!-- Name -->
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="ឈ្មោះ" value="{{ old('name') }}">
                            <label for="name">ឈ្មោះ</label>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="អ៊ីមែល" value="{{ old('email') }}">
                            <label for="email">អ៊ីមែល</label>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="ពាក្យសម្ងាត់">
                            <label for="password">ពាក្យសម្ងាត់</label>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Password Confirmation -->
                        <div class="form-floating mb-3">
                            <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="បញ្ជាក់ពាក្យសម្ងាត់">
                            <label for="password_confirmation">បញ្ជាក់ពាក្យសម្ងាត់</label>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <label for="role" class="form-label">តួនាទី</label>
                            <select name="role" id="role" class="form-select @error('role') is-invalid @enderror">
                                <option value="user" {{ old('role')=='user' ? 'selected' : '' }}>User</option>
                                <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Buttons side by side -->
                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary flex-grow-1 py-2 shadow-sm">រក្សាទុក</button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary flex-grow-1 py-2">ត្រឡប់ក្រោយ</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Gradient Primary for header */
.bg-gradient-primary {
    background: linear-gradient(135deg, #0d6efd 0%, #3a8dde 100%);
}

/* Card hover effect */
.card:hover {
    transform: translateY(-5px);
    transition: all 0.3s ease;
}

/* Floating label active */
.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    color: #0d6efd;
    font-weight: 500;
}

/* Button hover */
.btn-primary:hover {
    background-color: #0b5ed7;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .card {
        margin: 0 1rem;
    }
}
</style>
@endsection
