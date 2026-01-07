@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-eye text-primary me-2"></i>ព័ត៌មានលម្អិតការទិញ / Purchase Details</h1>
        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>ត្រឡប់ក្រោយ
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">ព័ត៌មានសម្ភារៈ / Material Info</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">ឈ្មោះសម្ភារៈ:</th>
                            <td class="fw-bold">{{ $purchase->material->name }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">កូដសម្ភារៈ:</th>
                            <td>{{ $purchase->material->code }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">ព័ត៌មានការទិញ / Purchase Info</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">កាលបរិច្ឆេទ:</th>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">អ្នកផ្គត់ផ្គង់:</th>
                            <td>{{ $purchase->supplier ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th class="text-muted">បរិមាណ:</th>
                            <td><span class="badge bg-info text-dark fs-6">{{ $purchase->quantity }}</span></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">ព័ត៌មានតម្លៃ / Cost Info</h5>
                    <table class="table table-borderless">
                        <tr>
                            <th class="text-muted" style="width: 150px;">តម្លៃដើម (ឯកតា):</th>
                            <td>${{ number_format($purchase->unit_cost, 2) }} <span class="text-muted">({{ number_format($purchase->unit_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛)</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">តម្លៃសរុប:</th>
                            <td class="fw-bold text-success fs-5">${{ number_format($purchase->total_cost, 2) }} <span class="fs-6 text-muted">({{ number_format($purchase->total_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛)</span></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6 mb-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-3">កំណត់ចំណាំ / Notes</h5>
                    <div class="p-3 bg-light rounded">
                        {{ $purchase->notes ?? 'គ្មានកំណត់ចំណាំ' }}
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> កែប្រែ
                </a>
                <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> លុប
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection