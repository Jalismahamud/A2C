<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::create([
            'company_name' => 'Your Company Name',
            'company_email' => 'example@example.com',
            'company_phone' => '123-456-7890',
            'company_address' => 'Dhaka Bangladesh',
            'company_logo' => 'default_logo.png',
            'registration_bonus' => 1500,
            'agent_minimum_withdraw' => 50.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
