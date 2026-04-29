<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PermissionRoleSeeder extends Seeder
{
    public function run()
    {
        // Permission sesuai standar operasional
        $permissions = [
            'user.manage', 'role.manage', 'product.manage', 
            'category.manage', 'order.view', 'order.status',
            'stock.manage', 'coupon.manage', 'cms.manage'
        ];

        foreach ($permissions as $perm) {
            Permission::updateOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Buat Super Admin Role
        $role = Role::updateOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $role->syncPermissions(Permission::all());

        // User Utama: Sandi Dwi Januar
        $user = User::updateOrCreate(
            ['email' => 'sandi@enterprise.com'],
            [
                'name' => 'Sandi Dwi Januar',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]
        );
        $user->assignRole($role);
    }
}
