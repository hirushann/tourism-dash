<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = \Spatie\Permission\Models\Role::create(['name' => 'super admin']);
        $managerRole = \Spatie\Permission\Models\Role::create(['name' => 'manager']);
        $driverRole = \Spatie\Permission\Models\Role::create(['name' => 'driver']);

        $manageVehicles = \Spatie\Permission\Models\Permission::create(['name' => 'manage vehicles']);
        $assignVehicles = \Spatie\Permission\Models\Permission::create(['name' => 'assign vehicles']);
        $viewVehicles = \Spatie\Permission\Models\Permission::create(['name' => 'view vehicles']);
        $manageTours = \Spatie\Permission\Models\Permission::create(['name' => 'manage tours']);
        $viewTours = \Spatie\Permission\Models\Permission::create(['name' => 'view tours']);
        $generateReports = \Spatie\Permission\Models\Permission::create(['name' => 'generate reports']);

        $superAdminRole->givePermissionTo([\Spatie\Permission\Models\Permission::all()]);
        $managerRole->givePermissionTo([$manageVehicles, $assignVehicles, $viewVehicles, $manageTours, $viewTours, $generateReports]);
        $driverRole->givePermissionTo([$viewVehicles, $manageTours, $viewTours]);

        $superAdmin = \App\Models\User::create([
            'name' => 'Super Admin',
            'email' => 'admin@tourism-dash.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $superAdmin->assignRole($superAdminRole);

        $manager = \App\Models\User::create([
            'name' => 'Manager',
            'email' => 'manager@tourism-dash.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $manager->assignRole($managerRole);

        $driver = \App\Models\User::create([
            'name' => 'Driver',
            'email' => 'driver@tourism-dash.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
        $driver->assignRole($driverRole);
    }
}
