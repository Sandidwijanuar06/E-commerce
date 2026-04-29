<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    //menampilkan data coupons
    public function index()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.marketing.coupons', compact('coupons'));
    }
    
      //Menampilkan form pembuatan kupon.
     
    public function create()
    {
        return view('admin.marketing.create');
    }
    
    //Menyimpan kupon baru ke database.
    
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons,code|max:50',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'limit' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'is_active' => 'required|boolean',
        ]);

        // Otomatis kapitalisasi kode kupon agar rapi
        $data = $request->all();
        $data['code'] = strtoupper($request->code);

        Coupon::create($data);

        return redirect()->route('admin.marketing.coupons')
        ->with('success', 'Coupon published successfully!');
    }

    // Menampilkan form edit kupon
    public function edit(Coupon $coupon)
    {
        return view('admin.marketing.edit', compact('coupon'));
    }

    // Memproses perubahan data kupon
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code'       => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type'       => 'required|in:fixed,percentage',
            'value'      => 'required|numeric|min:0',
            'limit'      => 'required|integer|min:1',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after:start_date',
            'is_active'  => 'required|boolean',
        ]);

        try {
            $coupon->update([
                'code'       => strtoupper($request->code),
                'type'       => $request->type,
                'value'      => $request->value,
                'limit'      => $request->limit,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
                'is_active'  => $request->is_active,
            ]);

            return redirect()->route('admin.marketing.coupons')
                ->with('success', 'Kupon ' . $coupon->code . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memperbarui kupon: ' . $e->getMessage());
        }
    }

     //Menghapus kupon.
     
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted successfully.');
    }
}
