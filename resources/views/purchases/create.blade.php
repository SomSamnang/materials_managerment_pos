@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-cart-plus text-primary me-2"></i>កត់ត្រាការទិញចូល / Record Purchase</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('purchases.store') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            {{-- Date --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">កាលបរិច្ឆេទទិញ</label>
                                <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>

                            {{-- Supplier --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">អ្នកផ្គត់ផ្គង់ (Supplier)</label>
                                <select name="supplier" class="form-select">
                                    <option value="">-- សូមជ្រើសរើស --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->name }}">{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Material --}}
                            <div class="col-md-12">
                                <label class="form-label fw-bold">ជ្រើសរើសសម្ភារៈ</label>
                                <select name="material_id" class="form-select" required>
                                    <option value="">-- សូមជ្រើសរើស --</option>
                                    @foreach($materials as $material)
                                        <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->code }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">បរិមាណទិញចូល</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required>
                            </div>

                            {{-- Unit Cost --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">តម្លៃដើមក្នុងមួយឯកតា ($)</label>
                                <input type="number" name="unit_cost" id="unit_cost" class="form-control" step="0.01" min="0" required>
                            </div>

                            {{-- Total Cost Display --}}
                            <div class="col-12">
                                <div class="alert alert-light border d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">ចំណាយសរុប (Total Cost):</span>
                                    <span class="fs-4 fw-bold text-primary" id="total_display">$0.00</span>
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">កំណត់ចំណាំ</label>
                                <textarea name="notes" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <a href="{{ route('purchases.index') }}" class="btn btn-secondary me-2">បោះបង់</a>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-save me-2"></i>រក្សាទុក</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const qtyInput = document.getElementById('quantity');
    const costInput = document.getElementById('unit_cost');
    const totalDisplay = document.getElementById('total_display');

    function calcTotal() {
        const total = (qtyInput.value * costInput.value) || 0;
        totalDisplay.textContent = '$' + total.toFixed(2);
    }

    qtyInput.addEventListener('input', calcTotal);
    costInput.addEventListener('input', calcTotal);
</script>
@endsection