<?php

namespace Database\Seeders;

use App\Models\AboutUs;
use App\Models\Mission;
use App\Models\PrivacyPolicy;
use App\Models\TermsCondition;
use App\Models\Vision;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VisionMissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vision::create([
            'title' => 'Lorem ipsum dolor sit amet',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur eum excepturi minima quisquam suscipit! Aliquam, amet fuga maiores modi necessitatibus nisi nobis porro quis ratione reprehenderit sed tempora ullam vel.',
        ]);

        Mission::create([
            'title' => 'Lorem ipsum dolor sit amet',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur eum excepturi minima quisquam suscipit! Aliquam, amet fuga maiores modi necessitatibus nisi nobis porro quis ratione reprehenderit sed tempora ullam vel.',
        ]);

        TermsCondition::create([
           'title' => 'Lorem ipsum dolor sit amet',
            'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur eum excepturi minima quisquam suscipit! Aliquam, amet fuga maiores modi necessitatibus nisi nobis porro quis ratione reprehenderit sed tempora ullam vel.',

        ]);

        PrivacyPolicy::create([
            'title' => 'Lorem ipsum dolor sit amet',
            'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur eum excepturi minima quisquam suscipit! Aliquam, amet fuga maiores modi necessitatibus nisi nobis porro quis ratione reprehenderit sed tempora ullam vel.',
        ]);

        AboutUs::create([
            'title' => 'Lorem ipsum dolor sit amet',
            'email' => 'user@gmail.com',
            'phone' => '01345676548',
            'website' => 'https://classicit.com.bd',
            'facebook' => 'https://www.facebook.com/classicitandskymartltd',
            'whatsapp_contact' => '01934567890',
            'short_description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur eum excepturi minima quisquam suscipit! Aliquam, amet fuga maiores modi necessitatibus nisi nobis porro quis ratione reprehenderit sed tempora ullam vel.',
        ]);
    }
}
