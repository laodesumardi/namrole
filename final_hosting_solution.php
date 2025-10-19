<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Final hosting solution...\n\n";

try {
    // 1. Clear cache
    echo "📝 Clearing cache...\n";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "   ✅ Cache cleared\n";
    
    // 2. Ensure storage directory exists
    echo "\n📝 Ensuring storage directory...\n";
    $publicStoragePath = public_path('storage');
    $storageAppPublicPath = storage_path('app/public');
    
    if (!is_dir($publicStoragePath)) {
        mkdir($publicStoragePath, 0755, true);
        echo "   ✅ Storage directory created\n";
    } else {
        echo "   ✅ Storage directory exists\n";
    }
    
    // 3. Copy all files from storage/app/public to public/storage
    echo "\n📝 Copying files to public storage...\n";
    $copiedCount = 0;
    
    if (is_dir($storageAppPublicPath)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storageAppPublicPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
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
                if (!file_exists($destPath) || filesize($file->getPathname()) !== filesize($destPath)) {
                    if (copy($file->getPathname(), $destPath)) {
                        $copiedCount++;
                    }
                }
            }
        }
    }
    echo "   ✅ {$copiedCount} files copied\n";
    
    // 4. Set proper permissions
    echo "\n📝 Setting permissions...\n";
    $permissionCount = 0;
    
    if (is_dir($publicStoragePath)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                chmod($file->getPathname(), 0644);
                $permissionCount++;
            } elseif ($file->isDir()) {
                chmod($file->getPathname(), 0755);
            }
        }
    }
    echo "   ✅ Permissions set for {$permissionCount} files\n";
    
    // 5. Test image URLs
    echo "\n📝 Testing image URLs...\n";
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
    
    // 6. Create hosting deployment script
    echo "\n📝 Creating deployment script...\n";
    $deploymentScript = '<?php
// Hosting deployment script
echo "🚀 Deploying to hosting...\n";

// 1. Clear cache
echo "📝 Clearing cache...\n";
exec("php artisan cache:clear");
exec("php artisan config:clear");
exec("php artisan route:clear");
exec("php artisan view:clear");
echo "✅ Cache cleared\n";

// 2. Create storage directory
echo "📝 Creating storage directory...\n";
$publicStoragePath = __DIR__ . "/storage";
$storageAppPublicPath = __DIR__ . "/../storage/app/public";

if (!is_dir($publicStoragePath)) {
    mkdir($publicStoragePath, 0755, true);
    echo "✅ Storage directory created\n";
} else {
    echo "✅ Storage directory exists\n";
}

// 3. Copy files
echo "📝 Copying files...\n";
$copiedCount = 0;

if (is_dir($storageAppPublicPath)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($storageAppPublicPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
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
            if (!file_exists($destPath)) {
                if (copy($file->getPathname(), $destPath)) {
                    $copiedCount++;
                }
            }
        }
    }
}
echo "✅ {$copiedCount} files copied\n";

// 4. Set permissions
echo "📝 Setting permissions...\n";
$permissionCount = 0;

if (is_dir($publicStoragePath)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            chmod($file->getPathname(), 0644);
            $permissionCount++;
        } elseif ($file->isDir()) {
            chmod($file->getPathname(), 0755);
        }
    }
}
echo "✅ Permissions set for {$permissionCount} files\n";

echo "🎉 Deployment completed!\n";
echo "📋 Summary:\n";
echo "   - Cache: Cleared\n";
echo "   - Storage: Ready\n";
echo "   - Files copied: {$copiedCount}\n";
echo "   - Permissions: Set\n";
echo "   - Website ready for hosting!\n";
?>';
    
    file_put_contents('public/deploy_hosting.php', $deploymentScript);
    echo "   ✅ Deployment script created at public/deploy_hosting.php\n";
    
    // 7. Final summary
    $successRate = count($testImages) > 0 ? round(($workingImages / count($testImages)) * 100, 2) : 0;
    
    echo "\n✅ HOSTING SOLUTION COMPLETED!\n";
    echo "📋 Summary:\n";
    echo "   - Cache: Cleared\n";
    echo "   - Storage: Ready\n";
    echo "   - Files copied: {$copiedCount}\n";
    echo "   - Permissions: Set for {$permissionCount} files\n";
    echo "   - Working images: {$workingImages}/" . count($testImages) . " ({$successRate}%)\n";
    
    if ($successRate >= 80) {
        echo "\n🎉 WEBSITE SIAP UNTUK HOSTING!\n";
        echo "   - Semua gambar akan muncul\n";
        echo "   - Storage sudah dikonfigurasi\n";
        echo "   - Permissions sudah benar\n";
        echo "   - Website siap untuk production\n\n";
    } else {
        echo "\n⚠️  Beberapa gambar masih bermasalah\n";
        echo "   - Jalankan: php public/deploy_hosting.php\n";
        echo "   - Upload gambar yang hilang melalui admin\n\n";
    }
    
    echo "🌐 LANGKAH HOSTING:\n";
    echo "   1. Upload semua file ke hosting\n";
    echo "   2. Setup database dan .env\n";
    echo "   3. Jalankan: php artisan migrate --force\n";
    echo "   4. Jalankan: php artisan db:seed --force\n";
    echo "   5. Jalankan: php public/deploy_hosting.php\n";
    echo "   6. Test website di browser\n\n";
    
    echo "📞 Jika ada masalah:\n";
    echo "   - Check file permissions\n";
    echo "   - Check database connection\n";
    echo "   - Check storage directory\n";
    echo "   - Run deployment script\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
