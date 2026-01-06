@extends('layouts.app')

@section('content')
<div class="container my-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $pageTitle }}</h2>
        <a href="{{ route('invoices.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle"></i> បង្កើតវិក្កយបត្រ
        </a>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('invoices.index') }}" class="row g-2 mb-3">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control"
                   placeholder="ស្វែងរកវិក្កយបត្រ..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> ស្វែងរក
            </button>
        </div>
    </form>

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 text-center text-nowrap">
                    <thead class="table-light text-center">
                        <tr>
                            <th>ល.រ</th>
                            <th>លេខវិក្កយបត្រ</th>
                            <th>អ្នកបញ្ជាទិញ</th>
                            <th>តម្លៃសរុប ($)</th>
                            <th>ស្ថានភាព</th>
                            <th>ថ្ងៃចេញ</th>
                            <th>ថ្ងៃផុតកំណត់</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->order->user->name }}</td>
                            <td>${{ number_format($invoice->total_amount_usd, 2) }}</td>
                            <td>
                                @if($invoice->status == 'unpaid')
                                    <span class="badge bg-warning">មិនទាន់បង់</span>
                                @elseif($invoice->status == 'paid')
                                    <span class="badge bg-success">បានបង់</span>                                @elseif($invoice->status == 'accepted')
                                    <span class="badge bg-info">បានទទួលយក</span>                                @else
                                    <span class="badge bg-danger">ហួសកំណត់</span>
                                @endif
                            </td>
                            <td>{{ $invoice->issued_date->format('d/m/Y') }}</td>
                            <td>{{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info" title="មើល">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="btn btn-sm btn-secondary" title="បោះពុម្ព">
                                    <i class="bi bi-printer"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-sm btn-warning" title="កែប្រែ">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="លុប" onclick="return confirm('តើអ្នកប្រាកដទេ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $invoices->links() }}
    </div>

</div>
@endsection