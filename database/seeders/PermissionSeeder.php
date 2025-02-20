<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cache to avoid issues
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define roles
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Event Creator']);
        Role::create(['name' => 'User']);

        // Define permissions
        $permissions = [
            'manage users',
            'assign roles',
            'delete events',
            'create events',
            'update own events',
            'view own events',
            'view events',
            'book tickets',
            'cancel bookings',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin = Role::where('name', 'Admin')->first();
        $eventCreator = Role::where('name', 'Event Creator')->first();
        $user = Role::where('name', 'User')->first();

        if ($admin) {
            $admin->givePermissionTo([
                'manage users',
                'assign roles',
                'delete events',
                'create events',
                'update own events',
                'view own events',
                'view events',
                'book tickets',
                'cancel bookings',
            ]);
        }

        if ($eventCreator) {
            $eventCreator->givePermissionTo([
                'create events',
                'update own events',
                'view own events',
                'view events',
                'book tickets',
                'cancel bookings',
            ]);
        }

        if ($user) {
            $user->givePermissionTo([
                'view events',
                'book tickets',
                'cancel bookings',
            ]);
        }
    }
}
