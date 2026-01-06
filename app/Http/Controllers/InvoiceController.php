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
        $orders = Order::whereDoesntHave('invoice')->get();

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
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Print the invoice
     */
    public function print(Invoice $invoice)
    {
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
        $invoice->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'Invoice accepted successfully.');
    }
}
