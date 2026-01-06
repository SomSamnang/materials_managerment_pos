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
    public function index()
    {
        $purchases = Purchase::with('material')->latest()->paginate(15);
        return view('purchases.index', compact('purchases'));
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
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        
        return redirect()->route('purchases.index')
                         ->with('success', 'ការទិញចូលត្រូវបានលុបចោល។ (Purchase deleted successfully.)');
    }
}