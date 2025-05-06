<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'vstaff',
            'password'          => bcrypt('11111111'),
            'role'              => 'staff',
            'email_verified_at' => now()
        ]);
    }
}
