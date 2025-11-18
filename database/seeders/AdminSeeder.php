<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'sourav',
            'phone' => '01789563214',
            'password' => Hash::make('12345678'),
            'role' => 1,
            'status' => 1,
            'approved' => 1,
            'created_by' => 1,
        ]);
    }
}
