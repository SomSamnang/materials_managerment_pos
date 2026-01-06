<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard page (materials + orders + stats + search)
     */
    public function dashboard(Request $request)
    {
        $search = $request->get('search');

        // ======================
        // MATERIALS
        // ======================
        $materialsQuery = Material::query();

        if ($search) {
            $materialsQuery->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%");
        }

        $materials = $materialsQuery->orderByDesc('id')->get();

        $totalMaterials = Material::count();
        $lowStockCount  = Material::whereColumn('stock', '<=', 'min_stock')->count();
        $totalStock     = Material::sum('stock');

        // Total price of all materials using total_usd / total_riel
        $totalPriceUSD = $materials->sum(fn($m) => $m->total_usd ?? ($m->stock * $m->price));
        $exchangeRate  = 4100;
        $totalPriceKHR = $materials->sum(fn($m) => $m->total_riel ?? (($m->total_usd ?? ($m->stock * $m->price)) * $exchangeRate));

        // ======================
        // ORDERS
        // ======================
        $totalOrders      = Order::count();
        $pendingOrders    = Order::where('status', 'pending')->count();
        $completedOrders  = Order::where('status', 'completed')->count();
        $cancelledOrders  = Order::where('status', 'cancelled')->count();

        // Calculate total order amounts dynamically
        $totalOrderPriceUSD = Order::with('materials')->get()->sum(function($order){
            return $order->materials->sum(fn($m) => $m->pivot->quantity * $m->pivot->unit_price_usd);
        });
        $totalOrderPriceKHR = $totalOrderPriceUSD * $exchangeRate;

        // ======================
        // INVOICES
        // ======================
        $totalInvoices    = Invoice::count();
        $paidInvoices     = Invoice::where('status', 'paid')->count();
        $unpaidInvoices   = Invoice::where('status', 'unpaid')->count();
        $acceptedInvoices = Invoice::where('status', 'accepted')->count();
        $overdueInvoices  = Invoice::where('status', 'overdue')->count();

        // ======================
        // PURCHASES & SUPPLIERS
        // ======================
        $totalPurchases    = Purchase::count();
        $totalPurchaseCost = Purchase::sum('total_cost');
        $totalSuppliers    = Supplier::count();

        // ======================
        // USERS
        // ======================
        $totalUsers  = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = $totalUsers - $totalAdmins;

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
            'totalPriceUSD',
            'totalPriceKHR',
            'search',

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
            'totalSuppliers'
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
