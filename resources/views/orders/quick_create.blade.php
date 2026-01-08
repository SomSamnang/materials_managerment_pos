@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-rocket-takeoff-fill text-primary me-2"></i>{{ __('Quick Order') }}</h1>
        <p class="text-muted">{{ __('Create a new order using materials in stock') }}</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Quick Order Form --}}
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="row g-4">

            {{-- Materials Selection (Left Column) --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0"><i class="bi bi-boxes text-primary me-2"></i>{{ __('Materials') }}</h5>
                        <div class="w-50">
                            <input type="text" id="material-search" class="form-control" placeholder="{{ __('Search by name or code...') }}">
                        </div>
                    </div>
                    <div class="card-body p-0" style="max-height: 65vh; overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center mb-0">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-start">{{ __('Name') }}</th>
                                        <th class="text-nowrap">{{ __('Stock') }}</th>
                                        <th class="text-nowrap">{{ __('Price') }} (USD)</th>
                                        <th style="width: 120px;">{{ __('Quantity') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="materials-tbody">
                                    @foreach($materials as $material)
                                        <tr data-price="{{ $material->price }}" @if($material->stock < $material->min_stock) class="table-warning" @endif>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-start">
                                                <div class="d-flex align-items-center">
                                                    @if($material->image)
                                                        <img src="{{ asset('storage/'.$material->image) }}" class="rounded me-3" style="width:40px; height:40px; object-fit:cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                            <i class="bi bi-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $material->name }}</div>
                                                        <small class="text-muted">{{ $material->code }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{ $material->stock }}
                                                @if($material->stock < $material->min_stock)
                                                    <span class="badge bg-danger ms-1">{{ __('Low Stock') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-success fw-bold">${{ number_format($material->price, 2) }}</td>
                                            <td>
                                                <input type="number" name="materials[{{ $material->id }}][quantity]" class="form-control form-control-sm text-center quantity-input" min="0" max="{{ $material->stock }}" value="0">
                                                <input type="hidden" name="materials[{{ $material->id }}][id]" value="{{ $material->id }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Details (Right Column) --}}
            <div class="col-lg-4">
                <div style="position: sticky; top: 80px;">
                    {{-- Order Info Card --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light border-0 p-3">
                            <h5 class="mb-0"><i class="bi bi-person-check-fill text-primary me-2"></i>{{ __('Details') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Select User') }}</label>
                                <select name="user_id" class="form-select" required>
                                    <option value="">-- {{ __('Select User') }} --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ __('Status') }}</label>
                                <select name="status" class="form-select" required>
                                    <option value="pending" selected>{{ __('Pending') }}</option>
                                    <option value="completed">{{ __('Completed') }}</option>
                                    <option value="cancelled">{{ __('Cancelled') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label fw-bold">{{ __('Notes') }}</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="{{ __('Notes (Optional)') }}">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Order Summary Card --}}
                    <div class="card">
                        <div class="card-header bg-light border-0 p-3">
                            <h5 class="mb-0"><i class="bi bi-receipt text-primary me-2"></i>{{ __('Total') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">{{ __('Subtotal') }} (USD)</span>
                                <span class="fw-bold" id="summary-subtotal-usd">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">{{ __('Subtotal') }} (KHR)</span>
                                <span class="fw-bold" id="summary-subtotal-khr">0 ៛</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-4 fw-bolder">
                                <span>{{ __('Total') }}</span>
                                <span id="summary-total-usd" class="text-primary">$0.00</span>
                            </div>
                        </div>
                        <div class="card-footer border-0 p-3">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-check-circle me-2"></i>{{ __('Create Order') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const exchangeRate = 4100;
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const summarySubtotalUSD = document.getElementById('summary-subtotal-usd');
    const summarySubtotalKHR = document.getElementById('summary-subtotal-khr');
    const summaryTotalUSD = document.getElementById('summary-total-usd');
    const materialSearch = document.getElementById('material-search');
    const materialsTbody = document.getElementById('materials-tbody');

    function updateSummary() {
        let totalUSD = 0;
        quantityInputs.forEach(input => {
            const row = input.closest('tr');
            const price = parseFloat(row.dataset.price);
            const quantity = parseInt(input.value) || 0;
            
            if (quantity > 0) {
                totalUSD += price * quantity;
                row.classList.add('table-success');
            } else {
                row.classList.remove('table-success');
            }
        });

        const totalKHR = totalUSD * exchangeRate;

        summarySubtotalUSD.textContent = `$${totalUSD.toFixed(2)}`;
        summarySubtotalKHR.textContent = `${totalKHR.toLocaleString('en-US')} ៛`;
        summaryTotalUSD.textContent = `$${totalUSD.toFixed(2)}`;
    }

    function filterMaterials() {
        const searchTerm = materialSearch.value.toLowerCase();
        const rows = materialsTbody.querySelectorAll('tr');

        rows.forEach(row => {
            const nameAndCode = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
            if (nameAndCode.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    quantityInputs.forEach(input => {
        input.addEventListener('input', updateSummary);
    });

    if (materialSearch) {
        materialSearch.addEventListener('keyup', filterMaterials);
    }

    updateSummary(); // Initial calculation
});
</script>
@endsection
