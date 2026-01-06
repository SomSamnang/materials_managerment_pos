<!DOCTYPE html>
<html lang="km">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $pageTitle ?? 'ប្រព័ន្ធគ្រប់គ្រងសម្ភារៈ' }}</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

<style>
@import url('https://fonts.googleapis.com/css2?family=Battambang:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap');

:root {
    --primary-color: #4361ee;
    --secondary-color: #3f37c9;
    --sidebar-bg: #0f172a;
    --sidebar-width: 280px;
    --text-muted: #94a3b8;
    --bg-body: #f1f5f9;
}

body {
    font-family: 'Poppins', 'Battambang', sans-serif;
    background-color: var(--bg-body);
    overflow-x: hidden;
    margin: 0;
}

/* Sidebar */
.sidebar {
    width: var(--sidebar-width);
    background: var(--sidebar-bg);
    color: white;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    flex-direction: column;
    z-index: 1000;
    box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.sidebar-brand {
    padding: 2rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.4rem;
    font-weight: 700;
    color: white;
    text-decoration: none;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.sidebar-brand i { color: var(--primary-color); }

.nav-pills { padding: 1.5rem 1rem; gap: 0.5rem; }

.nav-link {
    color: var(--text-muted);
    font-weight: 500;
    padding: 12px 20px;
    border-radius: 12px;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-link:hover {
    color: white;
    background: rgba(255,255,255,0.05);
    transform: translateX(5px);
}

.nav-link.active {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    box-shadow: 0 4px 15px rgba(67, 97, 238, 0.4);
}

.sidebar-footer {
    margin-top: auto;
    padding: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.05);
    background: rgba(0,0,0,0.1);
}

/* Content */
.content {
    margin-left: var(--sidebar-width);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    transition: margin-left 0.3s ease;
}

.dashboard-header {
    background: rgba(255,255,255,0.8);
    backdrop-filter: blur(10px);
    padding: 1rem 2rem;
    position: sticky;
    top: 0;
    z-index: 999;
    border-bottom: 1px solid rgba(0,0,0,0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-main {
    padding: 2rem;
    flex: 1;
}

/* Cards */
.card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    background: white;
    transition: transform 0.2s, box-shadow 0.2s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* Tables */
table th, table td { vertical-align: middle !important; }
table tbody tr.table-danger { background-color: rgba(255, 99, 132, 0.2); }

/* Scrollbars */
.sidebar::-webkit-scrollbar, .content::-webkit-scrollbar { width: 0px; background: transparent; }
.sidebar { scrollbar-width: none; -ms-overflow-style: none; }
.content { scrollbar-width: none; -ms-overflow-style: none; }

/* Mobile Responsive */
@media (max-width: 768px) {
    .sidebar { transform: translateX(-100%); }
    .content { margin-left: 0; }
    .sidebar.show { transform: translateX(0); }
}
</style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <i class="bi bi-box-seam-fill"></i>
        <span>ប្រព័ន្ធសម្ភារៈ</span>
    </a>
    
    <ul class="nav nav-pills flex-column mb-auto mt-2">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif"><i class="bi bi-speedometer2"></i> ទំព័រដេប៊ូ</a></li>
        <li class="nav-item"><a href="{{ route('materials.index') }}" class="nav-link @if(request()->routeIs('materials.*')) active @endif"><i class="bi bi-card-checklist"></i> បញ្ជីសម្ភារៈ</a></li>
        <li class="nav-item"><a href="{{ route('materials.stock.create_bulk') }}" class="nav-link @if(request()->routeIs('materials.stock.create_bulk')) active @endif"><i class="bi bi-box-arrow-in-down"></i> បញ្ចូលស្តុក</a></li>
        <li class="nav-item"><a href="{{ route('purchases.index') }}" class="nav-link @if(request()->routeIs('purchases.*')) active @endif"><i class="bi bi-cash-coin"></i> ការទិញចូល (Purchases)</a></li>
        <li class="nav-item"><a href="{{ route('suppliers.index') }}" class="nav-link @if(request()->routeIs('suppliers.*')) active @endif"><i class="bi bi-truck"></i> អ្នកផ្គត់ផ្គង់ (Suppliers)</a></li>
        <li class="nav-item"><a href="{{ route('orders.quick_create') }}" class="nav-link @if(request()->routeIs('orders.quick_create')) active @endif"><i class="bi bi-rocket-takeoff-fill"></i> បញ្ជាទិញលឿន</a></li>
        <li class="nav-item"><a href="{{ route('orders.index') }}" class="nav-link @if(request()->routeIs('orders.*')) active @endif"><i class="bi bi-receipt"></i> អ្នកបញ្ជាទិញ / Orders</a></li>
        <li class="nav-item"><a href="{{ route('invoices.index') }}" class="nav-link @if(request()->routeIs('invoices.*')) active @endif"><i class="bi bi-file-earmark-text"></i> វិក្កយបត្រ / Invoices</a></li>
        
        <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link @if(request()->routeIs('users.*')) active @endif"><i class="bi bi-people"></i> អ្នកប្រើប្រាស់</a></li>
    </ul>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST" class="w-100 text-center">
            @csrf
            <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" title="Logout">
                <i class="bi bi-box-arrow-right"></i> ចាកចេញ
            </button>
        </form>
        <div class="text-center text-muted mt-3" style="font-size: 0.8rem;">© 2026 ប្រព័ន្ធសម្ភារៈ</div>
    </div>
</div>

{{-- Main Content --}}
<div class="content">
    <header class="dashboard-header">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h4 class="fw-bold mb-0 text-dark">{{ $pageTitle ?? 'ទំព័រដេប៊ូ' }}</h4>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-flex align-items-center gap-2">
                <i class="bi bi-clock text-primary"></i>
                <span class="fw-medium text-secondary">{{ \Carbon\Carbon::now('Asia/Phnom_Penh')->format('h:i A | d M Y') }}</span>
            </div>
            <div class="dropdown">
                <div class="d-flex align-items-center gap-2 cursor-pointer" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ auth()->user()->name ?? 'Guest' }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ ucfirst(auth()->user()->role ?? 'user') }}</div>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-weight: bold;">
                        {{ substr(auth()->user()->name ?? 'G', 0, 1) }}
                    </div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" style="border-radius: 12px;">
                    <li><h6 class="dropdown-header text-uppercase text-muted" style="font-size: 0.75rem;">គណនី</h6></li>
                    <li><a class="dropdown-item rounded-2 py-2 @if(request()->routeIs('profile.show')) active @endif" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i> ប្រវត្តិរូប</a></li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-2 py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> ចាកចេញ</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <main class="dashboard-main">
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
