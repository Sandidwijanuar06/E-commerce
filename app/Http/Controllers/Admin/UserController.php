<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // Mengambil user beserta roles-nya (Eager Loading)
        $users = User::with('roles')->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function update(Request $request, User $user) // <-- Tanda $ sudah ditambahkan
    {
        $request->validate([
            'roles' => 'required|array'
        ]);

        // Sinkronisasi role menggunakan Spatie
        $user->syncRoles($request->roles);

        return back()->with('success', "Permissions for {$user->name} updated!");
    }
}