@extends('layouts.app')

@section('content')
<div class="container-fluid">
    {{-- Hero Section --}}
    <div class="card text-white mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-3">ស្វាគមន៍មកកាន់ប្រព័ន្ធ</h1>
                    <p class="lead mb-0">ប្រព័ន្ធគ្រប់គ្រងសម្ភារៈដ៏ទំនើប និងមានប្រសិទ្ធភាពខ្ពស់</p>
                </div>
                <div class="col-lg-4 text-center d-none d-lg-block">
                    <i class="fas fa-cogs" style="font-size: 6rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card mb-4">
        <div class="card-header fw-bold"><i class="fas fa-bolt text-warning me-2"></i>សកម្មភាពរហ័ស</div>
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('materials.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>បង្កើតសម្ភារៈ
                </a>
                <a href="{{ route('orders.create') }}" class="btn btn-success">
                    <i class="fas fa-shopping-cart me-2"></i>បញ្ជាទិញថ្មី
                </a>
                <a href="{{ route('users.create') }}" class="btn btn-info">
                    <i class="fas fa-user-plus me-2"></i>បង្កើតអ្នកប្រើប្រាស់
                </a>
                <a href="{{ route('orders.quick_create') }}" class="btn btn-warning">
                    <i class="bi bi-rocket-takeoff-fill me-2"></i>បញ្ជាទិញលឿន
                </a>
                <a href="{{ route('materials.stock.create_bulk') }}" class="btn btn-dark">
                    <i class="bi bi-box-arrow-in-down me-2"></i> បញ្ចូលស្តុក
                </a>
                <a href="{{ route('purchases.create') }}" class="btn btn-secondary">
                    <i class="bi bi-cash-coin me-2"></i> កត់ត្រាការទិញ
                </a>
                <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                    <i class="bi bi-truck me-2"></i> បង្កើតអ្នកផ្គត់ផ្គង់
                </a>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row g-4 mb-4">
        {{-- Materials Section --}}
        <div class="col-12">
            <h4 class="mb-3"><i class="fas fa-boxes text-primary me-2"></i>សម្ភារៈ</h4>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-box fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalMaterials }}</div>
                        <div class="text-muted">សម្ភារៈសរុប</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white p-3 rounded-3 me-3">
                        <i class="fas fa-exclamation-triangle fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $lowStockCount }}</div>
                        <div class="text-muted">សម្ភារៈស្តុកទាប</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6">
             <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="fas fa-warehouse fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalStock }}</div>
                        <div class="text-muted">សរុបចំនួនស្តុក</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white p-3 rounded-3 me-3">
                        <i class="fas fa-dollar-sign fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">${{ number_format($totalPriceUSD, 0) }}</div>
                        <div class="text-muted">តម្លៃសរុបសម្ភារៈ</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary text-white p-3 rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <span class="fs-4 fw-bold">៛</span>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ number_format($totalPriceKHR, 0) }} ៛</div>
                        <div class="text-muted">តម្លៃសរុប (៛)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users Section --}}
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="fas fa-users text-success me-2"></i>អ្នកប្រើប្រាស់</h4>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-users fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalUsers }}</div>
                        <div class="text-muted">អ្នកប្រើប្រាស់សរុប</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white p-3 rounded-3 me-3">
                        <i class="fas fa-user-shield fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalAdmins }}</div>
                        <div class="text-muted">អ្នកគ្រប់គ្រង (Admin)</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="fas fa-user fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalRegularUsers }}</div>
                        <div class="text-muted">អ្នកប្រើប្រាស់ (User)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Orders Section --}}
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="fas fa-shopping-cart text-info me-2"></i>ការបញ្ជាទិញ</h4>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-clipboard-list fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalOrders }}</div>
                        <div class="text-muted">សរុបបញ្ជាទិញ</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white p-3 rounded-3 me-3">
                        <i class="fas fa-clock fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $pendingOrders }}</div>
                        <div class="text-muted">បញ្ជាទិញរងចាំ</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white p-3 rounded-3 me-3">
                        <i class="fas fa-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $completedOrders }}</div>
                        <div class="text-muted">បញ្ជាទិញបញ្ចប់</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white p-3 rounded-3 me-3">
                        <i class="fas fa-times-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $cancelledOrders }}</div>
                        <div class="text-muted">បញ្ជាទិញបោះបង់</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Purchases Section --}}
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="fas fa-file-invoice-dollar text-secondary me-2"></i>ការទិញចូល</h4>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-secondary text-white p-3 rounded-3 me-3">
                        <i class="fas fa-receipt fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalPurchases ?? 0 }}</div>
                        <div class="text-muted">ចំនួនប្រតិបត្តិការទិញ</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-dark text-white p-3 rounded-3 me-3">
                        <i class="fas fa-coins fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">${{ number_format($totalPurchaseCost ?? 0, 2) }}</div>
                        <div class="text-muted">ចំណាយសរុប</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Suppliers Section --}}
        <div class="col-12 mt-4">
            <h4 class="mb-3"><i class="bi bi-truck text-primary me-2"></i>អ្នកផ្គត់ផ្គង់</h4>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white p-3 rounded-3 me-3">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <div>
                        <div class="fs-2 fw-bold">{{ $totalSuppliers ?? 0 }}</div>
                        <div class="text-muted">អ្នកផ្គត់ផ្គង់សរុប</div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Recent Materials Table --}}
    <div class="card">
        <div class="card-header fw-bold d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-list text-primary me-2"></i>សម្ភារៈថ្មីៗ</h4>
            <form action="{{ route('dashboard') }}" method="GET" class="d-flex" style="max-width: 300px;">
                <input type="text" name="search" class="form-control" placeholder="ស្វែងរកសម្ភារៈ..." value="{{ $search ?? '' }}">
                <button type="submit" class="btn btn-primary ms-2" aria-label="Search">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>រូបភាព</th>
                            <th>កូដ</th>
                            <th>ឈ្មោះ</th>
                            <th>ចំនួនស្តុក</th>
                            <th>អប្បបរមា</th>
                            <th>តម្លៃឯកតា</th>
                            <th>តម្លៃសរុប</th>
                            <th>ថ្ងៃបង្កើត</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($materials->take(10) as $material)
                            <tr class="{{ $material->stock < $material->min_stock ? 'table-warning' : '' }}">
                                <td>{{ $material->id }}</td>
                                <td>
                                    @if($material->image)
                                        <img src="{{ asset('storage/'.$material->image) }}" width="50" class="rounded" alt="Material Image">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td><code>{{ $material->code }}</code></td>
                                <td class="fw-semibold">{{ $material->name }}</td>
                                <td>
                                    <span class="badge {{ $material->stock < $material->min_stock ? 'bg-danger' : 'bg-primary' }}">{{ $material->stock }}</span>
                                    @if($material->stock < $material->min_stock)
                                        <span class="ms-2 text-danger fw-bold">ស្តុកទាប!</span>
                                    @endif
                                </td>
                                <td>{{ $material->min_stock }}</td>
                                <td class="text-success fw-bold">${{ number_format($material->price, 2) }}</td>
                                <td class="text-primary fw-bold">${{ number_format($material->stock * $material->price, 2) }}</td>
                                <td class="text-muted">{{ $material->created_at->setTimezone('Asia/Phnom_Penh')->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    @if(!empty($search))
                                        <p class="mb-0">រកមិនឃើញសម្ភារៈសម្រាប់ពាក្យស្វែងរករបស់អ្នកទេ "{{ $search }}"។</p>
                                    @else
                                        <p class="mb-0">រកមិនឃើញសម្ភារៈថ្មីៗទេ។</p>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
