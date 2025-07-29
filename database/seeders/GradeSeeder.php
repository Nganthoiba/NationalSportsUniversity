<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;

class GradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grades = [
            ['grade' => 'A+', 'description' => 'Outstanding'],
            ['grade' => 'A',  'description' => 'Excellent'],
            ['grade' => 'B+', 'description' => 'Very Good'],
            ['grade' => 'B',  'description' => 'Good'],
            ['grade' => 'C',  'description' => 'Average'],
            ['grade' => 'D',  'description' => 'Pass'],
            ['grade' => 'F',  'description' => 'Fail'],
        ];

        foreach ($grades as $grade) {
            //Grade::create($grade);
            Grade::updateOrCreate([
                'grade' => $grade['grade']
            ], $grade);
        }

        $this->command->info('MongoDB gender collection seeded.');
    }
}
