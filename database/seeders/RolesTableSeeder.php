<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'User', 'Aggregator'];

        foreach ($roles as $role) {
            if (!DB::table('roles')->where('role_name', $role)->exists()) {
                DB::table('roles')->insert(['role_name' => $role]);
            }
        }
    }
}
