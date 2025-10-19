<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VisionMission;

class VisionMissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VisionMission::create([
            'vision' => 'Menjadi sekolah unggul yang menghasilkan lulusan berkarakter, berprestasi, dan berdaya saing global',
            'missions' => json_encode([
                'Menyelenggarakan pendidikan berkualitas yang mengembangkan potensi siswa secara optimal',
                'Membentuk karakter siswa yang berakhlak mulia dan berintegritas',
                'Mengembangkan kompetensi siswa dalam bidang akademik dan non-akademik'
            ]),
            'is_active' => true,
        ]);
    }
}