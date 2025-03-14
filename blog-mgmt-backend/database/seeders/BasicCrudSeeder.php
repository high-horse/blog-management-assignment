<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BasicCrudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // prepare roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // prepare permissions
        $permissions = [
            'create-post',
            'update-post',
            'delete-post',
            'view-post',
            'super-post',

            'create-user',
            'update-user',
            'delete-user',
            'view-user',
            'super-user'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // assign permissions to roles
        $adminRole->givePermissionTo($permissions);
        $userRole->givePermissionTo(['create-post', 'update-post', 'view-post', 'delete-post']);

        // create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);

        // create regular user
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password')
        ]);

        // assign roles to the users
        $adminUser->assignRole($adminRole);
        $regularUser->assignRole($userRole);
    }
}
