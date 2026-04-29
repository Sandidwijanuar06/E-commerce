<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        // Mengelompokkan permission berdasarkan kategori (misal: user, order, product)
        $permissions = Permission::all(); 
        
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles,name']);
        
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        return back()->with('success', "Role '{$role->name}' created successfully!");
    }

    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->permissions);
        return back()->with('success', "Permissions for '{$role->name}' updated!");
    }
}