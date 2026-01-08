@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-end mb-3">
        <div class="btn-group shadow-sm">
            <a href="{{ route('language.switch', 'kh') }}" class="btn btn-sm {{ app()->getLocale() == 'kh' ? 'btn-primary' : 'btn-outline-primary' }}">
                ខ្មែរ
            </a>
            <a href="{{ route('language.switch', 'en') }}" class="btn btn-sm {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-primary' }}">
                English
            </a>
        </div>
    </div>

    {{-- Hero Section --}}
    <div class="card text-white mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-8 fw-bold mb-2">{{ __('Welcome to the System') }}</h1>
                    <p class="lead mb-0">{{ __('Modern and efficient material management system') }}</p>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <i class="fas fa-cogs" style="font-size: 6rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card mb-4">
        <div class="card-header fw-bold"><i class="fas fa-bolt text-warning me-2"></i>{{ __('Quick Actions') }}</div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                @if($isAdmin)
                <a href="{{ route('materials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>{{ __('Create Material') }}
                </a>
                @endif
                <a href="{{ route('orders.create') }}" class="btn btn-success">
                    <i class="fas fa-shopping-cart me-2"></i>{{ __('New Order') }}
                </a>
                <a href="{{ route('orders.index') }}" class="btn btn-primary">
                    <i class="fas fa-list me-2"></i>{{ __('Order List') }}
                </a>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-file-invoice me-2"></i>{{ __('Invoices') }}
                </a>
                @if($isAdmin)
                <a href="{{ route('users.create') }}" class="btn btn-info">
                    <i class="fas fa-user-plus me-2"></i>{{ __('Create User') }}
                </a>
                @endif
            
                @if($isAdmin)
                <a href="{{ route('materials.stock.create_bulk') }}" class="btn btn-dark">
                    <i class="bi bi-box-arrow-in-down me-2"></i> {{ __('Add Stock') }}
                </a>
                <a href="{{ route('purchases.create') }}" class="btn btn-secondary">
                    <i class="bi bi-cash-coin me-2"></i> {{ __('Record Purchase') }}
                </a>
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                    <i class="bi bi-truck me-2"></i> {{ __('Create Supplier') }}
                </a>
                @endif
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        {{-- Materials Section --}}
        <div class="col-12">
            <h4 class="mb-3"><i class="fas fa-boxes text-primary me-2"></i>{{ __('Materials') }}</h4>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-box fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalMaterials }}</div>
                        <div class="text-muted">{{ __('Total Materials') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white p-3 rounded-3 me-3">
                        <i class="fas fa-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $lowStockCount }}</div>
                        <div class="text-muted">{{ __('Low Stock Materials') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
             <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="fas fa-warehouse fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalStock }}</div>
                        <div class="text-muted">{{ __('Total Stock Quantity') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white p-3 rounded-3 me-3">
                        <i class="fas fa-coins fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $currencySymbol === '$' ? '$' : '' }}{{ number_format($totalMaterialPrice, $decimals) }} {{ $currencySymbol === '៛' ? '៛' : '' }}</div>
                        <div class="text-muted">{{ __('Total Material Value') }} ({{ session('currency', 'USD') }})</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users Section --}}
        @if($isAdmin)
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="fas fa-users text-success me-2"></i>{{ __('Users') }}</h4>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-users fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalUsers }}</div>
                        <div class="text-muted">{{ __('Total Users') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white p-3 rounded-3 me-3">
                        <i class="fas fa-user-shield fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalAdmins }}</div>
                        <div class="text-muted">{{ __('Admins') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="fas fa-user fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalRegularUsers }}</div>
                        <div class="text-muted">{{ __('Regular Users') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Orders Section --}}
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="fas fa-shopping-cart text-info me-2"></i>{{ __('Orders') }}</h4>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-clipboard-list fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalOrders }}</div>
                        <div class="text-muted">{{ __('Total Orders') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white p-3 rounded-3 me-3">
                        <i class="fas fa-clock fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $pendingOrders }}</div>
                        <div class="text-muted">{{ __('Pending Orders') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="fas fa-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $completedOrders }}</div>
                        <div class="text-muted">{{ __('Completed Orders') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white p-3 rounded-3 me-3">
                        <i class="fas fa-times-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $cancelledOrders }}</div>
                        <div class="text-muted">{{ __('Cancelled Orders') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Purchases Section --}}
        @if($isAdmin)
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="fas fa-file-invoice-dollar text-secondary me-2"></i>{{ __('Purchases') }}</h4>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-receipt fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalPurchases ?? 0 }}</div>
                        <div class="text-muted">{{ __('Purchase Transactions') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-dark text-white p-3 rounded-3 me-3">
                        <i class="fas fa-coins fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $currencySymbol === '$' ? '$' : '' }}{{ number_format($totalPurchaseCost ?? 0, $decimals) }} {{ $currencySymbol === '៛' ? '៛' : '' }}</div>
                        <div class="text-muted">{{ __('Total Expenses') }}</div>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        @endif

        {{-- Suppliers Section --}}
        @if($isAdmin)
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="bi bi-truck text-primary me-2"></i>{{ __('Suppliers') }}</h4>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalSuppliers ?? 0 }}</div>
                        <div class="text-muted">{{ __('Total Suppliers') }}</div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Recent Materials Table --}}
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-list text-primary me-2"></i>{{ __('Recent Materials') }}</h4>
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex gap-2">
                <select name="status" class="form-select" style="width: auto;">
                    <option value="all" {{ ($status ?? 'all') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                    <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="inactive" {{ ($status ?? '') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
                    <option value="low_stock" {{ ($status ?? '') == 'low_stock' ? 'selected' : '' }}>{{ __('Low Stock') }}</option>
                </select>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('Search') }}..." value="{{ $search ?? '' }}">
                    <button type="submit" class="btn btn-primary" aria-label="{{ __('Search') }}"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Stock') }}</th>
                            <th>{{ __('Min Stock') }}</th>
                            <th>{{ __('Unit Price') }}</th>
                            <th>{{ __('Total Price') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            @if($isAdmin)
                            <th class="text-end">{{ __('Actions') }}</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials->take(10) as $material)
                            <tr class="{{ $material->stock < $material->min_stock ? 'table-warning' : '' }}">
                                <td>{{ $material->id }}</td>
                                <td>
                                    @if($material->image)
                                        <img src="{{ asset('storage/'.$material->image) }}" width="50" class="rounded" alt="{{ __('Material Image') }}">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><code>{{ $material->code }}</code></td>
                                <td class="fw-semibold">{{ $material->name }}</td>
                                <td>
                                    @if($material->status == 'inactive')
                                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @else
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $material->stock < $material->min_stock ? 'bg-danger' : 'bg-primary' }}">{{ $material->stock }}</span>
                                    @if($material->stock < $material->min_stock)
                                        <span class="ms-2 text-danger fw-bold">{{ __('Low Stock') }}!</span>
                                    @endif
                                </td>
                                <td>{{ $material->min_stock }}</td>
                                <td class="text-success fw-bold">${{ number_format($material->price, 2) }}</td>
                                <td class="text-primary fw-bold">${{ number_format($material->stock * $material->price, 2) }}</td>
                                <td class="text-muted">{{ $material->created_at->setTimezone('Asia/Phnom_Penh')->format('d/m/Y') }}</td>
                                @if($isAdmin)
                                <td class="text-end">
                                    <a href="{{ route('materials.show', $material->id) }}" class="btn btn-sm btn-outline-info" title="{{ __('Details') }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                  
                                </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
                                    @if(!empty($search))
                                        <p class="mb-0">{{ __('No materials found for your search term') }} "{{ $search }}".</p>
                                    @else
                                        <p class="mb-0">{{ __('No recent materials found.') }}</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
