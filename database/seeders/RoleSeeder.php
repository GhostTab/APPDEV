<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'description' => 'Administrator with full access',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'organizer',
                'description' => 'Event organizer with event management access',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user',
                'description' => 'Regular user',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
