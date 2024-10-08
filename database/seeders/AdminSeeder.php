<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'first_name' => 'ADMINISTRATOR',
            'middle_name' => '',
            'last_name' => 'Admin',
            'extension' => '',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'), 
            'role_id' => 1, // Assuming 1 ID for Admin
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
