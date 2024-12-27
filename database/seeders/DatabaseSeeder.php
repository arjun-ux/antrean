<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'ref_group_id' => '1',
            'username' => 'admin',
            'name' => 'Admin',
            'password' => Hash::make('pass'),
            'handphone' => '080808080',
            'email' => 'admin@example.com',
        ]);
        User::create([
            'ref_group_id' => '2',
            'username' => 'P9171025201',
            'name' => 'Puskesmas Malaimsimsa',
            'password' => Hash::make('pass'),
            'handphone' => '080808080',
        ]);
        User::create([
            'ref_group_id' => '2',
            'username' => 'P9171023202',
            'name' => 'MALAWEI',
            'password' => Hash::make('pass'),
            'handphone' => '080808080',
        ]);

    }
}
