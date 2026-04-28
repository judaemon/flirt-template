<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'view roles',
            'create roles',
            'edit roles',
            'delete roles',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create roles and assign permissions
        $superAdmin = Role::findOrCreate('super-admin');
        // super-admin usually gets all permissions via a gate check, 
        // but we can assign them all here too for simplicity.
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::findOrCreate('admin');
        $admin->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'view roles',
        ]);

        $userRole = Role::findOrCreate('user');
        // Standard users might not have any specific management permissions by default
    }
}
