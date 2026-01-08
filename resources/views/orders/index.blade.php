@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header text-white d-flex justify-content-between align-items-center no-print p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h4 class="mb-0 fw-bold"><i class="bi bi-cart-check me-2"></i> {{ $pageTitle }}</h4>
            <div>
                <button onclick="window.print()" class="btn btn-light btn-sm me-2 shadow-sm fw-bold text-primary">
                    <i class="bi bi-printer"></i> {{ __('Print') }}
                </button>
                <a href="{{ route('orders.create') }}" class="btn btn-outline-light btn-sm shadow-sm fw-bold">
                    <i class="bi bi-plus-circle"></i> {{ __('Create New Order') }}
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            {{-- Print Header --}}
            <div class="d-none d-print-block text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Company Logo" style="max-height: 60px;" class="mb-3">
                <h2 class="fw-bold text-uppercase" style="color: #764ba2;">{{ __('Material Management System') }}</h2>
                <h4 class="text-muted">{{ __('Order List') }}</h4>
                <p class="text-muted">{{ __('Date') }}: {{ date('d/m/Y') }}</p>
            </div>

            {{-- Search Form --}}
            <form method="GET" action="{{ route('orders.index') }}" class="row g-2 mb-4 no-print">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                               placeholder="{{ __('Search orders...') }}" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-search"></i> {{ __('Search') }}
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle"></i> {{ __('Reset') }}
                    </a>
                </div>
                <div class="col-md-3">
                
                    <a href="{{ route('orders.index', array_merge(request()->query(), ['show_all' => 'true'])) }}" class="btn {{ request('show_all') ? 'btn-secondary' : 'btn-outline-secondary' }} w-100">
                        <i class="bi bi-list-ul"></i> {{ __('Show All') }}
                    </a>
                </div>
                
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center text-nowrap">
                    <thead class="text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <tr>
                            <th class="py-3 rounded-start">{{ __('ID') }}</th>
                            <th class="py-3">{{ __('Buyer') }}</th>
                            <th class="py-3">{{ __('Status') }}</th>
                            <th class="py-3">{{ __('Total Price ($)') }}</th>
                            <th class="py-3">{{ __('Total Price (៛)') }}</th>
                            <th class="py-3">{{ __('Created Date') }}</th>
                            <th class="py-3 rounded-end no-print">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-bold text-muted">{{ $order->id }}</td>
                            <td class="fw-bold text-primary">{{ $order->user->name }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge rounded-pill bg-warning text-dark px-3 py-2">{{ __('Pending') }}</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge rounded-pill bg-success px-3 py-2">{{ __('Completed') }}</span>
                                @else
                                    <span class="badge rounded-pill bg-danger px-3 py-2">{{ __('Cancelled') }}</span>
                                @endif
                            </td>
                            <td class="fw-bold">${{ number_format($order->total_amount_usd, 2) }}</td>
                            <td>{{ number_format($order->total_amount_khr) }} ៛</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="no-print">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-info rounded-circle" title="{{ __('View') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($order->invoice)
                                    <a href="{{ route('invoices.print', $order->invoice) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" title="{{ __('Print Invoice') }}">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                @endif
                                @if($isAdmin || $order->user_id === auth()->id())
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-warning rounded-circle" title="{{ __('Edit') }}">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                @endif
                                @if($isAdmin)
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE') 
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')" title="{{ __('Delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="3" class="text-end text-muted pt-3">{{ __('Total Price (This Page)') }}</td>
                            <td class="pt-3 text-primary fs-6">${{ number_format($orders->sum('total_amount_usd'), 2) }}</td>
                            <td class="pt-3 text-secondary fs-6">{{ number_format($orders->sum('total_amount_khr')) }} ៛</td>
                            <td class="pt-3"></td>
                            <td class="no-print pt-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Pagination --}}
            @if(method_exists($orders, 'total'))
                <div class="d-flex justify-content-between align-items-center mt-4 no-print">
                    <div class="text-muted">
                        {{ __('Showing') }} {{ $orders->firstItem() ?? 0 }} – {{ $orders->lastItem() ?? 0 }} {{ __('of') }} {{ $orders->total() ?? 0 }}
                    </div>
                    <div class="btn-group">
                        @if ($orders->onFirstPage())
                            <button class="btn btn-outline-secondary" disabled>
                                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                            </button>
                        @else
                            <a href="{{ $orders->previousPageUrl() }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                            </a>
                        @endif

                        @if ($orders->hasMorePages())
                            <a href="{{ $orders->nextPageUrl() }}" class="btn btn-outline-primary">
                                {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary" disabled>
                                {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @else
                <div class="mt-4 no-print text-muted">
                    {{ __('Total Records') }}: {{ $orders->count() }}
                </div>
            @endif
          
        </div>
    </div>
</div>
@endsection