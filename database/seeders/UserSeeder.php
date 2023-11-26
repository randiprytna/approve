<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => \Hash::make('Super123*Admin'),
            'role_id' => 1
        ]);

        \App\Models\User::create([
            'name' => 'user 1',
            'email' => 'user1@test.com',
            'password' => \Hash::make('Super123*User1'),
            'role_id' => 2
        ]);

        \App\Models\User::create([
            'name' => 'user 2',
            'email' => 'user2@test.com',
            'password' => \Hash::make('Super123*User2'),
            'role_id' => 2
        ]);

        \App\Models\User::create([
            'name' => 'user 3',
            'email' => 'user3@test.com',
            'password' => \Hash::make('Super123*User3'),
            'role_id' => 2
        ]);
    }
}
