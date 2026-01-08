@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-eye text-primary me-2"></i>{{ __('Purchase Details') }}</h1>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>{{ __('Back') }}
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">{{ __('Material Info') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">{{ __('Material Name') }}:</th>
                            <td class="fw-bold">{{ $purchase->material->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">{{ __('Material Code') }}:</th>
                            <td>{{ $purchase->material->code }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">{{ __('Purchase Info') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">{{ __('Date') }}:</th>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">{{ __('Supplier') }}:</th>
                            <td>{{ $purchase->supplier ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">{{ __('Quantity') }}:</th>
                            <td><span class="badge bg-info text-dark fs-6">{{ $purchase->quantity }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">{{ __('Cost Info') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">{{ __('Unit Cost') }}:</th>
                            <td>${{ number_format($purchase->unit_cost, 2) }} <span class="text-muted">({{ number_format($purchase->unit_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛)</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">{{ __('Total Cost') }}:</th>
                            <td class="fw-bold text-success fs-5">${{ number_format($purchase->total_cost, 2) }} <span class="fs-6 text-muted">({{ number_format($purchase->total_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛)</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">{{ __('Notes') }}</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $purchase->notes ?? __('No notes') }}
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>
@endsection