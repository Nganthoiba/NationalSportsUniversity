<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Database\Seeders\RoleSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\GradeSeeder;
use Database\Seeders\GenderSeeder;
use Database\Seeders\SportsTableSeeder;
use Database\Seeders\UniversitySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        /* User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]); */
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            GradeSeeder::class,
            GenderSeeder::class,
            SportsTableSeeder::class,
            UniversitySeeder::class,
        ]);
        
    }
}
