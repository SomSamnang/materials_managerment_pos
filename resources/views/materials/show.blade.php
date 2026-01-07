@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-box-seam text-primary me-2"></i>{{ __('Material Details') }}</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>{{ __('Back') }}
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-4 text-center">
                    @if($material->image)
                        <img src="{{ asset('storage/'.$material->image) }}" class="img-fluid rounded shadow-sm" alt="{{ $material->name }}" style="max-height: 300px;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center rounded shadow-sm" style="height: 300px; width: 100%;">
                            <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h4 class="fw-bold text-primary mb-3">{{ $material->name }}</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <th class="text-muted" style="width: 150px;">{{ __('Material Code') }}:</th>
                                    <td class="fw-bold">{{ $material->code }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('Status') }}:</th>
                                    <td>
                                        @if($material->status == 'active')
                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('Created Date') }}:</th>
                                    <td>{{ $material->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold text-muted mb-3">{{ __('Stock & Price Info') }}</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('Current Stock') }}:</span>
                                        <span class="fw-bold {{ $material->stock < $material->min_stock ? 'text-danger' : 'text-dark' }}">
                                            {{ $material->stock }}
                                            @if($material->stock < $material->min_stock)
                                                <i class="bi bi-exclamation-triangle-fill ms-1" title="{{ __('Low Stock') }}"></i>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('Min Stock') }}:</span>
                                        <span class="fw-bold">{{ $material->min_stock }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ __('Unit Price') }}:</span>
                                        <span class="fw-bold text-success">${{ number_format($material->price, 2) }} <small class="text-muted">({{ number_format($material->price * \App\Models\Setting::getExchangeRate(), 0) }} ៛)</small></span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>{{ __('Total Stock Value') }}:</span>
                                        <span class="fw-bold text-primary">${{ number_format($material->stock * $material->price, 2) }} <small class="text-muted">({{ number_format($material->stock * $material->price * \App\Models\Setting::getExchangeRate(), 0) }} ៛)</small></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="fw-bold border-bottom pb-2 mb-3">{{ __('Description') }}</h5>
                        <p class="text-muted">{{ $material->description ?? __('No description') }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection