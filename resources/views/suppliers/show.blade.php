@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-eye text-primary me-2"></i>{{ __('Supplier Details') }}</h1>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>{{ __('Back') }}
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">{{ __('Company Info') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">{{ __('Company Name') }}:</th>
                            <td class="fw-bold">{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">{{ __('Address') }}:</th>
                            <td>{{ $supplier->address ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">{{ __('Contact Info') }}</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">{{ __('Contact Person') }}:</th>
                            <td>{{ $supplier->contact_person ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">{{ __('Phone') }}:</th>
                            <td>{{ $supplier->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">{{ __('Email') }}:</th>
                            <td>{{ $supplier->email ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">{{ __('Supplied Materials') }}</h5>
                @if(isset($suppliedMaterials) && $suppliedMaterials->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>{{ __('Material') }}</th>
                                    <th>{{ __('Code') }}</th>
                                    <th>{{ __('Last Purchase Date') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliedMaterials as $material)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($material->image)
                                                    <img src="{{ asset('storage/'.$material->image) }}" class="rounded me-2" width="30" height="30" style="object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                        <i class="bi bi-image text-muted small"></i>
                                                    </div>
                                                @endif
                                                <span class="fw-bold">{{ $material->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $material->code }}</td>
                                        <td>
                                            @php
                                                $lastPurchase = $purchases->where('material_id', $material->id)->first();
                                            @endphp
                                            {{ $lastPurchase ? \Carbon\Carbon::parse($lastPurchase->purchase_date)->format('d/m/Y') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">{{ __('No materials found for this supplier.') }}</p>
                @endif
            </div>

          
        </div>
    </div>
</div>
@endsection