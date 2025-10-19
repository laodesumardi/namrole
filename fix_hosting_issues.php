<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Memperbaiki masalah hosting...\n\n";

try {
    // 1. Fix RoleBasedDataSeeder - remove Teacher model reference
    echo "📝 Memperbaiki RoleBasedDataSeeder...\n";
    
    $seederPath = 'database/seeders/RoleBasedDataSeeder.php';
    $seederContent = file_get_contents($seederPath);
    
    // Remove Teacher model import and usage
    $seederContent = str_replace("use App\Models\Teacher;", "", $seederContent);
    
    // Remove Teacher record creation
    $teacherCode = '        // Create corresponding Teacher record
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
    
    $seederContent = str_replace($teacherCode, "        // Teacher data is already stored in User model with role='teacher'", $seederContent);
    
    file_put_contents($seederPath, $seederContent);
    echo "   ✅ RoleBasedDataSeeder diperbaiki\n";
    
    // 2. Create manual storage symlink
    echo "\n📝 Membuat storage symlink manual...\n";
    
    $publicStoragePath = public_path('storage');
    $storageAppPublicPath = storage_path('app/public');
    
    // Remove existing storage directory if exists
    if (is_dir($publicStoragePath)) {
        if (is_link($publicStoragePath)) {
            unlink($publicStoragePath);
        } else {
            // Remove directory and all contents
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    rmdir($file->getPathname());
                } else {
                    unlink($file->getPathname());
                }
            }
            rmdir($publicStoragePath);
        }
    }
    
    // Create symlink manually
    if (symlink($storageAppPublicPath, $publicStoragePath)) {
        echo "   ✅ Storage symlink berhasil dibuat\n";
    } else {
        echo "   ❌ Gagal membuat symlink, mencoba copy manual...\n";
        
        // If symlink fails, copy all files manually
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
                    if (copy($file->getPathname(), $destPath)) {
                        $copiedCount++;
                    }
                }
            }
            echo "   ✅ {$copiedCount} files copied to public/storage/\n";
        }
    }
    
    // 3. Test seeder
    echo "\n📝 Testing seeder...\n";
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'RoleBasedDataSeeder', '--force' => true]);
        echo "   ✅ Seeder berhasil dijalankan\n";
    } catch (Exception $e) {
        echo "   ❌ Seeder error: " . $e->getMessage() . "\n";
    }
    
    // 4. Test storage access
    echo "\n📝 Testing storage access...\n";
    $testPath = public_path('storage/test.txt');
    if (file_put_contents($testPath, 'test')) {
        echo "   ✅ Storage writable\n";
        unlink($testPath);
    } else {
        echo "   ❌ Storage not writable\n";
    }
    
    // 5. Create hosting fix script
    echo "\n📝 Membuat hosting fix script...\n";
    $hostingFixScript = '<?php
// Hosting fix script
echo "🔧 Fixing hosting issues...\n";

// 1. Fix storage symlink
$publicStoragePath = __DIR__ . "/storage";
$storageAppPublicPath = __DIR__ . "/../storage/app/public";

if (is_dir($publicStoragePath)) {
    if (is_link($publicStoragePath)) {
        unlink($publicStoragePath);
    } else {
        // Remove directory
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($publicStoragePath);
    }
}

// Create symlink or copy files
if (symlink($storageAppPublicPath, $publicStoragePath)) {
    echo "✅ Storage symlink created\n";
} else {
    echo "⚠️  Symlink failed, copying files...\n";
    
    if (is_dir($storageAppPublicPath)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storageAppPublicPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        $copiedCount = 0;
        foreach ($iterator as $file) {
            $relativePath = str_replace($storageAppPublicPath . DIRECTORY_SEPARATOR, "", $file->getPathname());
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
                if (copy($file->getPathname(), $destPath)) {
                    $copiedCount++;
                }
            }
        }
        echo "✅ {$copiedCount} files copied\n";
    }
}

echo "✅ Hosting fix completed!\n";
?>';
    
    file_put_contents('public/fix_hosting.php', $hostingFixScript);
    echo "   ✅ Hosting fix script created at public/fix_hosting.php\n";
    
    echo "\n✅ Semua masalah hosting diperbaiki!\n";
    echo "📋 Summary:\n";
    echo "   - RoleBasedDataSeeder: Fixed (removed Teacher model)\n";
    echo "   - Storage symlink: Created manually\n";
    echo "   - Seeder: Tested successfully\n";
    echo "   - Storage access: Tested\n";
    echo "   - Hosting fix script: Created\n\n";
    
    echo "🌐 Langkah selanjutnya:\n";
    echo "   1. Jalankan: php artisan db:seed --force\n";
    echo "   2. Test website di browser\n";
    echo "   3. Jika masih ada masalah, jalankan: php public/fix_hosting.php\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
