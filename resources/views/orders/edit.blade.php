@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center p-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h4 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i> {{ __('Edit Order') }}</h4>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-bold">{{ __('Buyer') }}</label>
                            <select name="user_id" id="user_id" class="form-select form-select-lg" required>
                                <option value="">{{ __('Select User') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label fw-bold">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-select form-select-lg" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">{{ __('Materials') }}</label>
                            <div id="materials-container">
                                @foreach($order->materials as $index => $material)
                                <div class="material-row d-flex align-items-end mb-2">
                                    <div class="me-2 flex-grow-1">
                                        <select name="materials[{{ $index }}][id]" class="form-select form-select-lg material-select" required>
                                            <option value="">{{ __('Select Material') }}</option>
                                            @foreach($materials as $mat)
                                                <option value="{{ $mat->id }}" {{ $material->id == $mat->id ? 'selected' : '' }}>{{ $mat->name }} ({{ $mat->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="me-2" style="width: 120px;">
                                        <input type="number" name="materials[{{ $index }}][quantity]" class="form-control form-control-lg" placeholder="{{ __('Quantity') }}" min="1" value="{{ $material->pivot->quantity }}" required>
                                    </div>
                                    <button type="button" class="btn btn-outline-danger btn-lg remove-material" {{ count($order->materials) > 1 ? '' : 'style="display: none;"' }}><i class="bi bi-trash"></i></button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-material" class="btn btn-outline-primary btn-sm mt-2"><i class="bi bi-plus-circle me-1"></i> {{ __('Add Material') }}</button>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold">{{ __('Notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control form-control-lg" rows="3">{{ $order->notes }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                                <i class="bi bi-arrow-left me-2"></i> {{ __('Back') }}
                            </a>
                            <button type="submit" class="btn btn-success btn-lg shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                                <i class="bi bi-check-circle me-2"></i> {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
document.getElementById('add-material').addEventListener('click', function() {
    const container = document.getElementById('materials-container');
    const rowCount = container.querySelectorAll('.material-row').length;
    const newRow = document.createElement('div');
    newRow.className = 'material-row d-flex align-items-end mb-2';
    newRow.innerHTML = `
        <div class="me-2 flex-grow-1">
            <select name="materials[${rowCount}][id]" class="form-select form-select-lg material-select" required>
                <option value="">{{ __('Select Material') }}</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->code }})</option>
                @endforeach
            </select>
        </div>
        <div class="me-2" style="width: 120px;">
            <input type="number" name="materials[${rowCount}][quantity]" class="form-control form-control-lg" placeholder="{{ __('Quantity') }}" min="1" required>
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
    }
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.material-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-material');
        removeBtn.style.display = rows.length > 1 ? 'block' : 'none';
    });
}
</script>
@endsection