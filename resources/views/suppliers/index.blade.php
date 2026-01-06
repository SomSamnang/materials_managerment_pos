@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold"><i class="bi bi-truck text-primary me-2"></i>បញ្ជីអ្នកផ្គត់ផ្គង់ / Supplier List</h1>
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>បង្កើតអ្នកផ្គត់ផ្គង់ថ្មី
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
                            <th>ID</th>
                            <th>ឈ្មោះក្រុមហ៊ុន</th>
                            <th>អ្នកទំនាក់ទំនង</th>
                            <th>លេខទូរស័ព្ទ</th>
                            <th>អ៊ីមែល</th>
                            <th>អាសយដ្ឋាន</th>
                            <th class="text-end">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->id }}</td>
                                <td class="fw-bold">{{ $supplier->name }}</td>
                                <td>{{ $supplier->contact_person ?? '-' }}</td>
                                <td>{{ $supplier->phone ?? '-' }}</td>
                                <td>{{ $supplier->email ?? '-' }}</td>
                                <td>{{ Str::limit($supplier->address, 30) ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('តើអ្នកពិតជាចង់លុបមែនទេ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">មិនទាន់មានទិន្នន័យអ្នកផ្គត់ផ្គង់ទេ។</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($suppliers->hasPages())
            <div class="card-footer bg-white">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>
</div>
@endsection