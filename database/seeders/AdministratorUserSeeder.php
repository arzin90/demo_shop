<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministratorUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'Jon',
            'last_name' => 'Smith',
            'status' => User::ACTIVE,
            'type' => User::ADMINISTRATOR,
            'email' => 'admin@gmail.com',
            'password' => Hash::make('adminPass'),
        ]);
    }
}
