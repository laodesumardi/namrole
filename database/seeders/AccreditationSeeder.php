<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Accreditation;

class AccreditationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Accreditation::create([
            'status' => 'Terakreditasi A',
            'description' => 'SMP Negeri 01 Namrole telah meraih akreditasi A dengan skor 95, membuktikan kualitas pendidikan yang tinggi dan standar yang terbaik.',
            'score' => 95,
            'year' => 2023,
            'valid_until' => '2028-12-31',
            'certificate_number' => 'SK.001/2023',
            'is_active' => true,
        ]);
    }
}