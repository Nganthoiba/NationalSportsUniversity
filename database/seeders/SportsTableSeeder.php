<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use MongoDB\Client as Mongo;
use App\Models\Student;
use App\Models\Sport;

class SportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $students = Student::where('sports', '!=', null)->where('sports', '!=', "")
            ->where('sports_in_hindi', '!=', null)->where('sports_in_hindi', '!=', "")
            ->get(['sports', 'sports_in_hindi']);

        $uniqueSports = $students->unique(function ($item) {
            return $item['sports'] . '|' . $item['sports_in_hindi'];
        });

        foreach ($uniqueSports as $sport) {
            Sport::updateOrCreate(
                ['sport_name' => $sport['sports']],
                ['sport_name_in_hindi' => $sport['sports_in_hindi']]
            );
        }
        $this->command->info('MongoDB sports collection seeded from students collection.'); 
    }

    /* public function run(): void
    {
        $mongo = new Mongo(env('DB_DSN')); // Update URI if needed
        $students = $mongo->{env('DB_DATABASE')}->students;
        $sports = $mongo->{env('DB_DATABASE')}->sports;

        // Let's extract unique sports and sports_in_hindi
        $results = $students->aggregate([
            [
                '$match' => [
                    'sports' => ['$exists' => true, '$ne' => null],
                    'sports_in_hindi' => ['$exists' => true, '$ne' => null]
                ]
            ],
            [
                '$group' => [
                    '_id' => [
                        'sports' => '$sports',
                        'sports_in_hindi' => '$sports_in_hindi'
                    ]
                ]
            ],
            ['$group' => [
                '_id' => null,
                'sports' => ['$addToSet' => '$sports'],
                'sports_in_hindi' => ['$addToSet' => '$sports_in_hindi']
            ]]
        ]);

        // Insert into the sports table
        foreach ($results as $result) {
            

            $sports->updateOne(
                ['sport_name' => $result['sports']],
                ['$set' => ['sport_name_in_hindi' => $result['sports_in_hindi']]],
                ['upsert' => true]
            );

            $this->command->info('MongoDB sports collection seeded from students collection.');
        }
    } */
}
