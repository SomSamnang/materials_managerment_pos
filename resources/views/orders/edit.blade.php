@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-warning text-white text-center">
                    <h4><i class="bi bi-pencil"></i> {{ $pageTitle }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('orders.update', $order) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="user_id" class="form-label">អ្នកបញ្ជាទិញ</label>
                            <select name="user_id" id="user_id" class="form-select" required>
                                <option value="">ជ្រើសរើសអ្នកបញ្ជាទិញ</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $order->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">ស្ថានភាព</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>រងចាំ</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>បញ្ចប់</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>បោះបង់</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">សម្ភារៈ</label>
                            <div id="materials-container">
                                @foreach($order->materials as $index => $material)
                                <div class="material-row d-flex align-items-end mb-2">
                                    <div class="me-2 flex-grow-1">
                                        <select name="materials[{{ $index }}][id]" class="form-select material-select" required>
                                            <option value="">ជ្រើសរើសសម្ភារៈ</option>
                                            @foreach($materials as $mat)
                                                <option value="{{ $mat->id }}" {{ $material->id == $mat->id ? 'selected' : '' }}>{{ $mat->name }} ({{ $mat->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="me-2" style="width: 120px;">
                                        <input type="number" name="materials[{{ $index }}][quantity]" class="form-control" placeholder="ចំនួន" min="1" value="{{ $material->pivot->quantity }}" required>
                                    </div>
                                    <button type="button" class="btn btn-danger remove-material" {{ count($order->materials) > 1 ? '' : 'style="display: none;"' }}>លុប</button>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-material" class="btn btn-secondary btn-sm">បន្ថែមសម្ភារៈ</button>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">កំណត់ចំណាំ</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ $order->notes }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> កែប្រែ
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
            <select name="materials[${rowCount}][id]" class="form-select material-select" required>
                <option value="">ជ្រើសរើសសម្ភារៈ</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }} ({{ $material->code }})</option>
                @endforeach
            </select>
        </div>
        <div class="me-2" style="width: 120px;">
            <input type="number" name="materials[${rowCount}][quantity]" class="form-control" placeholder="ចំនួន" min="1" required>
        </div>
        <button type="button" class="btn btn-danger remove-material">លុប</button>
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