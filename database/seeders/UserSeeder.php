<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRoleMapping;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $role = Role::where('role_name', 'Super Admin')->first();

        $user = User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ],[
            'full_name' => 'Admin User',
            'email' => 'admin@gmail.com',
            //'password' => bcrypt('admin@123'), // password
            'password' =>  Hash::make('Test@123'), // password
        ]);

        if(!empty($role) && !empty($user)){
            UserRoleMapping::create([
                'user_id' => $user->_id,
                'role_id' => $role->_id,
                'created_by' => 'App',
            ]);
            $this->command->info('MongoDB admin role and admin user created.');
        }
        //insert 3 dummy users
        //User::factory(3)->create();
    }
}
