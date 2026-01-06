@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-cash-coin text-primary me-2"></i>តារាងចំណាយទិញចូល / Purchase Costs</h1>
        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>កត់ត្រាការទិញថ្មី
        </a>
    </div>

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
                            <th>កាលបរិច្ឆេទ</th>
                            <th>សម្ភារៈ</th>
                            <th>បរិមាណ</th>
                            <th>តម្លៃដើម (ឯកតា)</th>
                            <th>តម្លៃសរុប</th>
                            <th>អ្នកផ្គត់ផ្គង់</th>
                            <th>កំណត់ចំណាំ</th>
                            <th class="text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($purchases as $purchase)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}</td>
                                <td>
                                    <div class="fw-bold">{{ $purchase->material->name }}</div>
                                    <small class="text-muted">{{ $purchase->material->code }}</small>
                                </td>
                                <td><span class="badge bg-info text-dark">{{ $purchase->quantity }}</span></td>
                                <td>${{ number_format($purchase->unit_cost, 2) }}</td>
                                <td class="fw-bold text-success">${{ number_format($purchase->total_cost, 2) }}</td>
                                <td>{{ $purchase->supplier ?? '-' }}</td>
                                <td>{{ $purchase->notes ?? '-' }}</td>
                                <td class="text-end">
                                    <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">មិនទាន់មានទិន្នន័យទិញចូលទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
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