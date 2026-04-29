<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\StockLedger;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = StockLedger::with(['variant.product', 'warehouse'])->latest();

        // Filter berdasarkan gudang jika dipilih
        if ($request->warehouse_id) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $ledgers = $query->paginate(15);
        $warehouses = Warehouse::all();
        $variants = ProductVariant::with('product')->get();

        return view('admin.stock.index', compact('ledgers', 'warehouses', 'variants'));
    }

    public function createAdjustment()
    {
        $warehouses = Warehouse::all();
        $variants = ProductVariant::with('product')->get();
        
        // Gunakan admin.stock.adjust (pastikan file ada di resources/views/admin/stock/adjust.blade.php)
        return view('admin.stock.adjust', compact('warehouses', 'variants'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'adjustment_type' => 'required|in:addition,subtraction',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        $qty = $request->adjustment_type === 'addition' ? $request->quantity : -$request->quantity;

        StockLedger::create([
            'product_variant_id' => $request->product_variant_id,
            'warehouse_id' => $request->warehouse_id,
            'quantity' => $qty,
            'type' => 'adjustment',
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'Stock adjustment successful!');
    }
}