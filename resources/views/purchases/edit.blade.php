@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>កែប្រែការទិញ / Edit Purchase</h1>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>ត្រឡប់ក្រោយ
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('purchases.update', $purchase) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="material_id" class="form-label">សម្ភារៈ <span class="text-danger">*</span></label>
                        <select name="material_id" id="material_id" class="form-select @error('material_id') is-invalid @enderror" required>
                            <option value="">ជ្រើសរើសសម្ភារៈ</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}" {{ old('material_id', $purchase->material_id) == $material->id ? 'selected' : '' }}>
                                    {{ $material->name }} ({{ $material->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('material_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="purchase_date" class="form-label">កាលបរិច្ឆេទ <span class="text-danger">*</span></label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control @error('purchase_date') is-invalid @enderror" 
                               value="{{ old('purchase_date', \Carbon\Carbon::parse($purchase->purchase_date)->format('Y-m-d')) }}" required>
                        @error('purchase_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="quantity" class="form-label">បរិមាណ <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                               value="{{ old('quantity', $purchase->quantity) }}" min="1" required>
                        @error('quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="unit_cost" class="form-label">តម្លៃដើម (ឯកតា) ($) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="unit_cost" id="unit_cost" class="form-control @error('unit_cost') is-invalid @enderror" 
                               value="{{ old('unit_cost', $purchase->unit_cost) }}" min="0" required>
                        @error('unit_cost')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="supplier" class="form-label">អ្នកផ្គត់ផ្គង់</label>
                    <input type="text" name="supplier" id="supplier" class="form-control @error('supplier') is-invalid @enderror" 
                           value="{{ old('supplier', $purchase->supplier) }}" list="supplierList">
                    <datalist id="supplierList">
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->name }}">
                        @endforeach
                    </datalist>
                    @error('supplier')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">កំណត់ចំណាំ</label>
                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $purchase->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>រក្សាទុកការកែប្រែ
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection