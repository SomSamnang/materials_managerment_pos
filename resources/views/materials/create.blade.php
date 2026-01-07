@extends('layouts.app')

@section('content')
<div class="container my-4">
    <h4 class="fw-bold text-primary mb-4">{{ __('Add New Material') }}</h4>

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('materials.store') }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        @csrf

        <div class="row g-3">
            {{-- Auto-generated Code --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Code') }}</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $newCode) }}" readonly>
            </div>

            {{-- Material Name --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Name') }}</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            {{-- Stock --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Stock') }}</label>
                <div class="input-group">
                    <button class="btn btn-outline-secondary" type="button" id="minus-btn"><i class="bi bi-dash"></i></button>
                    <input type="number" id="stock" name="stock" class="form-control text-center" value="{{ old('stock', 0) }}" min="0" required>
                    <button class="btn btn-outline-secondary" type="button" id="plus-btn"><i class="bi bi-plus"></i></button>
                </div>
            </div>

            {{-- Minimum Stock --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Min Stock Quantity') }}</label>
                <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 0) }}" min="0" required>
            </div>

            {{-- Unit Price --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Unit Price') }} ($)</label>
                <input type="number" id="price" step="0.01" name="price" class="form-control" value="{{ old('price', 0) }}" required>
            </div>

            {{-- Total Price Display --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Total Price') }}</label>
                <div class="input-group">
                    <span class="input-group-text">USD</span>
                    <input type="text" id="total_usd" class="form-control" value="0.00" readonly>
                    <span class="input-group-text">៛</span>
                    <input type="text" id="total_riel" class="form-control" value="0" readonly>
                </div>
            </div>

            {{-- Image Upload --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Image') }}</label>
                <input type="file" name="image" class="form-control">
            </div>

            {{-- Description --}}
            <div class="col-md-6">
                <label class="form-label">{{ __('Description') }}</label>
                <textarea name="description" class="form-control" rows="3" style="white-space: pre-line;">{{ old('description') }}</textarea>
            </div>

        </div>

        {{-- Buttons --}}
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-success shadow-sm">
                <i class="bi bi-check2-circle"></i> {{ __('Save') }}
            </button>
            <a href="{{ route('materials.index') }}" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left-circle"></i> {{ __('Back') }}
            </a>
        </div>
    </form>
</div>

{{-- JS to calculate total dynamically --}}
<script>
    const stockInput = document.getElementById('stock');
    const priceInput = document.getElementById('price');
    const totalUSD = document.getElementById('total_usd');
    const totalRiel = document.getElementById('total_riel');
    const minusBtn = document.getElementById('minus-btn');
    const plusBtn = document.getElementById('plus-btn');
    const exchangeRate = 4100; // 1 USD = 4100 Riel

    function updateTotal() {
        const stock = parseFloat(stockInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        const usd = stock * price;
        const riel = usd * exchangeRate;
        totalUSD.value = usd.toFixed(2);
        totalRiel.value = riel.toLocaleString();
    }

    stockInput.addEventListener('input', updateTotal);
    priceInput.addEventListener('input', updateTotal);

    if (minusBtn && plusBtn) {
        minusBtn.addEventListener('click', function() {
            let currentValue = parseInt(stockInput.value) || 0;
            if (currentValue > 0) {
                stockInput.value = currentValue - 1;
                updateTotal();
            }
        });
        plusBtn.addEventListener('click', function() {
            let currentValue = parseInt(stockInput.value) || 0;
            stockInput.value = currentValue + 1;
            updateTotal();
        });
    }

    // Initialize
    updateTotal();
</script>

<style>
    form.card {
        border-radius: 15px;
    }
    form.card input, form.card textarea {
        border-radius: 8px;
    }
    #total_usd, #total_riel {
        background-color: #f4f7fb;
        font-weight: bold;
        text-align: right;
    }
    textarea {
        resize: vertical;
    }
</style>
@endsection
