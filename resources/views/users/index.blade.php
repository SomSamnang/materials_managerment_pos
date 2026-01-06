@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Search Users --}}
    <div class="mb-3">
        <form method="GET" action="{{ route('users.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="ស្វែងរកអ្នកប្រើប្រាស់..." value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> ស្វែងរក
            </button>
        </form>
    </div>

    {{-- Add User Button --}}
    <a href="{{ route('users.create') }}" class="btn mb-3 btn-success">
        <i class="bi bi-person-plus"></i> បង្កើតអ្នកប្រើប្រាស់
    </a>

    {{-- Users Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table table-bordered table-striped align-middle mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>ល.រ</th>
                        <th>ឈ្មោះ</th>
                        <th>អុីម៊ែល</th>
                        <th>តួនាទី</th>
                        <th>ថ្ងៃបង្កើត</th>
                        <th>ថ្ងៃកែប្រែចុងក្រោយ</th>
                        <th>សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users->sortByDesc('id') as $user)
                    <tr class="align-middle">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role ?? '-' }}</td>
                        <td>{{ $user->created_at->timezone('Asia/Phnom_Penh')->format('d F Y | h:i A') }}</td>
                        <td>{{ $user->updated_at->timezone('Asia/Phnom_Penh')->format('d F Y | h:i A') }}</td>
                        <td class="d-flex justify-content-center gap-1">
                            {{-- Edit Button --}}
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning text-white" title="កែប្រែ">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            {{-- Delete Button --}}
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបអ្នកប្រើប្រាស់នេះមែនទេ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="លុប">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">គ្មានអ្នកប្រើប្រាស់ទេ</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Optional custom styles for buttons --}}
<style>
    .btn-warning {
        background: linear-gradient(45deg, #ffba00, #ff8c00);
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-warning:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    .btn-danger {
        background: linear-gradient(45deg, #ff4d4d, #d60000);
        border: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .btn-danger:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

    table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
</style>
@endsection
