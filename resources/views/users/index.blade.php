@extends('layouts.app')

@section('content')
<div class="container-fluid">

    {{-- Search Users --}}
    <div class="mb-3">
        <form method="GET" action="{{ route('users.index') }}" class="d-flex gap-2">
            <input type="text" name="search" class="form-control" placeholder="{{ __('Search users...') }}" value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> {{ __('Search') }}
            </button>
        </form>
    </div>

    {{-- Add User Button --}}
    <a href="{{ route('users.create') }}" class="btn mb-3 btn-success">
        <i class="bi bi-person-plus"></i> {{ __('Create User') }}
    </a>

    {{-- Users Table --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover table table-bordered table-striped align-middle mb-0 text-center">
                <thead class="table-dark">
                    <tr>
                        <th>{{ __('ID') }}</th>
                        <th>{{ __('Photo') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Phone') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Created Date') }}</th>
                        <th>{{ __('Last Updated') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users->sortByDesc('id') as $user)
                    <tr class="align-middle">
                        <td>{{ $user->id }}</td>
                        <td>
                            <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random' }}" alt="Profile" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? '-' }}</td>
                        <td>{{ $user->role ?? '-' }}</td>
                        <td>{{ $user->created_at->timezone('Asia/Phnom_Penh')->format('d F Y | h:i A') }}</td>
                        <td>{{ $user->updated_at->timezone('Asia/Phnom_Penh')->format('d F Y | h:i A') }}</td>
                        <td class="d-flex justify-content-center gap-1">
                            {{-- Edit Button --}}
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning text-white" title="{{ __('Edit') }}">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            {{-- Delete Button --}}
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this user?') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="{{ __('Delete') }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-3">{{ __('No users found') }}</td>
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
