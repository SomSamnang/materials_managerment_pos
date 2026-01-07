<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = Material::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Calculate Grand Total (All Pages)
        $grandTotalQuery = clone $query;
        $grandTotalUSD = $grandTotalQuery->sum(DB::raw('stock * price'));
        $grandTotalKHR = $grandTotalUSD * 4100;

        // Sorting Logic
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'desc');
        $allowedSorts = ['id', 'code', 'name', 'status', 'stock', 'price', 'created_at'];

        if (!in_array($sort, $allowedSorts)) $sort = 'id';
        if (!in_array($direction, ['asc', 'desc'])) $direction = 'desc';

        $materials = $query->orderBy($sort, $direction)->paginate(15)->withQueryString();

        $lowStockCount = Material::whereColumn('stock', '<', 'min_stock')->count();

        return view('materials.index', compact('materials', 'lowStockCount', 'grandTotalUSD', 'grandTotalKHR'));
    }

    public function create()
    {
        $lastMaterial = Material::orderBy('id', 'desc')->first();
        $newNumber = $lastMaterial ? (int) str_replace('MAT-', '', $lastMaterial->code) + 1 : 1;
        $newCode = 'MAT-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        return view('materials.create', compact('newCode'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:materials,code',
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        $data['total_usd'] = $data['stock'] * $data['price'];
        $data['total_riel'] = $data['total_usd'] * 4100;

        Material::create($data);

        return redirect()->route('materials.index')->with('success', 'សម្ភារៈត្រូវបានបន្ថែមដោយជោគជ័យ!');
    }

    public function show(Material $material)
    {
        return view('materials.show', compact('material'));
    }

    public function edit(Material $material)
    {
        return view('materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $data = $request->validate([
            'code' => 'required|unique:materials,code,' . $material->id,
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'min_stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            if ($material->image) {
                Storage::disk('public')->delete($material->image);
            }
            $data['image'] = $request->file('image')->store('materials', 'public');
        }

        $data['total_usd'] = $data['stock'] * $data['price'];
        $data['total_riel'] = $data['total_usd'] * 4100;

        $material->update($data);

        return redirect()->route('materials.index')->with('success', 'សម្ភារៈត្រូវបានកែប្រែដោយជោគជ័យ!');
    }

    public function destroy(Material $material)
    {
        if ($material->image) {
            Storage::disk('public')->delete($material->image);
        }

        $material->delete();

        return redirect()->route('materials.index')->with('success', 'បានលុបសម្ភារៈដោយជោគជ័យ');
    }

    /**
     * Adjust stock for a material.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Material  $material
     * @return \Illuminate\Http\RedirectResponse
     */
    public function adjustStock(Request $request, Material $material)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
            'type' => 'required|in:in,out',
            'notes' => 'nullable|string|max:1000',
        ]);

        $quantity = (int) $data['quantity'];

        if ($data['type'] === 'in') {
            $material->stock += $quantity;
            $message = 'បញ្ចូលស្តុកដោយជោគជ័យ។';
        } else { // 'out'
            if ($material->stock < $quantity) {
                return back()->withErrors(['quantity' => 'បរិមាណដកចេញច្រើនជាងស្តុកដែលមាន។'])->withInput();
            }
            $material->stock -= $quantity;
            $message = 'ដកស្តុកដោយជោគជ័យ។';
        }

        $material->save();

        // TODO: Log the stock movement with notes for auditing purposes.

        return redirect()->route('materials.edit', $material)->with('success', $message);
    }

    /**
     * Show the form for creating a bulk stock entry.
     *
     * @return \Illuminate\View\View
     */
    public function createBulkStock()
    {
        $materials = Material::orderBy('name', 'asc')->get();
        return view('materials.create_bulk_stock', [
            'pageTitle' => 'បញ្ចូលស្តុកច្រើនមុខ',
            'materials' => $materials,
        ]);
    }

    /**
     * Store a new bulk stock entry.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeBulkStock(Request $request)
    {
        // Filter out materials with 0 quantity
        $materialsData = collect($request->materials)->filter(function ($material) {
            return isset($material['quantity']) && $material['quantity'] > 0;
        })->all();

        if (empty($materialsData)) {
            return redirect()->back()
                ->withErrors(['materials' => 'សូមបញ្ចូលបរិមាណសម្រាប់សម្ភារៈយ៉ាងតិចមួយមុខ។'])
                ->withInput();
        }

        $request->merge(['materials' => $materialsData]);

        $request->validate([
            'materials' => 'required|array|min:1',
            'materials.*.id' => 'required|exists:materials,id',
            'materials.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        foreach ($request->materials as $m) {
            $material = Material::find($m['id']);
            if ($material) {
                $material->increment('stock', (int)$m['quantity']);
                // TODO: Log stock movement with notes for auditing purposes.
            }
        }

        return redirect()->route('materials.index')->with('success', 'បានបញ្ចូលស្តុកដោយជោគជ័យ។');
    }

    /**
     * Bulk update status for materials.
     */
    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:materials,id',
            'status' => 'required|in:active,inactive',
        ]);

        Material::whereIn('id', $request->ids)->update(['status' => $request->status]);

        return back()->with('success', 'ស្ថានភាពសម្ភារៈត្រូវបានកែប្រែជាក្រុមដោយជោគជ័យ។');
    }
}
