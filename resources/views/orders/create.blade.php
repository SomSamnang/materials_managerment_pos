@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i> {{ $pageTitle }}</h4>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-bold">{{ __('Buyer') }}</label>
                            <select name="user_id" id="user_id" class="form-select form-select-lg" required>
                                <option value="">{{ __('Select User') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-select form-select-lg" required>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="completed">{{ __('Completed') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('Materials') }}</label>
                            <div id="materials-container">
                                <div class="material-row d-flex align-items-end mb-2">
                                    <div class="me-2 flex-grow-1">
                                        <select name="materials[0][id]" class="form-select form-select-lg material-select" required>
                                            <option value="" data-price="0">{{ __('Select Material') }}</option>
                                            @foreach($materials as $material)
                                                <option value="{{ $material->id }}" data-price="{{ $material->price }}">{{ $material->name }} ({{ $material->code }}) - ${{ number_format($material->price, 2) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="me-2" style="width: 120px;">
                                        <input type="number" name="materials[0][quantity]" class="form-control form-control-lg quantity-input" placeholder="{{ __('Quantity') }}" min="1" required>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-lg remove-material" style="display: none;"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                            <button type="button" id="add-material" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-plus-circle me-1"></i> {{ __('Add Material') }}</button>
                        </div>

                        <div class="d-flex justify-content-end mb-4 p-3 rounded bg-light">
                            <h4 class="fw-bold mb-0">{{ __('Total') }}: <span id="total-price" style="color: #667eea;">$0.00</span></h4>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">{{ __('Notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control form-control-lg" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                                <i class="bi bi-arrow-left me-2"></i> {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-success btn-lg shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                <i class="bi bi-check-circle me-2"></i> {{ __('Create Order') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('materials-container');
    const totalPriceElement = document.getElementById('total-price');

    function calculateTotal() {
        let total = 0;
        const rows = container.querySelectorAll('.material-row');
        rows.forEach(row => {
            const select = row.querySelector('.material-select');
            const quantityInput = row.querySelector('.quantity-input');
            
            if (select && quantityInput) {
                const selectedOption = select.options[select.selectedIndex];
                const price = parseFloat(selectedOption.getAttribute('data-price')) || 0;
                const quantity = parseFloat(quantityInput.value) || 0;
                total += price * quantity;
            }
        });
        totalPriceElement.textContent = '$' + total.toFixed(2);
    }

    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('material-select') || e.target.classList.contains('quantity-input')) {
            calculateTotal();
        }
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            calculateTotal();
        }
    });

document.getElementById('add-material').addEventListener('click', function() {
    const rowCount = container.querySelectorAll('.material-row').length;
    const newRow = document.createElement('div');
    newRow.className = 'material-row d-flex align-items-end mb-2';
    newRow.innerHTML = `
        <div class="me-2 flex-grow-1">
            <select name="materials[${rowCount}][id]" class="form-select form-select-lg material-select" required>
                <option value="" data-price="0">{{ __('Select Material') }}</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}" data-price="{{ $material->price }}">{{ $material->name }} ({{ $material->code }}) - ${{ number_format($material->price, 2) }}</option>
                @endforeach
            </select>
        </div>
        <div class="me-2" style="width: 120px;">
            <input type="number" name="materials[${rowCount}][quantity]" class="form-control form-control-lg quantity-input" placeholder="{{ __('Quantity') }}" min="1" required>
        </div>
        <button type="button" class="btn btn-outline-danger btn-lg remove-material"><i class="bi bi-trash"></i></button>
    `;
    container.appendChild(newRow);
    updateRemoveButtons();
});

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('remove-material')) {
        e.target.closest('.material-row').remove();
        updateRemoveButtons();
        calculateTotal();
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.material-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-material');
        removeBtn.style.display = rows.length > 1 ? 'block' : 'none';
    });
}
});
</script>
@endsection