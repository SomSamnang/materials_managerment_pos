@extends('layouts.app')

@section('content')
<div class="container my-4">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('materials.create') }}" class="btn btn-success shadow-sm">
            <i class="bi bi-plus-circle"></i> បន្ថែមសម្ភារៈ
        </a>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('materials.index') }}" class="row g-2 mb-3">
        <div class="col-md-8">
            <input type="text" name="search" class="form-control" 
                   placeholder="ស្វែងរកសម្ភារៈ..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">
                <i class="bi bi-search"></i> ស្វែងរក
            </button>
        </div>
    </form>

    {{-- Low stock alert --}}
    @if($lowStockCount > 0)
        <div class="alert alert-danger shadow-sm d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            មាន {{ $lowStockCount }} សម្ភារៈស្តុកទាប! សូមពិនិត្យឡើងវិញ។
        </div>
    @endif

    {{-- Table --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 text-center text-nowrap">
                    <thead class="table-light text-center">
                        <tr>
                            <th>ល.រ</th>
                            <th>រូបភាព</th>
                            <th>កូដ</th>
                            <th>ឈ្មោះ</th>
                            <th>ចំនួនស្តុក</th>
                            <th>អប្បបរមា</th>
                            <th>តម្លៃឯកតា</th>
                            <th>តម្លៃសរុប ($)</th>
                            <th>តម្លៃសរុប (៛)</th>
                            <th>ពិពណ៌នា</th>
                            <th>ថ្ងៃបង្កើត</th>
                            <th>ថ្ងៃកែប្រែ</th>
                            <th>សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalUSD = 0;
                            $totalKHR = 0;
                        @endphp

                        @foreach($materials as $m)
                            @php
                                $rowUSD = $m->stock * $m->price;
                                $rowKHR = $rowUSD * 4100;
                                $totalUSD += $rowUSD;
                                $totalKHR += $rowKHR;
                            @endphp
                            <tr @if($m->stock < $m->min_stock) class="table-danger fw-bold" @endif>
                                <td>{{ $m->id }}</td>
                                <td>
                                    @if($m->image)
                                        <img src="{{ asset('storage/'.$m->image) }}" class="rounded" width="50" height="50">
                                    @else
                                        <span class="text-muted">គ្មាន</span>
                                    @endif
                                </td>
                                <td>{{ $m->code }}</td>
                                <td class="text-start">{{ $m->name }}</td>
                                <td>
                                    {{ $m->stock }}
                                    @if($m->stock < $m->min_stock)
                                        <span class="badge bg-danger ms-1">ទាប</span>
                                    @endif
                                </td>
                                <td>{{ $m->min_stock }}</td>
                                <td>${{ number_format($m->price,2) }}</td>
                                <td>${{ number_format($rowUSD,2) }}</td>
                                <td>{{ number_format($rowKHR) }} ៛</td>
                                <td class="text-start text-nowrap">{{ $m->description }}</td>
                                <td>{{ $m->created_at->setTimezone('Asia/Phnom_Penh')->format('d-m-Y | g:i A') }}</td>
                                <td>{{ $m->updated_at->setTimezone('Asia/Phnom_Penh')->format('d-m-Y | g:i A') }}</td>
                                <td>
                                    <a href="{{ route('materials.edit', $m->id) }}" class="btn btn-warning btn-sm mb-1">
                                        <i class="bi bi-pencil-square"></i> កែប្រែ
                                    </a>
                                    <form method="POST" action="{{ route('materials.destroy', $m->id) }}" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?')">
                                            <i class="bi bi-trash3"></i> លុប
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light fw-bold">
                        <tr>
                            <td colspan="7" class="text-end">តម្លៃសរុបសម្ភារៈទាំងអស់</td>
                            <td class="text-primary">${{ number_format($totalUSD,2) }}</td>
                            <td class="text-success">{{ number_format($totalKHR) }} ៛</td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
.table-responsive::-webkit-scrollbar {
    height: 8px;
}
.table-responsive::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.25);
    border-radius: 4px;
}
.table-responsive::-webkit-scrollbar-track {
    background-color: rgba(0,0,0,0.05);
}
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}
.card {
    border-radius: 15px;
}
.alert-danger i {
    font-size: 1.2rem;
}
.badge.bg-danger {
    font-size: 0.75rem;
}
.text-nowrap td,
.text-nowrap th {
    white-space: nowrap; /* Force one line */
    overflow: hidden;
    text-overflow: ellipsis; /* Add "..." if text is too long */
    max-width: 200px;
}
</style>
@endsection
