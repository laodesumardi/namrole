<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeadmasterGreeting;

class HeadmasterGreetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeadmasterGreeting::create([
            'headmaster_name' => 'Dr. H. Ahmad Rizki, M.Pd',
            'greeting_message' => 'Selamat datang di website resmi SMP Negeri 01 Namrole. Sebagai kepala sekolah, saya mengucapkan terima kasih atas kepercayaan yang diberikan kepada kami dalam mendidik putra-putri Anda. Kami berkomitmen untuk memberikan pendidikan yang berkualitas dan membentuk karakter siswa yang unggul.',
            'photo' => 'headmaster-greetings/1760857699_68f48e639295c.png',
            'is_active' => true,
        ]);
    }
}