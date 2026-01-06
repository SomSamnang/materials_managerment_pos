@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="bi bi-plus-circle"></i> {{ $pageTitle }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="order_id" class="form-label">ជ្រើសរើសអ្នកបញ្ជាទិញ</label>
                            <select name="order_id" id="order_id" class="form-select" required>
                                <option value="">ជ្រើសរើសអ្នកបញ្ជាទិញ</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}">អ្នកបញ្ជាទិញ: {{ $order->user->name }} - តម្លៃ: ${{ $order->total_amount_usd }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> បង្កើតវិក្កយបត្រ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection