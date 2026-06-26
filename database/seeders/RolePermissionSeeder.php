<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [
            'dashboard-view',

            'user-view',
            'user-create',
            'user-edit',
            'user-delete',

            'product-view',
            'product-create',
            'product-edit',
            'product-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin',
            'guard_name' => 'web'
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web'
        ]);

        $staff = Role::firstOrCreate([
            'name' => 'Staff',
            'guard_name' => 'web'
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'Customer',
            'guard_name' => 'web'
        ]);

        $superAdmin->givePermissionTo(Permission::all());

        $admin->givePermissionTo([
            'dashboard-view',
            'user-view',
            'user-create',
            'user-edit',
            'product-view',
            'product-create',
            'product-edit'
        ]);

        $staff->givePermissionTo([
            'dashboard-view',
            'product-view'
        ]);

        $customer->givePermissionTo([
            'dashboard-view'
        ]);
    }
}