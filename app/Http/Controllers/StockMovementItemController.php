<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockMovementItemController extends Controller
{
    public function show(StockItem $stockItem)
    {
        $movements = $stockItem->stockMovements()->latest()->get();
        return view('stock_items.stock', compact('stockItem', 'movements'));
    }

    public function store(Request $request, StockItem $stockItem)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string'
        ]);

        $stockItem->stockMovements()->create($request->all());

        return redirect()->back()->with('success', 'Stock updated!');
    }
}
