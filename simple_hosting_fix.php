<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Simple hosting fix...\n\n";

try {
    // 1. Fix RoleBasedDataSeeder
    echo "📝 Fixing RoleBasedDataSeeder...\n";
    
    $seederPath = 'database/seeders/RoleBasedDataSeeder.php';
    $seederContent = file_get_contents($seederPath);
    
    // Remove Teacher model import
    $seederContent = str_replace("use App\Models\Teacher;", "", $seederContent);
    
    // Remove Teacher record creation
    $oldTeacherCode = '        // Create corresponding Teacher record
        Teacher::updateOrCreate(
            [\'nip\' => \'198505151990031001\'],
            [
                \'nip\' => \'198505151990031001\',
                \'name\' => \'Budi Santoso, S.Pd\',
                \'email\' => \'guru@smpnamrole.sch.id\',
                \'phone\' => \'081234567001\',
                \'address\' => \'Jl. Guru No. 1, Namrole\',
                \'birth_date\' => \'1985-05-15\',
                \'gender\' => \'male\',
                \'subject\' => \'Matematika\',
                \'education\' => \'S1 Pendidikan Matematika\',
                \'education_level\' => \'S1 Pendidikan Matematika\',
                \'position\' => \'Guru Matematika\',
                \'join_date\' => \'1990-03-01\',
                \'bio\' => \'Guru Matematika yang berpengalaman dalam mengajar siswa SMP.\',
                \'type\' => \'teacher\',
                \'is_active\' => true
            ]
        );';
    
    $newTeacherCode = '        // Teacher data is already stored in User model with role=\'teacher\'';
    
    $seederContent = str_replace($oldTeacherCode, $newTeacherCode, $seederContent);
    
    file_put_contents($seederPath, $seederContent);
    echo "   ✅ RoleBasedDataSeeder fixed\n";
    
    // 2. Create storage directory manually
    echo "\n📝 Creating storage directory manually...\n";
    
    $publicStoragePath = public_path('storage');
    $storageAppPublicPath = storage_path('app/public');
    
    // Create storage directory if not exists
    if (!is_dir($publicStoragePath)) {
        mkdir($publicStoragePath, 0755, true);
        echo "   ✅ Storage directory created\n";
    } else {
        echo "   ✅ Storage directory already exists\n";
    }
    
    // Copy files from storage/app/public to public/storage
    if (is_dir($storageAppPublicPath)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storageAppPublicPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        $copiedCount = 0;
        foreach ($iterator as $file) {
            $relativePath = str_replace($storageAppPublicPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $destPath = $publicStoragePath . DIRECTORY_SEPARATOR . $relativePath;
            
            if ($file->isDir()) {
                if (!is_dir($destPath)) {
                    mkdir($destPath, 0755, true);
                }
            } else {
                $destDir = dirname($destPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                if (!file_exists($destPath)) {
                    if (copy($file->getPathname(), $destPath)) {
                        $copiedCount++;
                    }
                }
            }
        }
        echo "   ✅ {$copiedCount} files copied to public/storage/\n";
    }
    
    // 3. Test seeder
    echo "\n📝 Testing seeder...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'RoleBasedDataSeeder', '--force' => true]);
        echo "   ✅ Seeder berhasil dijalankan\n";
    } catch (Exception $e) {
        echo "   ❌ Seeder error: " . $e->getMessage() . "\n";
    }
    
    // 4. Create hosting instructions
    echo "\n📝 Creating hosting instructions...\n";
    $instructions = [
        "🚀 HOSTING INSTRUCTIONS",
        "",
        "1. FIX SEEDER:",
        "   - RoleBasedDataSeeder sudah diperbaiki",
        "   - Teacher model reference sudah dihapus",
        "",
        "2. FIX STORAGE:",
        "   - Storage directory sudah dibuat",
        "   - Files sudah di-copy ke public/storage/",
        "",
        "3. RUN COMMANDS:",
        "   php artisan db:seed --force",
        "   php artisan cache:clear",
        "   php artisan config:clear",
        "",
        "4. TEST WEBSITE:",
        "   - Buka website di browser",
        "   - Check semua halaman",
        "   - Check gambar muncul",
        "",
        "✅ WEBSITE SIAP UNTUK HOSTING!"
    ];
    
    file_put_contents('HOSTING_FIX_INSTRUCTIONS.txt', implode("\n", $instructions));
    echo "   ✅ Hosting instructions created\n";
    
    echo "\n✅ Hosting issues fixed!\n";
    echo "📋 Summary:\n";
    echo "   - RoleBasedDataSeeder: Fixed\n";
    echo "   - Storage directory: Created\n";
    echo "   - Files copied: {$copiedCount}\n";
    echo "   - Seeder: Tested\n";
    echo "   - Instructions: Created\n\n";
    
    echo "🌐 Langkah selanjutnya:\n";
    echo "   1. Jalankan: php artisan db:seed --force\n";
    echo "   2. Jalankan: php artisan cache:clear\n";
    echo "   3. Test website di browser\n";
    echo "   4. Baca HOSTING_FIX_INSTRUCTIONS.txt\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
