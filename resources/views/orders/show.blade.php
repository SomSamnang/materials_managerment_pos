@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4><i class="bi bi-eye"></i> {{ $pageTitle }}</h4>
                    <div>
                        @if($order->invoice)
                            <a href="{{ route('invoices.print', $order->invoice) }}" target="_blank" class="btn btn-light btn-sm me-2">
                                <i class="bi bi-printer"></i> បោះពុម្ពវិក្កយបត្រ
                            </a>
                        @endif
                        <a href="{{ route('orders.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
                        </a>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>អ្នកបញ្ជាទិញ:</strong> {{ $order->user->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>ស្ថានភាព:</strong>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">រងចាំ</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">បញ្ចប់</span>
                            @else
                                <span class="badge bg-danger">បោះបង់</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>តម្លៃសរុប ($):</strong> ${{ number_format($order->total_amount_usd, 2) }}
                        </div>
                        <div class="col-md-6">
                            <strong>តម្លៃសរុប (៛):</strong> {{ number_format($order->total_amount_khr) }} ៛
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ថ្ងៃបង្កើត:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                        </div>
                        <div class="col-md-6">
                            <strong>ថ្ងៃកែប្រែ:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    @if($order->notes)
                    <div class="mb-3">
                        <strong>កំណត់ចំណាំ:</strong>
                        <p>{{ $order->notes }}</p>
                    </div>
                    @endif

                    <h5>សម្ភារៈក្នុងអ្នកបញ្ជាទិញ</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ឈ្មោះ</th>
                                    <th>កូដ</th>
                                    <th>ចំនួន</th>
                                    <th>តម្លៃឯកតា ($)</th>
                                    <th>តម្លៃសរុប ($)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->materials as $material)
                                <tr>
                                    <td>{{ $material->name }}</td>
                                    <td>{{ $material->code }}</td>
                                    <td>{{ $material->pivot->quantity }}</td>
                                    <td>${{ number_format($material->pivot->unit_price_usd, 2) }}</td>
                                    <td>${{ number_format($material->pivot->quantity * $material->pivot->unit_price_usd, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($order->invoice)
                    <div class="mt-4">
                        <h5>ព័ត៌មានវិក្កយបត្រ</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>លេខវិក្កយបត្រ:</strong> {{ $order->invoice->invoice_number }}</p>
                                <p><strong>ថ្ងៃចេញវិក្កយបត្រ:</strong> {{ $order->invoice->issued_date->format('d/m/Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>ស្ថានភាព:</strong>
                                    @if($order->invoice->status == 'paid')
                                        <span class="badge bg-success">បានបង់</span>
                                    @elseif($order->invoice->status == 'unpaid')
                                        <span class="badge bg-warning">មិនទាន់បង់</span>
                                    @elseif($order->invoice->status == 'accepted')
                                        <span class="badge bg-info">បានទទួលយក</span>
                                    @else
                                        <span class="badge bg-danger">ហួសកំណត់</span>
                                    @endif
                                </p>
                                <p><strong>ថ្ងៃផុតកំណត់:</strong> {{ $order->invoice->due_date ? $order->invoice->due_date->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection