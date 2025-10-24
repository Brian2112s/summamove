<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                
                User::create([
                    'name' => 'Brian Meurkens',
                    'email' => 'brian@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'admin',
                ]);

                User::create([
                    'name' => 'Eren Atalay',
                    'email' => 'eren@example.com',
                    'password' => bcrypt('password'),
                    'role' => 'member',
                ]);

    }
}
