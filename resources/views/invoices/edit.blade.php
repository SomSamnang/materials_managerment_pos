@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header bg-warning text-white text-center">
                    <h4><i class="bi bi-pencil"></i> {{ $pageTitle }}</h4>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="status" class="form-label">ស្ថានភាព</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>មិនទាន់បង់</option>
                                <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>បានបង់</option>
                                <option value="accepted" {{ $invoice->status == 'accepted' ? 'selected' : '' }}>បានទទួលយក</option>
                                <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>ហួសកំណត់</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="due_date" class="form-label">ថ្ងៃផុតកំណត់</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}">
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> ត្រឡប់ក្រោយ
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle"></i> កែប្រែ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection