@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-receipt"></i> {{ $pageTitle }} - {{ $invoice->invoice_number }}</h4>
                    <div>
                        @if($invoice->status !== 'accepted')
                            <form action="{{ route('invoices.accept', $invoice) }}" method="POST" class="d-inline me-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('តើអ្នកចង់ទទួលយកវិក្កយបត្រនេះទេ?')">
                                    <i class="bi bi-check-circle"></i> ទទួលយក
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="btn btn-light btn-sm me-2">
                            <i class="bi bi-printer"></i> បោះពុម្ព
                        </a>
                        <a href="{{ route('invoices.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>លេខវិក្កយបត្រ:</strong> {{ $invoice->invoice_number }}
                        </div>
                        <div class="col-md-6">
                            <strong>អ្នកបញ្ជាទិញ:</strong> {{ $invoice->order->user->name }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>តម្លៃសរុប ($):</strong> ${{ number_format($invoice->total_amount_usd, 2) }}
                        </div>
                        <div class="col-md-6">
                            <strong>តម្លៃសរុប (៛):</strong> {{ number_format($invoice->total_amount_khr) }} ៛
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>ស្ថានភាព:</strong>
                            @if($invoice->status == 'unpaid')
                                <span class="badge bg-warning">មិនទាន់បង់</span>
                            @elseif($invoice->status == 'paid')
                                <span class="badge bg-success">បានបង់</span>                            @elseif($invoice->status == 'accepted')
                                <span class="badge bg-info">បានទទួលយក</span>                            @else
                                <span class="badge bg-danger">ហួសកំណត់</span>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>ថ្ងៃចេញវិក្កយបត្រ:</strong> {{ $invoice->issued_date->format('d/m/Y') }}
                        </div>
                        <div class="col-md-4">
                            <strong>ថ្ងៃផុតកំណត់:</strong> {{ $invoice->due_date ? $invoice->due_date->format('d/m/Y') : '-' }}
                        </div>
                    </div>

                    <h5>ព័ត៌មានអ្នកបញ្ជាទិញ</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>សម្ភារៈ</th>
                                    <th>ចំនួន</th>
                                    <th>តម្លៃឯកតា ($)</th>
                                    <th>តម្លៃសរុប ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->order->materials as $material)
                                <tr>
                                    <td>{{ $material->name }} ({{ $material->code }})</td>
                                    <td>{{ $material->pivot->quantity }}</td>
                                    <td>${{ number_format($material->pivot->unit_price_usd, 2) }}</td>
                                    <td>${{ number_format($material->pivot->quantity * $material->pivot->unit_price_usd, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection