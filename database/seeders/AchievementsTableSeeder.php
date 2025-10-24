<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Achievement::create([
            'exercise_id' => 1,
            'user_id' => 1,
            'date' => '2024-06-01',
            'start_time' => '08:00:00',
            'end_time' => '08:30:00',
            'quantity' => 50,
        ]);

        Achievement::create([
            'exercise_id' => 2,
            'user_id' => 2,
            'date' => '2024-06-02',
            'start_time' => '09:00:00',
            'end_time' => '09:20:00',
            'quantity' => 75,
        ]);

        Achievement::create([
            'exercise_id' => 4,
            'user_id' => 1,
            'date' => '2024-06-03',
            'start_time' => '07:30:00',
            'end_time' => '08:00:00',
            'quantity' => 40,
        ]);

        Achievement::create([
            'exercise_id' => 5,
            'user_id' => 2,
            'date' => '2024-06-02',
            'start_time' => '09:00:00',
            'end_time' => '09:20:00',
            'quantity' => 75,
        ]);
    }
}
