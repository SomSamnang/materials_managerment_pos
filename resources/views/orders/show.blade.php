@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center no-print">
                    <h4><i class="bi bi-eye"></i> {{ __('Order Details') }}</h4>
                    <div>
                        <button onclick="window.print()" class="btn btn-light btn-sm me-2">
                            <i class="bi bi-printer"></i> {{ __('Print Order') }}
                        </button>
                        @if($order->invoice)
                            <a href="{{ route('invoices.print', $order->invoice) }}" target="_blank" class="btn btn-light btn-sm me-2">
                                <i class="bi bi-printer"></i> {{ __('Print Invoice') }}
                            </a>
                        @endif
                        <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    {{-- Print Header --}}
                    <div class="d-none d-print-block text-center mb-4">
                        <h2 class="fw-bold">{{ __('Material Management System') }}</h2>
                        <h4>{{ __('Order Details') }}</h4>
                        <p class="text-muted">{{ __('Date') }}: {{ date('d/m/Y') }}</p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Buyer') }}:</strong> {{ $order->user->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Status') }}:</strong>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">{{ __('Pending') }}</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">{{ __('Completed') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Cancelled') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Total Price ($)') }}:</strong> ${{ number_format($order->total_amount_usd, 2) }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Total Price (៛)') }}:</strong> {{ number_format($order->total_amount_khr) }} ៛
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{ __('Created Date') }}:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>{{ __('Updated Date') }}:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="mb-3">
                        <strong>{{ __('Notes') }}:</strong>
                        <p>{{ $order->notes }}</p>
                    </div>
                    @endif

                    <h5>{{ __('Order Materials') }}</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Unit Price') }} ($)</th>
                                    <th>{{ __('Total Price') }} ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->materials as $material)
                                <tr>
                                    <td>{{ $material->name }}</td>
                                    <td>{{ $material->code }}</td>
                                    <td>{{ $material->pivot->quantity }}</td>
                                    <td>${{ number_format($material->pivot->unit_price_usd, 2) }}</td>
                                    <td>${{ number_format($material->pivot->quantity * $material->pivot->unit_price_usd, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($order->invoice)
                    <div class="mt-4">
                        <h5>{{ __('Invoice Info') }}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>{{ __('Invoice Number') }}:</strong> {{ $order->invoice->invoice_number }}</p>
                                <p><strong>{{ __('Issued Date') }}:</strong> {{ $order->invoice->issued_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>{{ __('Status') }}:</strong>
                                    @if($order->invoice->status == 'paid')
                                        <span class="badge bg-success">{{ __('Paid') }}</span>
                                    @elseif($order->invoice->status == 'unpaid')
                                        <span class="badge bg-warning">{{ __('Unpaid') }}</span>
                                    @elseif($order->invoice->status == 'accepted')
                                        <span class="badge bg-info">{{ __('Accepted') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('Overdue') }}</span>
                                    @endif
                                </p>
                                <p><strong>{{ __('Due Date') }}:</strong> {{ $order->invoice->due_date ? $order->invoice->due_date->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection