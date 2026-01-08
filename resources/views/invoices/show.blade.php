@extends('layouts.app')

@section('content')
<style>
    .invoice-card {
        border-radius: 16px;
    }

    .invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 2rem;
    }

    .invoice-badge {
        background: rgba(255,255,255,0.15);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
    }

    .info-box {
        background: #f8f9fc;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        height: 100%;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 4px;
    }

    .info-value {
        font-weight: 600;
        font-size: 1rem;
    }

    .table thead {
        background: #f1f3f9;
    }

    .table th {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table td {
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9ff;
    }

    .total-row {
        background: #f1f3f9;
        font-weight: 700;
    }
</style>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">

            <div class="card shadow-lg border-0 invoice-card">

                {{-- HEADER --}}
                <div class="invoice-header d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold mb-1">
                            <i class="bi bi-receipt me-2"></i>{{ $pageTitle }}
                        </h3>
                        <div class="invoice-badge">
                            Invoice #{{ $invoice->invoice_number }}
                        </div>
                    </div>

                    <a href="{{ route('invoices.index') }}" class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                    </a>
                </div>

                {{-- BODY --}}
                <div class="card-body p-4">

                    {{-- SUMMARY --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="info-box">
                                <div class="info-label">{{ __('Buyer') }}</div>
                                <div class="info-value">
                                    {{ $invoice->order->user->name }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <div class="info-label">{{ __('Issued Date') }}</div>
                                <div class="info-value">
                                    {{ $invoice->issued_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box">
                                <div class="info-label">{{ __('Due Date') }}</div>
                                <div class="info-value">
                                    {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TOTALS --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">{{ __('Total Price (USD)') }}</div>
                                <div class="info-value text-success">
                                    ${{ number_format($invoice->total_amount_usd, 2) }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-box">
                                <div class="info-label">{{ __('Total Price (KHR)') }}</div>
                                <div class="info-value text-primary">
                                    {{ number_format($invoice->total_amount_khr) }} ៛
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ORDER INFO --}}
                    <h5 class="fw-bold mb-3">{{ __('Order Details') }}</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>{{ __('Material') }}</th>
                                    <th class="text-center">{{ __('Quantity') }}</th>
                                    <th class="text-end">{{ __('Unit Price ($)') }}</th>
                                    <th class="text-end">{{ __('Total ($)') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->order->materials as $material)
                                <tr>
                                    <td>
                                        <strong>{{ $material->name }}</strong><br>
                                        <small class="text-muted">{{ $material->code }}</small>
                                    </td>
                                    <td class="text-center">
                                        {{ $material->pivot->quantity }}
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($material->pivot->unit_price_usd, 2) }}
                                    </td>
                                    <td class="text-end">
                                        ${{ number_format($material->pivot->quantity * $material->pivot->unit_price_usd, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
