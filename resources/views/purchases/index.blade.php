@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold no-print"><i class="bi bi-cash-coin text-primary me-2"></i>{{ __('Purchase Costs') }}</h1>
        
        {{-- Print Header (Visible only in print) --}}
        <div class="d-none d-print-block text-center w-100 mb-4">
            <h2 class="fw-bold mb-1">{{ __('Material Management System') }}</h2>
            <h4 class="mb-2">{{ __('Purchase Report') }}</h4>
            <p class="text-muted mb-0">{{ __('Date') }}: {{ date('d/m/Y') }}</p>
        </div>

        <div class="no-print">
            <button onclick="window.print()" class="btn btn-secondary me-2">
                <i class="bi bi-printer me-2"></i>{{ __('Print') }}
            </button>
            <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>{{ __('Add New Purchase') }}
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('purchases.index') }}" class="row g-2 mb-4 no-print">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="{{ __('Search materials or suppliers...') }}" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text">{{ __('From') }}</span>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text">{{ __('To') }}</span>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-2"></i>{{ __('Search') }}
            </button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th>{{ __('Material') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Quantity') }}</th>
                            <th>{{ __('Unit Cost') }}</th>
                            <th>{{ __('Unit Cost') }} (៛)</th>
                            <th>{{ __('Total Cost') }}</th>
                            <th>{{ __('Total Cost') }} (៛)</th>
                            <th>{{ __('Supplier') }}</th>
                            <th>{{ __('Notes') }}</th>
                            <th class="text-end no-print">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $purchase->material->name }}</span>
                                    <small class="text-muted">({{ $purchase->material->code }})</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-info text-dark">{{ $purchase->quantity }}</span></td>
                                <td class="text-primary">${{ number_format($purchase->unit_cost, 2) }}</td>
                                <td class="text-secondary">{{ number_format($purchase->unit_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛</td>
                                <td class="fw-bold text-success">${{ number_format($purchase->total_cost, 2) }}</td>
                                <td class="fw-bold text-danger">{{ number_format($purchase->total_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛</td>
                                <td>{{ $purchase->supplier ?? '-' }}</td>
                                <td>{{ $purchase->notes ?? '-' }}</td>
                                <td class="text-end no-print">
                                    <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info" title="{{ __('Details') }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning" title="{{ __('Edit') }}">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete?') }}');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">{{ __('No purchase records found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($purchases->count() > 0)
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end">{{ __('Total') }}:</td>
                            <td class="text-success">${{ number_format($totalFilteredCost, 2) }}</td>
                            <td class="text-danger">{{ number_format($totalFilteredCost * \App\Models\Setting::getExchangeRate(), 0) }} ៛</td>
                            <td colspan="3" class="no-print"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @if($purchases->hasPages())
            <div class="card-footer bg-white">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection