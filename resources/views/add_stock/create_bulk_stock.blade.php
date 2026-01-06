@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Page Header --}}
    <div class="mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-box-arrow-in-down-left text-primary me-2"></i>បញ្ចូលស្តុកច្រើនមុខ / Bulk Stock Entry</h1>
        <p class="text-muted">បញ្ចូលស្តុកសម្រាប់សម្ភារៈច្រើនមុខក្នុងពេលតែមួយ។</p>
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

    {{-- Bulk Stock Form --}}
    <form action="{{ route('materials.stock.store_bulk') }}" method="POST">
        @csrf
        <div class="row g-4">

            {{-- Materials Selection (Left Column) --}}
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header bg-light border-0 d-flex justify-content-between align-items-center p-3">
                        <h5 class="mb-0"><i class="bi bi-boxes text-primary me-2"></i>សម្ភារៈ / Materials</h5>
                        <div class="w-50">
                            <input type="text" id="material-search" class="form-control" placeholder="ស្វែងរកតាមឈ្មោះ ឬកូដ...">
                        </div>
                    </div>
                    <div class="card-body p-0" style="max-height: 65vh; overflow-y: auto;">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle text-center mb-0">
                                <thead class="table-light" style="position: sticky; top: 0; z-index: 1;">
                                    <tr>
                                        <th>#</th>
                                        <th class="text-start">ឈ្មោះ</th>
                                        <th class="text-nowrap">ស្តុកបច្ចុប្បន្ន</th>
                                        <th style="width: 150px;">បរិមាណត្រូវបញ្ចូល</th>
                                    </tr>
                                </thead>
                                <tbody id="materials-tbody">
                                    @foreach($materials as $material)
                                        <tr @if($material->stock < $material->min_stock) class="table-warning" @endif>
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
                                                    <span class="badge bg-danger ms-1">ស្តុកទាប</span>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="number" name="materials[{{ $material->id }}][quantity]" class="form-control form-control-sm text-center quantity-input" min="0" value="0">
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

            {{-- Details (Right Column) --}}
            <div class="col-lg-4">
                <div style="position: sticky; top: 80px;">
                    <div class="card">
                        <div class="card-header bg-light border-0 p-3">
                            <h5 class="mb-0"><i class="bi bi-journal-text text-primary me-2"></i>ព័ត៌មានបន្ថែម</h5>
                        </div>
                        <div class="card-body">
                            <div>
                                <label class="form-label fw-bold">កំណត់ចំណាំ</label>
                                <textarea name="notes" class="form-control" rows="4" placeholder="ឧទាហរណ៍: ការទិញចូលប្រចាំខែ...">{{ old('notes') }}</textarea>
                                <small class="form-text text-muted">កំណត់ចំណាំនេះសម្រាប់គោលបំណងត្រួតពិនិត្យ។</small>
                            </div>
                        </div>
                        <div class="card-footer border-0 p-3">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="bi bi-check-circle me-2"></i> បញ្ចូលស្តុក
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
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const materialSearch = document.getElementById('material-search');
    const materialsTbody = document.getElementById('materials-tbody');

    function highlightRows() {
        quantityInputs.forEach(input => {
            const row = input.closest('tr');
            const quantity = parseInt(input.value) || 0;
            
            if (quantity > 0) {
                row.classList.add('table-success');
            } else {
                row.classList.remove('table-success');
            }
        });
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
        input.addEventListener('input', highlightRows);
    });

    if (materialSearch) {
        materialSearch.addEventListener('keyup', filterMaterials);
    }
});
</script>
@endsection