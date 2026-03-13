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
        $superAdminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'super admin']);
        $managerRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'manager']);
        $driverRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'driver']);

        $manageVehicles = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage vehicles']);
        $assignVehicles = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'assign vehicles']);
        $viewVehicles = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view vehicles']);
        $manageTours = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'manage tours']);
        $viewTours = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'view tours']);
        $generateReports = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'generate reports']);

        $superAdminRole->syncPermissions(\Spatie\Permission\Models\Permission::all());
        $managerRole->syncPermissions([$manageVehicles, $assignVehicles, $viewVehicles, $manageTours, $viewTours, $generateReports]);
        $driverRole->syncPermissions([$viewVehicles, $manageTours, $viewTours]);

        $superAdmin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@tourism-dash.com'],
            [
                'name' => 'Super Admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $superAdmin->assignRole($superAdminRole);

        $manager = \App\Models\User::updateOrCreate(
            ['email' => 'manager@tourism-dash.com'],
            [
                'name' => 'Manager',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $manager->assignRole($managerRole);

        $driver = \App\Models\User::updateOrCreate(
            ['email' => 'driver@tourism-dash.com'],
            [
                'name' => 'Driver',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $driver->assignRole($driverRole);
    }
}
