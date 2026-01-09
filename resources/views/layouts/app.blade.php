<!DOCTYPE html>
<html lang="km">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $pageTitle ?? __('Material Management System') }}</title>

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

@media print {
    @page {
        size: landscape;
        margin: 10mm;
    }
    body {
        background-color: white !important;
        font-family: 'Times New Roman', Times, serif; /* Professional font for print */
        color: black !important;
    }
    .sidebar, .dashboard-header, .btn, .no-print, .card-footer { display: none !important; }
    .content { margin-left: 0 !important; padding: 0 !important; }
    .card { box-shadow: none !important; border: none !important; background: none !important; }
    .container-fluid { padding: 0 !important; }
    
    /* Table Styling for Print */
    .table { width: 100% !important; border-collapse: collapse !important; font-size: 12px; }
    .table th, .table td { border: 1px solid #ddd !important; padding: 8px !important; color: black !important; }
    .table thead th { background-color: #f0f0f0 !important; font-weight: bold !important; -webkit-print-color-adjust: exact; }
    .badge { border: 1px solid #000; color: #000 !important; background: none !important; padding: 2px 5px; }
    .text-success, .text-primary, .text-danger { color: black !important; } /* Remove colors for B&W print */
}
</style>
</head>
<body>

{{-- Sidebar --}}
<div class="sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <i class="bi bi-box-seam-fill"></i>
        <span>{{ __('Material System') }}</span>
    </a>
    
    <ul class="nav nav-pills flex-column mb-auto mt-2">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link @if(request()->routeIs('dashboard')) active @endif"><i class="bi bi-speedometer2"></i> {{ __('Dashboard') }}</a></li>
        <li class="nav-item"><a href="{{ route('materials.index') }}" class="nav-link @if(request()->routeIs('materials.*')) active @endif"><i class="bi bi-card-checklist"></i> {{ __('Material List') }}</a></li>
        <li class="nav-item"><a href="{{ route('materials.stock.create_bulk') }}" class="nav-link @if(request()->routeIs('materials.stock.create_bulk')) active @endif"><i class="bi bi-box-arrow-in-down"></i> {{ __('Add Stock') }}</a></li>
        <li class="nav-item"><a href="{{ route('purchases.index') }}" class="nav-link @if(request()->routeIs('purchases.*')) active @endif"><i class="bi bi-cash-coin"></i> {{ __('Purchases') }}</a></li>
        <li class="nav-item"><a href="{{ route('suppliers.index') }}" class="nav-link @if(request()->routeIs('suppliers.*')) active @endif"><i class="bi bi-truck"></i> {{ __('Suppliers') }}</a></li>

        <li class="nav-item"><a href="{{ route('orders.index') }}" class="nav-link @if(request()->routeIs('orders.*')) active @endif"><i class="bi bi-receipt"></i> {{ __('Orders') }}</a></li>
        <li class="nav-item"><a href="{{ route('invoices.index') }}" class="nav-link @if(request()->routeIs('invoices.*')) active @endif"><i class="bi bi-file-earmark-text"></i> {{ __('Invoices') }}</a></li>
        
        <li class="nav-item"><a href="{{ route('users.index') }}" class="nav-link @if(request()->routeIs('users.*')) active @endif"><i class="bi bi-people"></i> {{ __('Users') }}</a></li>
        @if(auth()->user()->role === 'admin')
        <li class="nav-item"><a href="{{ route('settings.edit') }}" class="nav-link @if(request()->routeIs('settings.*')) active @endif"><i class="bi bi-gear"></i> {{ __('Settings') }}</a></li>
        @endif
    </ul>

    <div class="sidebar-footer">
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('language.switch', 'en') }}" class="btn btn-sm w-100 {{ app()->getLocale() == 'en' ? 'btn-primary' : 'btn-outline-secondary' }}">
                English
            </a>
            <a href="{{ route('language.switch', 'kh') }}" class="btn btn-sm w-100 {{ app()->getLocale() == 'kh' ? 'btn-primary' : 'btn-outline-secondary' }}">
                ខ្មែរ
            </a>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="w-100 text-center">
            @csrf
            <button type="submit" class="btn btn-danger w-100 d-flex align-items-center justify-content-center gap-2" title="{{ __('Logout') }}">
                <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
            </button>
        </form>
        <div class="text-center text-muted mt-3" style="font-size: 0.8rem;">© 2026 {{ __('Material System') }}</div>
    </div>
</div>

{{-- Main Content --}}
<div class="content">
    <header class="dashboard-header">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list fs-4"></i>
            </button>
            <h4 class="fw-bold mb-0 text-dark">{{ $pageTitle ?? __('Dashboard') }}</h4>
        </div>
        
        <div class="d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn bg-white px-3 py-2 rounded-pill shadow-sm border d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-translate text-primary"></i>
                    <span class="fw-medium text-secondary">{{ app()->getLocale() == 'en' ? 'English' : 'ខ្មែរ' }}</span>
                </button>
                <ul class="dropdown-menu border-0 shadow-lg p-2 mt-2" style="border-radius: 12px;">
                    <li><a class="dropdown-item rounded-2 py-2 {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">English</a></li>
                    <li><a class="dropdown-item rounded-2 py-2 {{ app()->getLocale() == 'kh' ? 'active' : '' }}" href="{{ route('language.switch', 'kh') }}">ខ្មែរ</a></li>
                </ul>
            </div>
          
            <div class="bg-white px-3 py-2 rounded-pill shadow-sm border d-flex align-items-center gap-2">
                <i class="bi bi-clock text-primary"></i>
                <span class="fw-medium text-secondary">{{ \Carbon\Carbon::now('Asia/Phnom_Penh')->format('h:i A | d M Y') }}</span>
            </div>
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text-end d-none d-sm-block">
                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ auth()->user()->name ?? 'Guest' }}</div>
                        <div class="text-muted" style="font-size: 0.75rem;">{{ ucfirst(auth()->user()->role ?? 'user') }}</div>
                    </div>
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name ?? 'User' }}" class="rounded-circle shadow-sm" width="40" height="40" style="object-fit: cover;">
                </a>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-2 mt-2" style="border-radius: 12px;">
                    <li><h6 class="dropdown-header text-uppercase text-muted" style="font-size: 0.75rem;">{{ __('Account') }}</h6></li>
                    <li><a class="dropdown-item rounded-2 py-2 @if(request()->routeIs('profile.show')) active @endif" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i> {{ __('Profile') }}</a></li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item rounded-2 py-2 text-danger"><i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}</button>
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
