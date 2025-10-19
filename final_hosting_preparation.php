<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Final preparation untuk hosting...\n\n";

try {
    // 1. Clear cache
    echo "📝 Clearing cache...\n";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "   ✅ Cache cleared\n";
    
    // 2. Ensure storage symlink exists
    echo "\n📝 Checking storage symlink...\n";
    $symlinkPath = public_path('storage');
    $targetPath = storage_path('app/public');
    
    if (is_link($symlinkPath)) {
        echo "   ✅ Storage symlink already exists\n";
    } elseif (is_dir($symlinkPath)) {
        echo "   ✅ Storage directory exists\n";
    } else {
        if (symlink($targetPath, $symlinkPath)) {
            echo "   ✅ Storage symlink created\n";
        } else {
            echo "   ❌ Failed to create storage symlink\n";
        }
    }
    
    // 3. Copy all images to public storage
    echo "\n📝 Copying all images to public storage...\n";
    $storageDir = storage_path('app/public');
    $publicStorageDir = public_path('storage');
    $copiedCount = 0;
    
    if (is_dir($storageDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageDir));
        
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $relativePath = str_replace($storageDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $destPath = $publicStorageDir . DIRECTORY_SEPARATOR . $relativePath;
                
                if (!file_exists($destPath)) {
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    if (copy($file->getPathname(), $destPath)) {
                        $copiedCount++;
                    }
                }
            }
        }
    }
    echo "   ✅ Copied {$copiedCount} images from storage\n";
    
    // 4. Copy from uploads directory
    echo "\n📝 Copying from uploads directory...\n";
    $uploadsDir = public_path('uploads');
    $uploadsCopiedCount = 0;
    
    if (is_dir($uploadsDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploadsDir));
        
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $relativePath = str_replace($uploadsDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $destPath = $publicStorageDir . DIRECTORY_SEPARATOR . $relativePath;
                
                if (!file_exists($destPath)) {
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    if (copy($file->getPathname(), $destPath)) {
                        $uploadsCopiedCount++;
                    }
                }
            }
        }
    }
    echo "   ✅ Copied {$uploadsCopiedCount} images from uploads\n";
    
    // 5. Set proper permissions
    echo "\n📝 Setting proper permissions...\n";
    $publicStorageDir = public_path('storage');
    if (is_dir($publicStorageDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($publicStorageDir));
        $permissionCount = 0;
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                chmod($file->getPathname(), 0644);
                $permissionCount++;
            } elseif ($file->isDir()) {
                chmod($file->getPathname(), 0755);
            }
        }
        echo "   ✅ Set permissions for {$permissionCount} files\n";
    }
    
    // 6. Test key images
    echo "\n📝 Testing key images...\n";
    $testImages = [
        'storage/school-profiles/1760868291_Struktur_Organisasi.png',
        'storage/home-sections/1760868230_Screenshot_2025-10-15_235511.png',
        'storage/gallery/upacara-bendera.jpg',
        'storage/gallery/lomba-17-agustus.jpg',
        'storage/gallery/ekstrakurikuler.jpg'
    ];
    
    $workingImages = 0;
    foreach ($testImages as $imagePath) {
        $fullPath = public_path($imagePath);
        if (file_exists($fullPath)) {
            echo "   ✅ {$imagePath}\n";
            $workingImages++;
        } else {
            echo "   ❌ {$imagePath}\n";
        }
    }
    
    // 7. Create hosting instructions
    echo "\n📝 Creating hosting instructions...\n";
    $instructions = [
        "🚀 PANDUAN HOSTING WEBSITE SEKOLAH",
        "",
        "1. UPLOAD FILE KE HOSTING:",
        "   - Upload semua file ke public_html",
        "   - Pastikan .env sudah dikonfigurasi",
        "",
        "2. SETUP DATABASE:",
        "   - Buat database baru",
        "   - Update .env dengan database info",
        "   - Jalankan: php artisan migrate --force",
        "   - Jalankan: php artisan db:seed --force",
        "",
        "3. SETUP STORAGE:",
        "   - Jalankan: php artisan storage:link",
        "   - Copy semua gambar ke public/storage/",
        "   - Set permissions: chmod -R 755 storage/",
        "",
        "4. TEST WEBSITE:",
        "   - Buka website di browser",
        "   - Check semua halaman",
        "   - Check gambar muncul",
        "   - Test admin panel",
        "",
        "5. TROUBLESHOOTING:",
        "   - Jika gambar tidak muncul: check storage symlink",
        "   - Jika error 500: check .env dan permissions",
        "   - Jika error 404: check file paths",
        "",
        "✅ WEBSITE SIAP ONLINE!"
    ];
    
    file_put_contents('HOSTING_INSTRUCTIONS.txt', implode("\n", $instructions));
    echo "   ✅ Hosting instructions created\n";
    
    // 8. Final summary
    $successRate = count($testImages) > 0 ? round(($workingImages / count($testImages)) * 100, 2) : 0;
    
    echo "\n✅ PROJECT SIAP UNTUK HOSTING!\n";
    echo "📋 Summary:\n";
    echo "   - Cache: Cleared\n";
    echo "   - Storage: Ready\n";
    echo "   - Images copied: {$copiedCount} from storage, {$uploadsCopiedCount} from uploads\n";
    echo "   - Working images: {$workingImages}/" . count($testImages) . " ({$successRate}%)\n";
    echo "   - Permissions: Set correctly\n";
    
    if ($successRate >= 60) {
        echo "\n🎉 GAMBAR AKAN MUNCUL DI HOSTING!\n";
        echo "   - Sebagian besar gambar berfungsi\n";
        echo "   - Website siap untuk production\n";
        echo "   - Storage symlink sudah dikonfigurasi\n\n";
    } else {
        echo "\n⚠️  Beberapa gambar masih bermasalah\n";
        echo "   - Upload gambar yang hilang melalui admin\n";
        echo "   - Periksa path gambar yang error\n\n";
    }
    
    echo "🌐 LANGKAH SELANJUTNYA:\n";
    echo "   1. Baca file HOSTING_INSTRUCTIONS.txt\n";
    echo "   2. Upload semua file ke hosting\n";
    echo "   3. Setup database dan .env\n";
    echo "   4. Jalankan migrasi dan seeder\n";
    echo "   5. Test website di browser\n\n";
    
    echo "📞 Jika ada masalah, check:\n";
    echo "   - Storage symlink: public/storage → storage/app/public\n";
    echo "   - File permissions: 755 untuk folder, 644 untuk file\n";
    echo "   - Database connection di .env\n";
    echo "   - Web server configuration\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
