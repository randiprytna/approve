<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Role::create([
            'role' => 'admin',
            'description' => 'handle incoming complaint reports'
        ]);

        \App\Models\Role::create([
            'role' => 'user',
            'description' => 'make a complaint report'
        ]);
    }
}
