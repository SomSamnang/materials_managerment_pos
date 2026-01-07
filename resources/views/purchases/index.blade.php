@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-cash-coin text-primary me-2"></i>តារាងចំណាយទិញចូល / Purchase Costs</h1>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>កត់ត្រាការទិញថ្មី
        </a>
    </div>

    <form method="GET" action="{{ route('purchases.index') }}" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" 
                   placeholder="ស្វែងរកសម្ភារៈ ឬអ្នកផ្គត់ផ្គង់..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text">ពី</span>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
        </div>
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text">ដល់</span>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search me-2"></i>ស្វែងរក
            </button>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>សម្ភារៈ</th>
                            <th>កាលបរិច្ឆេទ</th>
                            <th>បរិមាណ</th>
                            <th>តម្លៃដើម (ឯកតា)</th>
                            <th>តម្លៃដើម (រៀល)</th>
                            <th>តម្លៃសរុប</th>
                            <th>តម្លៃសរុប (រៀល)</th>
                            <th>អ្នកផ្គត់ផ្គង់</th>
                            <th>កំណត់ចំណាំ</th>
                            <th class="text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $purchase->material->name }}</div>
                                    <small class="text-muted">{{ $purchase->material->code }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-info text-dark">{{ $purchase->quantity }}</span></td>
                                <td>${{ number_format($purchase->unit_cost, 2) }}</td>
                                <td>{{ number_format($purchase->unit_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛</td>
                                <td class="fw-bold text-success">${{ number_format($purchase->total_cost, 2) }}</td>
                                <td class="fw-bold text-success">{{ number_format($purchase->total_cost * \App\Models\Setting::getExchangeRate(), 0) }} ៛</td>
                                <td>{{ $purchase->supplier ?? '-' }}</td>
                                <td>{{ $purchase->notes ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('purchases.show', $purchase) }}" class="btn btn-sm btn-info" title="លម្អិត">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('purchases.edit', $purchase) }}" class="btn btn-sm btn-warning" title="កែប្រែ">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">មិនទាន់មានទិន្នន័យទិញចូលទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($purchases->count() > 0)
                    <tfoot class="bg-light fw-bold">
                        <tr>
                            <td colspan="5" class="text-end">សរុប (Total):</td>
                            <td class="text-success">${{ number_format($totalFilteredCost, 2) }}</td>
                            <td class="text-success">{{ number_format($totalFilteredCost * \App\Models\Setting::getExchangeRate(), 0) }} ៛</td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
        @if($purchases->hasPages())
            <div class="card-footer bg-white">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>
</div>
@endsection