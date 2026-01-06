<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockItemController extends Controller
{
    public function index()
    {
        $items = StockItem::latest()->get();
        return view('stock_items.index', compact('items'));
    }

    public function create()
    {
        return view('stock_items.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:stock_items,code',
            'name' => 'required'
        ]);

        StockItem::create($request->all());

        return redirect()->route('stock_items.index')->with('success', 'Stock Item added!');
    }
}
