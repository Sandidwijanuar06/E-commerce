<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog; // Pastikan Model ini sudah ada
use Illuminate\Http\Request;

class SecurityLogController extends Controller
{
    /**
     * Menampilkan Audit Logs dengan fitur Filter dan Pagination.
     */
    public function index(Request $request)
    {
        $query = SecurityLog::with('user')->latest();

        // Filter berdasarkan Event Type (contoh: login, failed_login, delete)
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filter berdasarkan IP Address
        if ($request->filled('ip_address')) {
            $query->where('ip_address', 'like', '%' . $request->ip_address . '%');
        }

        // Pagination 15 data per halaman
        $ledgers = $query->paginate(15)->withQueryString();

        return view('admin.security.logs', compact('ledgers'));
    }

    /**
     * Optional: Method untuk membersihkan log lama (Cleanup)
     */
    public function clearOldLogs()
    {
        try {
            // Hapus log yang lebih lama dari 30 hari
            SecurityLog::where('created_at', '<', now()->subDays(30))->delete();
            
            return back()->with('success', 'Logs yang lebih tua dari 30 hari telah dibersihkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membersihkan log: ' . $e->getMessage());
        }
    }
}