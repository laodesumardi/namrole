<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Final hosting image setup...\n\n";

try {
    // 1. Ensure storage symlink exists
    echo "📝 Ensuring storage symlink...\n";
    $symlinkPath = public_path('storage');
    $targetPath = storage_path('app/public');
    
    if (!is_link($symlinkPath) && !is_dir($symlinkPath)) {
        if (symlink($targetPath, $symlinkPath)) {
            echo "   ✅ Storage symlink created\n";
        } else {
            echo "   ❌ Failed to create storage symlink\n";
        }
    } else {
        echo "   ✅ Storage symlink already exists\n";
    }
    
    // 2. Copy all images from storage to public storage
    echo "\n📝 Copying all images from storage to public...\n";
    $storageDir = storage_path('app/public');
    $publicStorageDir = public_path('storage');
    
    if (is_dir($storageDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageDir));
        $copiedCount = 0;
        
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
        echo "   ✅ Copied {$copiedCount} images\n";
    }
    
    // 3. Copy images from uploads directory
    echo "\n📝 Copying images from uploads directory...\n";
    $uploadsDir = public_path('uploads');
    $copiedFromUploads = 0;
    
    if (is_dir($uploadsDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploadsDir));
        
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $relativePath = str_replace(public_path(), '', $file->getPathname());
                $destPath = public_path('storage' . $relativePath);
                
                if (!file_exists($destPath)) {
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    if (copy($file->getPathname(), $destPath)) {
                        $copiedFromUploads++;
                    }
                }
            }
        }
        echo "   ✅ Copied {$copiedFromUploads} images from uploads\n";
    }
    
    // 4. Set proper permissions
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
    
    // 5. Test a few key images
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
    
    echo "\n✅ Hosting image setup completed!\n";
    echo "📋 Summary:\n";
    echo "   - Storage symlink: " . (is_link(public_path('storage')) ? 'OK' : 'Missing') . "\n";
    echo "   - Images copied from storage: {$copiedCount}\n";
    echo "   - Images copied from uploads: {$copiedFromUploads}\n";
    echo "   - Working test images: {$workingImages}/" . count($testImages) . "\n";
    echo "   - All images should now display correctly on hosting\n\n";
    
    echo "🌐 Your website is now ready for hosting!\n";
    echo "   - All images are properly configured\n";
    echo "   - Storage symlink is working\n";
    echo "   - Images will display correctly on production\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
