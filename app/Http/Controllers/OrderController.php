<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Material;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with search and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Order::with('user', 'materials');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('status', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->has('show_all')) {
            $orders = $query->orderByDesc('id')->get();
        } else {
            $orders = $query->orderByDesc('id')->paginate(10);
        }

        return view('orders.index', [
            'pageTitle' => 'បញ្ជីអ្នកបញ្ជាទិញ',
            'orders' => $orders,
            'search' => $search,
            'isAdmin' => auth()->user()->role === 'admin',
        ]);
    }
/**
 * Quick Order Creation page
 */
public function quickCreate()
{
    $materials = Material::where('status', 'active')->get();
    if (auth()->user()->role === 'admin') {
        $users = User::all();
    } else {
        $users = User::where('id', auth()->id())->get();
    }

    return view('orders.quick_create', [
        'pageTitle' => 'បញ្ជាទិញលឿន',
        'materials' => $materials,
        'users' => $users,
    ]);
}

    /**
     * Show the form to create a new order.
     */
    public function create()
    {
        $materials = Material::where('status', 'active')->get();
        if (auth()->user()->role === 'admin') {
            $users = User::all();
        } else {
            $users = User::where('id', auth()->id())->get();
        }

        return view('orders.create', [
            'pageTitle' => 'បង្កើតអ្នកបញ្ជាទិញថ្មី',
            'materials' => $materials,
            'users' => $users,
        ]);
    }

    /**
     * Store a new order and create its invoice.
     */
    public function store(Request $request)
    {
        // Filter out materials with 0 quantity before validation
        $materialsData = collect($request->materials)->filter(function ($material) {
            return isset($material['quantity']) && $material['quantity'] > 0;
        })->all();

        if (empty($materialsData)) {
            return redirect()->back()
                ->withErrors(['materials' => 'Please add at least one material with a quantity greater than 0.'])
                ->withInput();
        }

        // Replace request data with filtered data for validation
        $request->merge(['materials' => $materialsData]);

        if (auth()->user()->role !== 'admin') {
            $request->merge(['user_id' => auth()->id()]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,completed,cancelled',
            'materials' => 'required|array|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create the order
        $order = Order::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'notes' => $request->notes,
            'total_amount_usd' => 0,
            'total_amount_khr' => 0,
        ]);

        // Calculate totals and attach materials
        $totalUSD = 0;
        foreach ($request->materials as $m) {
            $material = Material::find($m['id']);
            $unitPrice = $material->price; // USD
            $quantity = $m['quantity'];
            $totalUSD += $unitPrice * $quantity;

            $order->materials()->attach($material->id, [
                'quantity' => $quantity,
                'unit_price_usd' => $unitPrice,
            ]);
        }

        $exchangeRate = 4100;
        $order->update([
            'total_amount_usd' => $totalUSD,
            'total_amount_khr' => $totalUSD * $exchangeRate,
        ]);

        // Create Invoice automatically
        Invoice::create([
            'order_id' => $order->id,
            'total_amount_usd' => $totalUSD,
            'total_amount_khr' => $totalUSD * $exchangeRate,
            'issued_date' => now(),
            'due_date' => now()->addDays(30),
            'status' => 'unpaid', // default
        ]);

        return redirect()->route('orders.index')->with('success', 'Order created successfully with invoice.');
    }

    /**
     * Display a single order.
     */
    public function show(Order $order)
    {
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to view this order.');
        }

        $order->load('user', 'materials', 'invoice');

        return view('orders.show', [
            'pageTitle' => 'ពិនិត្យអ្នកបញ្ជាទិញ',
            'order' => $order,
        ]);
    }

    /**
     * Show form to edit an order.
     */
    public function edit(Order $order)
    {
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to edit this order.');
        }

        $materials = Material::where('status', 'active')->get();
        if (auth()->user()->role === 'admin') {
            $users = User::all();
        } else {
            $users = User::where('id', auth()->id())->get();
        }

        return view('orders.edit', [
            'pageTitle' => 'កែប្រែអ្នកបញ្ជាទិញ',
            'order' => $order,
            'materials' => $materials,
            'users' => $users,
        ]);
    }

    /**
     * Update an order and its materials.
     */
    public function update(Request $request, Order $order)
    {
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to update this order.');
        }

        if (auth()->user()->role !== 'admin') {
            $request->merge(['user_id' => auth()->id()]);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:pending,completed,cancelled',
            'materials' => 'required|array|min:1',
            'materials.*.id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        // Detach old materials
        $order->materials()->detach();

        // Reattach new materials
        $totalUSD = 0;
        foreach ($request->materials as $m) {
            $material = Material::find($m['id']);
            $unitPrice = $material->price;
            $quantity = $m['quantity'];
            $totalUSD += $unitPrice * $quantity;

            $order->materials()->attach($material->id, [
                'quantity' => $quantity,
                'unit_price_usd' => $unitPrice,
            ]);
        }

        $exchangeRate = 4100;
        $order->update([
            'total_amount_usd' => $totalUSD,
            'total_amount_khr' => $totalUSD * $exchangeRate,
        ]);

        // Update invoice if exists
        if ($order->invoice) {
            $order->invoice->update([
                'total_amount_usd' => $totalUSD,
                'total_amount_khr' => $totalUSD * $exchangeRate,
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Delete an order.
     */
    public function destroy(Order $order)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'You are not authorized to delete orders.');
        }

        $order->materials()->detach();
        if ($order->invoice) {
            $order->invoice->delete();
        }
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }
}
