<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissões
        Permission::create(['name' => 'manage posts']);
        Permission::create(['name' => 'manage catalog']);
        Permission::create(['name' => 'manage texts']);
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage all']);

        // Cargos
        $redator = Role::create(['name' => 'redator']);
        $vendor = Role::create(['name' => 'vendor']);
        $manager = Role::create(['name' => 'manager']);
        $admin = Role::create(['name' => 'admin']);

        // Atribuir permissões
        $redator->givePermissionTo('manage posts');
        $vendor->givePermissionTo('manage catalog');
        $manager->givePermissionTo(['manage texts', 'manage users', 'manage catalog']);
        $admin->givePermissionTo(Permission::all());
    }
}
