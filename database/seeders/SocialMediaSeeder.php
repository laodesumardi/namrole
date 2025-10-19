<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SocialMedia;

class SocialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SocialMedia::create([
            'name' => 'Facebook',
            'url' => 'https://facebook.com/smpnamrole',
            'icon' => 'fab fa-facebook',
            'color' => '#1877f2',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        SocialMedia::create([
            'name' => 'Instagram',
            'url' => 'https://instagram.com/smpnamrole',
            'icon' => 'fab fa-instagram',
            'color' => '#e4405f',
            'is_active' => true,
            'sort_order' => 2,
        ]);

        SocialMedia::create([
            'name' => 'YouTube',
            'url' => 'https://youtube.com/smpnamrole',
            'icon' => 'fab fa-youtube',
            'color' => '#ff0000',
            'is_active' => true,
            'sort_order' => 3,
        ]);
    }
}