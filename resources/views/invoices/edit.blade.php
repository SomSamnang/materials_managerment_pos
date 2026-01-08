@extends('layouts.app')

@section('content')
<style>
    .edit-invoice-card {
        border-radius: 18px;
    }

    .edit-invoice-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        color: #fff;
        text-align: center;
    }

    /* FORCE HEADER TEXT TO ONE LINE */
    .edit-invoice-header h4 {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .form-section {
        background: #f8f9fc;
        border-radius: 14px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .form-select,
    .form-control {
        border-radius: 10px;
    }

    .action-bar {
        border-top: 1px solid #e9ecef;
        padding-top: 1.25rem;
        margin-top: 1.25rem;
        display: flex;
        justify-content: space-between;
    }
</style>

<div class="container my-4">
    <div class="row justify-content-center">

        <!-- CHANGE WIDTH HERE -->
        <div class="col-lg-8 col-md-10 col-sm-12">

            <div class="card shadow-lg border-0 edit-invoice-card">

                {{-- HEADER --}}
                <div class="edit-invoice-header">
                    <h4 class="fw-bold mb-0">
                        <i class="bi bi-pencil-square me-2"></i>
                        {{ $pageTitle }}
                    </h4>
                </div>

                {{-- BODY --}}
                <div class="card-body p-4">

                    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- STATUS --}}
                        <div class="form-section">
                            <label class="form-label">{{ __('Invoice Status') }}</label>
                            <select name="status" class="form-select" required>
                                <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="accepted" {{ $invoice->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>

                        {{-- DUE DATE --}}
                        <div class="form-section">
                            <label class="form-label">{{ __('Due Date') }}</label>
                            <input
                                type="date"
                                name="due_date"
                                class="form-control"
                                value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}"
                            >
                        </div>

                        {{-- ACTIONS --}}
                        <div class="action-bar">
                            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                            </a>

                            <button type="submit" class="btn btn-success px-4">
                                <i class="bi bi-save"></i> {{ __('Update Invoice') }}
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
