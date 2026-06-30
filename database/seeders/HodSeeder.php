<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HodSeeder extends Seeder {
    public function run(): void {
        DB::table('users')->insert([
            'name'       => 'Head of Department',
            'email'      => 'hod@campus.com',
            'password'   => Hash::make('password'),
            'user_type'  => 'hod',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}