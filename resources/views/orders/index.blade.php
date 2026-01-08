@extends('layouts.app')

@section('content')
<div class="container my-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
    
        <h2 class="no-print">{{ $pageTitle }}</h2>
        <h2 class="d-none d-print-block text-center w-100">{{ __('Order List') }}</h2>
        
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-secondary me-2 shadow-sm">
                <i class="bi bi-printer"></i> {{ __('Print') }}
            </button>
            <a href="{{ route('orders.create') }}" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-circle"></i> {{ __('Create New Order') }}
            </a>
        </div>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('orders.index') }}" class="row g-2 mb-3 no-print">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control"
                   placeholder="{{ __('Search orders...') }}" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> {{ __('Search') }}
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 text-center text-nowrap">
                    <thead class="table-light text-center">
                        <tr>
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Buyer') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Total Price ($)') }}</th>
                            <th>{{ __('Total Price (៛)') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th class="no-print">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">{{ __('Pending') }}</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">{{ __('Completed') }}</span>
                                @else
                                    <span class="badge bg-danger">{{ __('Cancelled') }}</span>
                                @endif
                            </td>
                            <td>${{ number_format($order->total_amount_usd, 2) }}</td>
                            <td>{{ number_format($order->total_amount_khr) }} ៛</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="no-print">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($isAdmin || $order->user_id === auth()->id())
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                @endif
                                @if($isAdmin)
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3 no-print">
        {{ $orders->links() }}
    </div>

</div>
@endsection