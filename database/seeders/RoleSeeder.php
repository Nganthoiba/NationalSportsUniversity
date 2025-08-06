<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'role_name' => 'Super Admin',
                'role_description' => 'The head of all admin.',
                'permission_names' => [],
                'enabled' => true,
                'changeable' => false,
            ],
            /*
            [
                'role_name' => 'University Admin',
                'role_description' => 'The admin of a university/institution.',
                'permission_names' => [],
                'enabled' => true,
                'changeable' => false,
            ],
            [
                'role_name' => 'staff',
                'role_description' => 'The staff of any institution.',
                'permission_names' => [],
                'enabled' => true,
                'changeable' => false,
            ],*/
        ];

        foreach($roles as $role){
            Role::updateOrCreate([
                'role_name' => $role['role_name']
            ],$role);
        }
        $this->command->info('MongoDB admin roles seeded.');
    }
}
