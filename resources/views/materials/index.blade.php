@extends('layouts.app')

@section('content')
<div class="container my-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('materials.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle"></i> {{ __('Create Material') }}
        </a>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('materials.index') }}" class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>{{ __('All Status') }}</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="search" class="form-control" 
                   placeholder="{{ __('Search Materials...') }}" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> {{ __('Search') }}
            </button>
        </div>
    </form>

    {{-- Low stock alert --}}
    @if($lowStockCount > 0)
        <div class="alert alert-danger shadow-sm d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ __('There are :count low stock materials! Please review.', ['count' => $lowStockCount]) }}
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex gap-2 align-items-center">
                <select id="bulkStatus" class="form-select form-select-sm w-auto">
                    <option value="">{{ __('Select Action...') }}</option>
                    <option value="active">{{ __('Set as Active') }}</option>
                    <option value="inactive">{{ __('Set as Inactive') }}</option>
                </select>
                <button type="button" class="btn btn-sm btn-secondary" id="btnBulkAction">
                    {{ __('Apply') }}
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 text-center text-nowrap">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Image') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>
                                <a href="{{ route('materials.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc'])) }}" class="text-decoration-none text-dark">{{ __('Status') }} <i class="bi bi-arrow-down-up small text-muted"></i></a>
                            </th>
                            <th>{{ __('Stock') }}</th>
                            <th>{{ __('Min Stock') }}</th>
                            <th>{{ __('Unit Price') }}</th>
                            <th>{{ __('Total Price ($)') }}</th>
                            <th>{{ __('Total Price (៛)') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Created Date') }}</th>
                            <th>{{ __('Updated Date') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalUSD = 0;
                            $totalKHR = 0;
                        @endphp

                        @foreach($materials as $m)
                            @php
                                $rowUSD = $m->stock * $m->price;
                                $rowKHR = $rowUSD * 4100;
                                $totalUSD += $rowUSD;
                                $totalKHR += $rowKHR;
                            @endphp
                            <tr class="@if($m->status == 'inactive') table-secondary text-muted @elseif($m->stock < $m->min_stock) table-danger fw-bold @endif">
                                <td><input type="checkbox" name="ids[]" value="{{ $m->id }}" class="form-check-input material-checkbox"></td>
                                <td>{{ $m->id }}</td>
                                <td>
                                    @if($m->image)
                                        <img src="{{ asset('storage/'.$m->image) }}" class="rounded" width="50" height="50">
                                    @else
                                        <span class="text-muted">{{ __('None') }}</span>
                                    @endif
                                </td>
                                <td>{{ $m->code }}</td>
                                <td class="text-start">{{ $m->name }}</td>
                                <td>
                                    @if($m->status == 'active')
                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $m->stock }}
                                    @if($m->stock < $m->min_stock)
                                        <span class="badge bg-danger ms-1">{{ __('Low') }}</span>
                                    @endif
                                </td>
                                <td>{{ $m->min_stock }}</td>
                                <td>${{ number_format($m->price,2) }}</td>
                                <td>${{ number_format($rowUSD,2) }}</td>
                                <td>{{ number_format($rowKHR) }} ៛</td>
                                <td class="text-start text-nowrap">{{ $m->description }}</td>
                                <td>{{ $m->created_at->setTimezone('Asia/Phnom_Penh')->format('d-m-Y | g:i A') }}</td>
                                <td>{{ $m->updated_at->setTimezone('Asia/Phnom_Penh')->format('d-m-Y | g:i A') }}</td>
                                <td>
                                    <a href="{{ route('materials.show', $m->id) }}" class="btn btn-info btn-sm mb-1">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('materials.edit', $m->id) }}" class="btn btn-warning btn-sm mb-1">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                    <form method="POST" action="{{ route('materials.destroy', $m->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('{{ __('Are you sure you want to delete?') }}')">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="9" class="text-end">{{ __('Total Price (This Page)') }}</td>
                            <td class="text-primary">${{ number_format($totalUSD,2) }}</td>
                            <td class="text-success">{{ number_format($totalKHR) }} ៛</td>
                            <td colspan="4"></td>
                        </tr>
                        @if(isset($grandTotalUSD))
                            <tr>
                                <td colspan="9" class="text-end">{{ __('Grand Total Material Price') }}</td>
                                <td class="text-primary">${{ number_format($grandTotalUSD,2) }}</td>
                                <td class="text-success">{{ number_format($grandTotalKHR) }} ៛</td>
                                <td colspan="4"></td>
                            </tr>
                        @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $materials->links() }}
    </div>

    {{-- Hidden Form for Bulk Action --}}
    <form id="bulkActionForm" action="{{ route('materials.bulk_status') }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="status" id="hiddenStatus">
        <div id="hiddenIds"></div>
    </form>

    {{-- Confirmation Modal --}}
    <div class="modal fade" id="bulkActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Confirm Action') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! __('Are you sure you want to change the status of :count selected materials to :status?', ['count' => '<span id="selectedCount" class="fw-bold">0</span>', 'status' => '<span id="selectedStatus" class="fw-bold"></span>']) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="confirmBulkAction">{{ __('Confirm') }}</button>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.table-responsive::-webkit-scrollbar {
    height: 8px;
}
.table-responsive::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.25);
    border-radius: 4px;
}
.table-responsive::-webkit-scrollbar-track {
    background-color: rgba(0,0,0,0.05);
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}
.card {
    border-radius: 15px;
}
.alert-danger i {
    font-size: 1.2rem;
}
.badge.bg-danger {
    font-size: 0.75rem;
}
.text-nowrap td,
.text-nowrap th {
    white-space: nowrap; /* Force one line */
    overflow: hidden;
    text-overflow: ellipsis; /* Add "..." if text is too long */
    max-width: 200px;
}
.table-secondary img {
    opacity: 0.6;
    filter: grayscale(100%);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.material-checkbox');
    const btnBulkAction = document.getElementById('btnBulkAction');
    const bulkStatus = document.getElementById('bulkStatus');
    const bulkActionModal = new bootstrap.Modal(document.getElementById('bulkActionModal'));
    const confirmBtn = document.getElementById('confirmBulkAction');
    const bulkForm = document.getElementById('bulkActionForm');

    // Select All Logic
    selectAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Open Modal Logic
    btnBulkAction.addEventListener('click', function() {
        const selectedIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        const status = bulkStatus.value;

        if (selectedIds.length === 0) {
            alert('{{ __('Please select at least one material!') }}');
            return;
        }
        if (!status) {
            alert('{{ __('Please select an action!') }}');
            return;
        }

        document.getElementById('selectedCount').textContent = selectedIds.length;
        document.getElementById('selectedStatus').textContent = status === 'active' ? '{{ __('Active') }}' : '{{ __('Inactive') }}';
        
        bulkActionModal.show();
    });

    // Confirm Logic
    confirmBtn.addEventListener('click', function() {
        const selectedIds = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);
        document.getElementById('hiddenStatus').value = bulkStatus.value;
        const hiddenIdsContainer = document.getElementById('hiddenIds');
        hiddenIdsContainer.innerHTML = '';
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            hiddenIdsContainer.appendChild(input);
        });
        bulkForm.submit();
    });
});
</script>
@endsection
