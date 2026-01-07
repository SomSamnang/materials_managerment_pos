@extends('layouts.app')

@section('content')
<div class="container my-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>{{ $pageTitle }}</h2>
        <a href="{{ route('orders.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle"></i> បន្ថែមអ្នកបញ្ជាទិញ
        </a>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('orders.index') }}" class="row g-2 mb-3">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control"
                   placeholder="ស្វែងរកអ្នកបញ្ជាទិញ..." value="{{ request('search') }}">
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
                            <th>អ្នកបញ្ជាទិញ</th>
                            <th>ស្ថានភាព</th>
                            <th>តម្លៃសរុប ($)</th>
                            <th>តម្លៃសរុប (៛)</th>
                            <th>ថ្ងៃបង្កើត</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">រងចាំ</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">បញ្ចប់</span>
                                @else
                                    <span class="badge bg-danger">បោះបង់</span>
                                @endif
                            </td>
                            <td>${{ number_format($order->total_amount_usd, 2) }}</td>
                            <td>{{ number_format($order->total_amount_khr) }} ៛</td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($isAdmin || $order->user_id === auth()->id())
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-square"></i> 
                                    </a>
                                @endif
                                @if($isAdmin)
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('តើអ្នកប្រាកដទេ?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @endif
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
        {{ $orders->links() }}
    </div>

</div>
@endsection