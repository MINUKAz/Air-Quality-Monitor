<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Add this line

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate(); // Add this line
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'status' => 'Active'
        ]);
    }
}