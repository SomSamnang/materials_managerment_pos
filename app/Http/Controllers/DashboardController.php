<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard page (materials + orders + stats + search)
     */
    public function dashboard(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            return redirect()->route('orders.index');
        }

        $search = $request->get('search');
        $status = $request->get('status');
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';

        // ======================
        // MATERIALS
        // ======================
        $materialsQuery = Material::query();

        if (!$isAdmin) {
            $materialsQuery->where('status', 'active');
        }

        if ($search) {
            $materialsQuery->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            if ($status === 'low_stock') {
                $materialsQuery->whereColumn('stock', '<=', 'min_stock');
            } else {
                $materialsQuery->where('status', $status);
            }
        }

        $materials = $materialsQuery->orderByDesc('id')->get();

        if ($isAdmin) {
            $totalMaterials = Material::count();
            $lowStockCount  = Material::whereColumn('stock', '<=', 'min_stock')->count();
            $totalStock     = Material::sum('stock');
        } else {
            $totalMaterials = Material::where('status', 'active')->count();
            $lowStockCount  = Material::where('status', 'active')->whereColumn('stock', '<=', 'min_stock')->count();
            $totalStock     = Material::where('status', 'active')->sum('stock');
        }

        // Total price of all materials using total_usd / total_riel
        $totalPriceUSD = $materials->sum(fn($m) => $m->total_usd ?? ($m->stock * $m->price));
        
        // Dynamic Currency Logic
        $exchangeRate = Setting::getExchangeRate();
        $currency = session('currency', 'USD');
        $currencySymbol = $currency === 'KHR' ? '៛' : '$';
        $decimals = $currency === 'KHR' ? 0 : 2;

        // Calculate Total Material Price based on currency
        $totalMaterialPrice = $currency === 'KHR' 
            ? $totalPriceUSD * $exchangeRate 
            : $totalPriceUSD;

        // ======================
        // ORDERS
        // ======================
        $ordersQuery = Order::query();
        if (!$isAdmin) {
            $ordersQuery->where('user_id', $user->id);
        }

        $totalOrders      = (clone $ordersQuery)->count();
        $pendingOrders    = (clone $ordersQuery)->where('status', 'pending')->count();
        $completedOrders  = (clone $ordersQuery)->where('status', 'completed')->count();
        $cancelledOrders  = (clone $ordersQuery)->where('status', 'cancelled')->count();

        // Calculate total order amounts dynamically
        $totalOrderPriceUSD = (clone $ordersQuery)->with('materials')->get()->sum(function($order){
            return $order->materials->sum(fn($m) => $m->pivot->quantity * $m->pivot->unit_price_usd);
        });
        $totalOrderPriceKHR = $totalOrderPriceUSD * $exchangeRate;

        // ======================
        // INVOICES
        // ======================
        $invoicesQuery = Invoice::query();
        if (!$isAdmin) {
            $invoicesQuery->whereHas('order', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $totalInvoices    = (clone $invoicesQuery)->count();
        $paidInvoices     = (clone $invoicesQuery)->where('status', 'paid')->count();
        $unpaidInvoices   = (clone $invoicesQuery)->where('status', 'unpaid')->count();
        $acceptedInvoices = (clone $invoicesQuery)->where('status', 'accepted')->count();
        $overdueInvoices  = (clone $invoicesQuery)->where('status', 'overdue')->count();

        // ======================
        // PURCHASES & SUPPLIERS
        // ======================
        if ($isAdmin) {
            $totalPurchases    = Purchase::count();
            $totalPurchaseCostUSD = Purchase::sum('total_cost');
            $totalSuppliers    = Supplier::count();
        } else {
            $totalPurchases = 0;
            $totalPurchaseCostUSD = 0;
            $totalSuppliers = 0;
        }
        $totalPurchaseCost = $currency === 'KHR' ? $totalPurchaseCostUSD * $exchangeRate : $totalPurchaseCostUSD;

        // ======================
        // USERS
        // ======================
        if ($isAdmin) {
            $totalUsers  = User::count();
            $totalAdmins = User::where('role', 'admin')->count();
            $totalRegularUsers = $totalUsers - $totalAdmins;
        } else {
            $totalUsers = 0;
            $totalAdmins = 0;
            $totalRegularUsers = 0;
        }

        // ======================
        // Return dashboard view
        // ======================
        return view('dashboard', compact(
            'materials',
            'totalMaterials',
            'lowStockCount',
            'totalStock',
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'totalMaterialPrice',
            'currencySymbol',
            'decimals',
            'search',
            'status',

            // Orders stats
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalOrderPriceUSD',
            'totalOrderPriceKHR',

            // Invoices stats
            'totalInvoices',
            'paidInvoices',
            'unpaidInvoices',
            'acceptedInvoices',
            'overdueInvoices',

            // Purchases & Suppliers
            'totalPurchases',
            'totalPurchaseCost',
            'totalSuppliers',
            'isAdmin'
        ));
    }

    /**
     * Users page with search
     */
    public function users(Request $request)
    {
        $search = $request->get('search');

        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
        }

        $users = $query->orderByDesc('id')->get();

        return view('users.index', [
            'pageTitle' => 'បញ្ជីអ្នកប្រើប្រាស់',
            'users'     => $users,
            'search'    => $search,
        ]);
    }

    /**
     * Home route fallback
     */
    public function index(Request $request)
    {
        return $this->dashboard($request);
    }
}
