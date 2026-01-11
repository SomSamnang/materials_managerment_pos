    @extends('layouts.app')

    @section('content')
    <div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Search Users --}}
            <form method="GET" action="{{ route('users.index') }}" class="d-flex gap-2">
                <select name="status" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="banned" {{ ($status ?? '') == 'banned' ? 'selected' : '' }}>{{ __('Banned') }}</option>
                </select>
                <input type="text" name="search" class="form-control" placeholder="{{ __('Search users...') }}" value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> {{ __('Search') }}
                </button>
            </form>

        {{-- Add User Button --}}
        <div class="d-flex gap-2">
            <button onclick="printAllCards()" class="btn btn-secondary">
                <i class="bi bi-printer"></i> {{ __('Print All IDs') }}
            </button>
        @can('admin')
        <a href="{{ route('users.create') }}" class="btn btn-success">
            <i class="bi bi-person-plus"></i> {{ __('Create User') }}
        </a>
        @endcan
        </div>
    </div>

        {{-- Users Table --}}
        <div class="card shadow-sm border-0">
        <div class="card-body p-0 table-responsive">
                <table class="table table-hover table-bordered table-striped align-middle mb-0 text-center text-nowrap">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Photo') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Phone') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Last Updated') }}</th>
                            <th>{{ __('Last Login') }}</th>
                            @can('admin')
                            <th>{{ __('Actions') }}</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users->sortByDesc('id') as $user)
                        <tr class="align-middle">
                            <td>{{ $user->id }}</td>
                            <td>
                                <img src="{{ $user->profile_photo_url }}" alt="Profile" class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td>{{ __(ucfirst($user->role ?? '-')) }}</td>
                            <td>
                                @if($user->status === 'active')
                                    <span class="badge bg-success">{{ __('Active') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('Banned') }}</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->timezone('Asia/Phnom_Penh')->format('d F Y | h:i A') }}</td>
                            <td>{{ $user->updated_at->timezone('Asia/Phnom_Penh')->format('d F Y | h:i A') }}</td>
                            <td>{{ $user->last_login_at ? $user->last_login_at->timezone('Asia/Phnom_Penh')->format('d F Y | h:i A') : '-' }}</td>
                            @can('admin')
                            <td class="d-flex justify-content-center gap-1">
                                {{-- Print ID Card Button --}}
                                <button type="button" class="btn btn-sm btn-info text-white" 
                                    onclick="openPrintModal(this)"
                                    data-name="{{ $user->name }}"
                                    data-role="{{ ucfirst($user->role) }}"
                                    data-photo="{{ $user->profile_photo_url }}"
                                    data-id="{{ $user->id }}"
                                    data-email="{{ $user->email }}"
                                    data-phone="{{ $user->phone ?? '-' }}"
                                    data-joined="{{ $user->created_at->format('d M Y') }}"
                                    data-status="{{ ucfirst($user->status) }}"
                                    data-url="{{ route('users.edit', $user->id) }}"
                                    title="{{ __('Print ID Card') }}">
                                    <i class="bi bi-person-badge"></i>
                                </button>
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
                            @endcan
                        </tr>
                        @empty
                        <tr>
                            <td colspan="@can('admin') 11 @else 10 @endcan" class="text-center text-muted py-3">{{ __('No users found') }}</td>
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
    
    {{-- Include Print Resources (Modal, Scripts, Styles) --}}
    @include('users.print_resources')
    @endsection
