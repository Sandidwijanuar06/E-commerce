<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::with(['order.user'])->latest()->get();
        return view('admin.fulfillment.returns', compact('returns'));
    }

    public function updateStatus(Request $request, $id)
    {
        $return = ReturnRequest::findOrFail($id);
        $return->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);

        return back()->with('success', "Return status updated to " . strtoupper($request->status));
    }
}
