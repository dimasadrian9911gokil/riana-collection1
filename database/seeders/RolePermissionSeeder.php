<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
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

            'role-view',
            'role-create',
            'role-edit',
            'role-delete',
        ];

        foreach ($permissions as $permission) {

            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin'
        ]);

        $staff = Role::firstOrCreate([
            'name' => 'Staff'
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'Customer'
        ]);

        $superAdmin->givePermissionTo(
            Permission::all()
        );

        $admin->givePermissionTo([
            'dashboard-view',
            'user-view',
            'user-create',
            'user-edit',
            'product-view',
            'product-create',
            'product-edit',
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