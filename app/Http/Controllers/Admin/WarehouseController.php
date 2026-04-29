<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WarehouseController extends Controller
{
    /**
     * Menampilkan daftar gudang.
     */
    public function index()
    {
        // Mengambil data warehouse beserta jumlah record stoknya
        $warehouses = Warehouse::withCount('stockLedgers')->get();
        
        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * Menyimpan gudang baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:warehouses,code',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        try {
            Warehouse::create([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'address' => $request->address,
            ]);

            return redirect()->route('admin.warehouses.index')
                ->with('success', 'Gudang baru berhasil didaftarkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambah gudang: ' . $e->getMessage());
        }
    }

    /**
     * Update data gudang yang sudah ada.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:warehouses,code,' . $warehouse->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        try {
            $warehouse->update([
                'code' => strtoupper($request->code),
                'name' => $request->name,
                'address' => $request->address,
            ]);

            return redirect()->route('admin.warehouses.index')
                ->with('success', 'Informasi gudang berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui gudang: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus gudang dari database.
     */
    public function destroy(Warehouse $warehouse)
    {
        try {
            // Cek apakah gudang ini memiliki riwayat stok (Stock Ledger)
            if ($warehouse->stockLedgers()->count() > 0) {
                return back()->with('error', 'Gudang tidak bisa dihapus karena masih memiliki riwayat transaksi stok.');
            }

            $warehouse->delete();

            return redirect()->route('admin.warehouses.index')
                ->with('success', 'Gudang telah berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus gudang: ' . $e->getMessage());
        }
    }
}