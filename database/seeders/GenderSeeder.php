<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genders = [
            ['short_name' => 'M', 'gender_name' => 'Male'],            
            ['short_name' => 'F', 'gender_name' => 'Female'],            
            ['short_name' => 'T', 'gender_name' => 'Transgender'],            
        ];

        foreach ($genders as $gender) {
            Gender::updateOrCreate([
                'short_name' => $gender['short_name']
            ],$gender);
        }

        $this->command->info('MongoDB gender collection seeded.');
    }
}
