@extends('layouts.app')

@section('content')
<div class="container my-4">

    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">

        {{-- HEADER --}}
        <div class="card-header text-white d-flex justify-content-between align-items-center no-print p-4"
             style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h4 class="mb-0 fw-bold text-nowrap">
                <i class="bi bi-receipt me-2"></i> {{ $pageTitle }}
                
            </h4>
            <div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-light btn-sm me-2 shadow-sm fw-bold">
                    <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                </a>
                <button onclick="window.print()" class="btn btn-light btn-sm shadow-sm fw-bold text-primary">
                    <i class="bi bi-printer"></i> {{ __('Print') }}
                </button>
            </div>
           
        </div>

        <div class="card-body p-4">

            {{-- PRINT HEADER --}}
            <div class="d-none d-print-block text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Company Logo" style="max-height: 60px;" class="mb-3">
                <h2 class="fw-bold text-uppercase" style="color: #764ba2;">
                    {{ __('Material Management System') }}
                </h2>
                <h4 class="text-muted">{{ __('Invoice List') }}</h4>
                <p class="text-muted">{{ __('Date') }}: {{ date('d/m/Y') }}</p>
            </div>

            {{-- SEARCH + SHOW ALL --}}
            <form method="GET" action="{{ route('invoices.index') }}" class="row g-2 mb-4 no-print">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text"
                               name="search"
                               class="form-control"
                               placeholder="{{ __('Search invoices...') }}"
                               value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
                        <i class="bi bi-search"></i> {{ __('Search') }}
                    </button>
                </div>
 <div class="col-md-2">
                    <a href="{{ route('invoices.index') }}" class="btn btn-outline-danger w-100">
                        <i class="bi bi-x-circle"></i> {{ __('Reset') }}
                    </a>
                </div>
                <div class="col-md-3">
                
                    <a href="{{ route('invoices.index', array_merge(request()->query(), ['show_all' => 'true'])) }}" class="btn {{ request('show_all') ? 'btn-secondary' : 'btn-outline-secondary' }} w-100">
                        <i class="bi bi-list-ul"></i> {{ __('Show All') }}
                    </a>
                </div>

               
            </form>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle text-center text-nowrap mb-0">
                    <thead class="text-white"
                           style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <tr>
                            <th>ID</th>
                            <th>{{ __('Invoice Number') }}</th>
                            <th>{{ __('Buyer') }}</th>
                            <th>{{ __('Total ($)') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Issued') }}</th>
                            <th>{{ __('Due') }}</th>
                            <th class="no-print">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td class="fw-bold text-muted">{{ $invoice->id ?? '-' }}</td>
                            <td class="fw-bold text-primary">{{ $invoice->invoice_number ?? '-' }}</td>
                            <td>
                                {{-- SAFE ACCESS to nested relationships --}}
                                {{ optional(optional($invoice->order)->user)->name ?? '-' }}
                            </td>
                            <td>${{ number_format($invoice->total_amount_usd ?? 0, 2) }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2
                                    @if(($invoice->status ?? '')=='paid') bg-success
                                    @elseif(($invoice->status ?? '')=='accepted') bg-info text-dark
                                    @elseif(($invoice->status ?? '')=='overdue') bg-danger
                                    @else bg-warning text-dark @endif">
                                    {{ ucfirst($invoice->status ?? '-') }}
                                </span>
                            </td>
                            <td>{{ optional($invoice->issued_date)->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ optional($invoice->due_date)->format('d/m/Y') ?? '-' }}</td>
                            <td class="no-print">
                                <a href="{{ route('invoices.show', $invoice->id ?? 0) }}" class="btn btn-sm btn-outline-info rounded-circle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('invoices.edit', $invoice->id ?? 0) }}" class="btn btn-sm btn-outline-warning rounded-circle">
                                  <i class="bi bi-pencil-square"></i> 
                                </a>
                                <form action="{{ route('invoices.destroy', $invoice->id ?? 0) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-outline-danger rounded-circle">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-4 text-muted">
                                {{ __('No invoices found') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION (BACK / NEXT) --}}
            @if(method_exists($invoices, 'total'))
            <div class="d-flex justify-content-between align-items-center mt-4 no-print">
                <div class="text-muted">
                    {{ __('Showing') }}
                    {{ $invoices->firstItem() ?? 0 }} –
                    {{ $invoices->lastItem() ?? 0 }}
                    {{ __('of') }}
                    {{ $invoices->total() ?? 0 }}
                </div>

                <div class="btn-group">
                    @if ($invoices->onFirstPage())
                        <button class="btn btn-outline-secondary" disabled>
                            <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                        </button>
                    @else
                        <a href="{{ $invoices->previousPageUrl() }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> {{ __('Back') }}
                        </a>
                    @endif

                    @if ($invoices->hasMorePages())
                        <a href="{{ $invoices->nextPageUrl() }}" class="btn btn-outline-primary">
                            {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                        </a>
                    @else
                        <button class="btn btn-outline-secondary" disabled>
                            {{ __('Next') }} <i class="bi bi-arrow-right"></i>
                        </button>
                    @endif
                </div>
            </div>
            @else
            <div class="mt-4 no-print text-muted">
                {{ __('Total Records') }}: {{ $invoices->count() }}
            </div>
            @endif

            {{-- PRINT FOOTER --}}
            <div class="d-none d-print-block mt-4 text-end border-top pt-3">
                <small class="text-muted">
                    {{ __('Printed by') }} {{ auth()->user()->name ?? '-' }} – {{ now()->format('d/m/Y H:i') }}
                </small>
            </div>

        </div>
    </div>
</div>
@endsection
