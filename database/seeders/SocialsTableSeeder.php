<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Social;

class SocialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('local'))
            Social::truncate();

        $socials = ['spotify', 'instagram', 'facebook', 'soundcloud', 'youtube'];

        foreach ($socials as $social_name) {
            Social::create([
                'name' => $social_name,
            ]);
        }
    }
}
