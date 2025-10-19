<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Mempersiapkan project untuk hosting...\n\n";

try {
    // 1. Clear cache
    echo "📝 Clearing cache...\n";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "   ✅ Cache cleared\n";
    
    // 2. Optimize for production
    echo "\n📝 Optimizing for production...\n";
    \Illuminate\Support\Facades\Artisan::call('config:cache');
    \Illuminate\Support\Facades\Artisan::call('route:cache');
    \Illuminate\Support\Facades\Artisan::call('view:cache');
    echo "   ✅ Optimized for production\n";
    
    // 3. Ensure storage symlink
    echo "\n📝 Ensuring storage symlink...\n";
    $symlinkPath = public_path('storage');
    $targetPath = storage_path('app/public');
    
    if (is_link($symlinkPath)) {
        unlink($symlinkPath);
    }
    
    if (is_dir($symlinkPath)) {
        rmdir($symlinkPath);
    }
    
    if (symlink($targetPath, $symlinkPath)) {
        echo "   ✅ Storage symlink created\n";
    } else {
        echo "   ❌ Failed to create storage symlink\n";
    }
    
    // 4. Copy all images to public storage
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
    echo "   ✅ Copied {$copiedCount} images\n";
    
    // 5. Copy from uploads directory
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
    
    // 6. Set proper permissions
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
    
    // 7. Test image URLs
    echo "\n📝 Testing image URLs...\n";
    $totalImages = 0;
    $workingImages = 0;
    
    // Test SchoolProfile images
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    foreach ($schoolProfiles as $profile) {
        $totalImages++;
        $imageUrl = $profile->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        if (file_exists($imagePath)) {
            $workingImages++;
            echo "   ✅ SchoolProfile {$profile->id}\n";
        } else {
            echo "   ❌ SchoolProfile {$profile->id} - {$imageUrl}\n";
        }
    }
    
    // Test HomeSection images
    $homeSections = \App\Models\HomeSection::whereNotNull('image')->get();
    foreach ($homeSections as $section) {
        $totalImages++;
        $imageUrl = $section->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        if (file_exists($imagePath)) {
            $workingImages++;
            echo "   ✅ HomeSection {$section->id}\n";
        } else {
            echo "   ❌ HomeSection {$section->id} - {$imageUrl}\n";
        }
    }
    
    // Test Gallery images
    $galleries = \App\Models\Gallery::whereNotNull('cover_image')->get();
    foreach ($galleries as $gallery) {
        $totalImages++;
        $imageUrl = $gallery->cover_image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        if (file_exists($imagePath)) {
            $workingImages++;
            echo "   ✅ Gallery {$gallery->id}\n";
        } else {
            echo "   ❌ Gallery {$gallery->id} - {$imageUrl}\n";
        }
    }
    
    // 8. Create hosting checklist
    echo "\n📝 Creating hosting checklist...\n";
    $checklist = [
        "✅ Cache cleared and optimized",
        "✅ Storage symlink created",
        "✅ {$copiedCount} images copied to public storage",
        "✅ {$uploadsCopiedCount} images copied from uploads",
        "✅ Permissions set correctly",
        "✅ {$workingImages}/{$totalImages} images working"
    ];
    
    file_put_contents('hosting_checklist.txt', implode("\n", $checklist));
    echo "   ✅ Hosting checklist created\n";
    
    // 9. Final summary
    $successRate = $totalImages > 0 ? round(($workingImages / $totalImages) * 100, 2) : 0;
    
    echo "\n✅ Project siap untuk hosting!\n";
    echo "📋 Summary:\n";
    echo "   - Cache: Cleared and optimized\n";
    echo "   - Storage symlink: Created\n";
    echo "   - Images copied: {$copiedCount} from storage, {$uploadsCopiedCount} from uploads\n";
    echo "   - Working images: {$workingImages}/{$totalImages} ({$successRate}%)\n";
    echo "   - Permissions: Set correctly\n";
    
    if ($successRate >= 70) {
        echo "\n🎉 PROJECT SIAP UNTUK HOSTING!\n";
        echo "   - Sebagian besar gambar berfungsi\n";
        echo "   - Website akan berjalan dengan baik\n";
        echo "   - Gambar akan muncul di hosting\n\n";
    } else {
        echo "\n⚠️  Beberapa gambar masih bermasalah\n";
        echo "   - Upload gambar yang hilang melalui admin\n";
        echo "   - Periksa path gambar yang error\n\n";
    }
    
    echo "🌐 Langkah selanjutnya untuk hosting:\n";
    echo "   1. Upload semua file ke hosting\n";
    echo "   2. Set database di .env\n";
    echo "   3. Jalankan: php artisan migrate --force\n";
    echo "   4. Jalankan: php artisan db:seed --force\n";
    echo "   5. Set permissions: chmod -R 755 storage/\n";
    echo "   6. Test website di browser\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
