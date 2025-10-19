<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::create([
            'address' => 'Jl. Pendidikan No. 1, Namrole, Buru Selatan, Maluku',
            'phone' => '(0913) 123456',
            'email' => 'info@smpnamrole.sch.id',
            'website' => 'https://smpnamrole.sch.id',
            'is_active' => true,
        ]);
    }
}