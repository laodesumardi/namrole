<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleBasedDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 1 Admin User
        User::updateOrCreate(
            ['email' => 'admin@smpnamrole.sch.id'],
            [
                'name' => 'Administrator Sekolah',
                'email' => 'admin@smpnamrole.sch.id',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'phone' => '081234567000',
                'address' => 'Jl. Pendidikan No. 1, Namrole',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Create 1 Teacher User
        User::updateOrCreate(
            ['email' => 'guru@smpnamrole.sch.id'],
            [
                'name' => 'Budi Santoso, S.Pd',
                'email' => 'guru@smpnamrole.sch.id',
                'password' => Hash::make('guru123'),
                'role' => 'teacher',
                'teacher_id' => 'T001',
                'phone' => '081234567001',
                'address' => 'Jl. Guru No. 1, Namrole',
                'birth_date' => '1985-05-15',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Teacher data is already stored in User model with role='teacher'

        // Students must register through the registration form
        // No automatic student creation

        $this->command->info('Role-based data created successfully!');
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Admin: admin@smpnamrole.sch.id / admin123');
        $this->command->info('Teacher: guru@smpnamrole.sch.id / guru123');
        $this->command->info('Students: Must register at /register');
        $this->command->info('========================');
    }
}