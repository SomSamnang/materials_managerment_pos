<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Material;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Purchase::with('material');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('supplier', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('material', function($mq) use ($search) {
                      $mq->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('start_date')) {
            $query->whereDate('purchase_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('purchase_date', '<=', $request->end_date);
        }

        $totalFilteredCost = (clone $query)->sum('total_cost');

        $purchases = $query->latest()->paginate(15)->withQueryString();
        return view('purchases.index', compact('purchases', 'totalFilteredCost'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $materials = Material::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('purchases.create', compact('materials', 'suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'purchase_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validatedData['total_cost'] = $validatedData['quantity'] * $validatedData['unit_cost'];

        Purchase::create($validatedData);

        return redirect()->route('purchases.index')
                         ->with('success', 'ការទិញចូលត្រូវបានកត់ត្រាដោយជោគជ័យ។ (Purchase recorded successfully.)');
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $materials = Material::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        
        return view('purchases.edit', compact('purchase', 'materials', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validatedData = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'purchase_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_cost' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $validatedData['total_cost'] = $validatedData['quantity'] * $validatedData['unit_cost'];

        $purchase->update($validatedData);

        return redirect()->route('purchases.index')
                         ->with('success', 'ការទិញចូលត្រូវបានកែប្រែដោយជោគជ័យ។ (Purchase updated successfully.)');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        
        return redirect()->route('purchases.index')
                         ->with('success', 'ការទិញចូលត្រូវបានលុបចោល។ (Purchase deleted successfully.)');
    }
}