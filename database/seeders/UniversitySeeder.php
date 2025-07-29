<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\University;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            ['name' => 'National Sports University, Manipur', 'email' => 'nsu-manipur@gmail.com'],           
            ['name' => 'National Sports University, Assam', 'email' => 'nsu-assam@gmail.com'],           
            ['name' => 'National Sports University, Nagaland', 'email' => 'nsu-nagaland@gmail.com'],           
        ];

        foreach ($universities as $university) {
            University::updateOrCreate([
                'email' => $university['email']
            ],$university);
        }

        $this->command->info('MongoDB universities collection seeded.');
    }
}
