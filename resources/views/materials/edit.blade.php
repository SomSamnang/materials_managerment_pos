@extends('layouts.app')

@section('content')
<div class="container my-5">

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

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i>{{ __('Edit Material') }}</h4>
            <a href="{{ route('materials.index') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>

        <div class="card-body">
            @if($material->stock < $material->min_stock)
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>{{ __('Low Stock') }}!</strong> {{ __('Current stock (:current) is lower than minimum stock (:min).', ['current' => $material->stock, 'min' => $material->min_stock]) }}
                    </div>
                </div>
            @endif

            <form action="{{ route('materials.update', $material->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    {{-- Code --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Code') }}</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $material->code) }}" required readonly>
                    </div>

                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Name') }}</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $material->name) }}" required>
                    </div>

                    {{-- Stock --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Stock') }}</label>
                        <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock', $material->stock) }}" min="0" required>
                    </div>

                    {{-- Minimum Stock --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Min Stock Quantity') }}</label>
                        <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', $material->min_stock) }}" min="0" required>
                    </div>

                    {{-- Price --}}
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Unit Price') }} ($)</label>
                        <input type="number" id="price" step="0.01" name="price" class="form-control" value="{{ old('price', $material->price) }}" required>
                    </div>

                    {{-- Image --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Image') }}</label>
                        <input type="file" name="image" class="form-control">
                        @if($material->image)
                            <img src="{{ asset('storage/'.$material->image) }}" width="100" class="mt-2 rounded shadow-sm">
                        @endif
                    </div>

                    {{-- Description --}}
                    <div class="col-md-6">
                        <label class="form-label">{{ __('Description') }}</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $material->description) }}</textarea>
                    </div>

                    {{-- Total Price Display --}}
                    <div class="col-md-12 mt-3">
                        <div class="alert alert-info d-flex justify-content-between align-items-center">
                            <strong>{{ __('Total Price') }}:</strong>
                            <div>
                                <span id="totalUSD">${{ number_format($material->stock * $material->price, 2) }}</span> |
                                <span id="totalKHR">{{ number_format($material->stock * $material->price * 4100) }} ៛</span>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Submit --}}
                <div class="mt-3">
                    <button type="submit" class="btn btn-success"><i class="bi bi-save2 me-1"></i> {{ __('Save Changes') }}</button>
                    <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#stockModal" data-type="in">
                        <i class="bi bi-plus-circle me-1"></i> {{ __('Add Stock') }}
                    </button>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#stockModal" data-type="out">
                        <i class="bi bi-dash-circle me-1"></i> {{ __('Remove Stock') }}
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>

{{-- Stock Adjustment Modal --}}
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('materials.stock.adjust', $material->id) }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="stockModalLabel">{{ __('Adjust Stock') }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="type" id="stock-type">
            <div class="mb-3">
                <label for="quantity" class="form-label">{{ __('Quantity') }}</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>
            <div class="mb-3">
                <label for="notes" class="form-label">{{ __('Notes (Optional)') }}</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
          <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    // Live total price calculation
    const stockInput = document.getElementById('stock');
    const priceInput = document.getElementById('price');
    const totalUSD = document.getElementById('totalUSD');
    const totalKHR = document.getElementById('totalKHR');
    const exchangeRate = 4100;

    function updateTotal() {
        const stock = parseFloat(stockInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalUSD.textContent = `$${(stock * price).toFixed(2)}`;
        totalKHR.textContent = `${Math.round(stock * price * exchangeRate)} ៛`;
    }

    stockInput.addEventListener('input', updateTotal);
    priceInput.addEventListener('input', updateTotal);

    // Stock Modal
    const stockModal = document.getElementById('stockModal');
    if (stockModal) {
        stockModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const type = button.getAttribute('data-type');
            
            const modalTitle = stockModal.querySelector('.modal-title');
            const typeInput = stockModal.querySelector('#stock-type');
            const submitButton = stockModal.querySelector('form button[type=submit]');

            if (type === 'in') {
                modalTitle.textContent = '{{ __('Add Stock') }}';
                submitButton.className = 'btn btn-primary';
                submitButton.innerHTML = '<i class="bi bi-plus-circle me-1"></i> {{ __('Add Stock') }}';
            } else {
                modalTitle.textContent = '{{ __('Remove Stock') }}';
                submitButton.className = 'btn btn-danger';
                submitButton.innerHTML = '<i class="bi bi-dash-circle me-1"></i> {{ __('Remove Stock') }}';
            }

            typeInput.value = type;
            stockModal.querySelector('#quantity').value = '';
            stockModal.querySelector('#notes').value = '';
        });
    }
</script>

<style>
    .card {
        border-radius: 15px;
    }
    .card-header {
        font-size: 1.1rem;
    }
    textarea.form-control {
        resize: none;
    }
</style>
@endsection
