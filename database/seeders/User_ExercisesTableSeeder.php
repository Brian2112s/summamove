<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exercise;


class User_ExercisesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $exercises = Exercise::all();

        foreach ($users as $user) {
            $user->exercises()->attach(
                $exercises->random(rand(1, 5))->pluck('id')->toArray()
            );
        }
    }
}
