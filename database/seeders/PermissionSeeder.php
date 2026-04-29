<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $list = [
        'user.view', 'user.create', 'user.edit', 'user.delete',
        'product.view', 'product.create', 'product.edit',
        'order.view', 'order.process', 'order.print',
        'report.view', 'marketing.manage'
    ];

    foreach ($list as $perm) {
        \Spatie\Permission\Models\Permission::create(['name' => $perm]);
    }
    
    // Buat Role Super Admin
    $admin = \Spatie\Permission\Models\Role::create(['name' => 'super-admin']);
    $admin->givePermissionTo(\Spatie\Permission\Models\Permission::all());
    }
}
