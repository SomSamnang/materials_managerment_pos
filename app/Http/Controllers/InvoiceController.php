<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = Invoice::with('order.user');

        if (auth()->user()->role !== 'admin') {
            $query->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        if ($search) {
            $query->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('order.user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $invoices = $query->orderByDesc('id')->paginate(10);

        return view('invoices.index', [
            'pageTitle' => 'បញ្ជីវិក្កយបត្រ',
            'invoices' => $invoices,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $query = Order::whereDoesntHave('invoice');
        if (auth()->user()->role !== 'admin') {
            $query->where('user_id', auth()->id());
        }
        $orders = $query->get();

        return view('invoices.create', [
            'pageTitle' => 'បង្កើតវិក្កយបត្រថ្មី',
            'orders' => $orders,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id|unique:invoices,order_id',
        ]);

        $order = Order::find($request->order_id);

        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to create an invoice for this order.');
        }

        Invoice::create([
            'order_id' => $order->id,
            'total_amount_usd' => $order->total_amount_usd,
            'total_amount_khr' => $order->total_amount_khr,
            'issued_date' => now(),
            'due_date' => now()->addDays(30),
        ]);

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        if (auth()->user()->role !== 'admin' && $invoice->order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to view this invoice.');
        }

        $invoice->load('order.user', 'order.materials');

        return view('invoices.show', [
            'pageTitle' => 'ពិនិត្យវិក្កយបត្រ',
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'You are not authorized to edit invoices.');
        }

        return view('invoices.edit', [
            'pageTitle' => 'កែប្រែវិក្កយបត្រ',
            'invoice' => $invoice,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'You are not authorized to update invoices.');
        }

        $request->validate([
            'status' => 'required|in:unpaid,paid,overdue',
            'due_date' => 'nullable|date',
        ]);

        $invoice->update($request->only(['status', 'due_date']));

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'You are not authorized to delete invoices.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Print the invoice
     */
    public function print(Invoice $invoice)
    {
        if (auth()->user()->role !== 'admin' && $invoice->order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to print this invoice.');
        }

        $invoice->load('order.user', 'order.materials');

        return view('invoices.print', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Accept the invoice
     */
    public function accept(Invoice $invoice)
    {
        if (auth()->user()->role !== 'admin' && $invoice->order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to accept this invoice.');
        }

        $invoice->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'Invoice accepted successfully.');
    }
}
