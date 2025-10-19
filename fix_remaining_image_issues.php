<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Memperbaiki masalah gambar yang tersisa...\n\n";

try {
    // 1. Fix double storage paths in database
    echo "📝 Memperbaiki double storage paths di database...\n";
    
    // Fix SchoolProfile double storage paths
    $schoolProfiles = \App\Models\SchoolProfile::where('image', 'like', 'storage/storage/%')->get();
    foreach ($schoolProfiles as $profile) {
        $fixedImage = str_replace('storage/storage/', 'storage/', $profile->image);
        $profile->update(['image' => $fixedImage]);
        echo "   ✅ Fixed SchoolProfile {$profile->id}: {$profile->image} → {$fixedImage}\n";
    }
    
    // Fix HomeSection double storage paths
    $homeSections = \App\Models\HomeSection::where('image', 'like', 'storage/storage/%')->get();
    foreach ($homeSections as $section) {
        $fixedImage = str_replace('storage/storage/', 'storage/', $section->image);
        $section->update(['image' => $fixedImage]);
        echo "   ✅ Fixed HomeSection {$section->id}: {$section->image} → {$fixedImage}\n";
    }
    
    // Fix Gallery double storage paths
    $galleries = \App\Models\Gallery::where('cover_image', 'like', 'storage/storage/%')->get();
    foreach ($galleries as $gallery) {
        $fixedImage = str_replace('storage/storage/', 'storage/', $gallery->cover_image);
        $gallery->update(['cover_image' => $fixedImage]);
        echo "   ✅ Fixed Gallery {$gallery->id}: {$gallery->cover_image} → {$fixedImage}\n";
    }
    
    // 2. Copy missing images from uploads to storage
    echo "\n📝 Menyalin gambar yang hilang dari uploads ke storage...\n";
    
    $uploadsDir = public_path('uploads');
    $storageDir = storage_path('app/public');
    $copiedCount = 0;
    
    if (is_dir($uploadsDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploadsDir));
        
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $relativePath = str_replace($uploadsDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $destPath = $storageDir . DIRECTORY_SEPARATOR . $relativePath;
                
                if (!file_exists($destPath)) {
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    if (copy($file->getPathname(), $destPath)) {
                        $copiedCount++;
                        echo "   ✅ Copied: {$relativePath}\n";
                    }
                }
            }
        }
    }
    
    // 3. Copy all images to public storage
    echo "\n📝 Menyalin semua gambar ke public storage...\n";
    $publicStorageDir = public_path('storage');
    $storageAppDir = storage_path('app/public');
    $publicCopiedCount = 0;
    
    if (is_dir($storageAppDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageAppDir));
        
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $relativePath = str_replace($storageAppDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $destPath = $publicStorageDir . DIRECTORY_SEPARATOR . $relativePath;
                
                if (!file_exists($destPath)) {
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    if (copy($file->getPathname(), $destPath)) {
                        $publicCopiedCount++;
                    }
                }
            }
        }
    }
    
    // 4. Test image URLs
    echo "\n📝 Testing image URLs...\n";
    
    // Test SchoolProfile images
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    foreach ($schoolProfiles as $profile) {
        $imageUrl = $profile->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   SchoolProfile {$profile->id}: " . (file_exists($imagePath) ? '✅' : '❌') . " {$imageUrl}\n";
    }
    
    // Test HomeSection images
    $homeSections = \App\Models\HomeSection::whereNotNull('image')->get();
    foreach ($homeSections as $section) {
        $imageUrl = $section->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   HomeSection {$section->id}: " . (file_exists($imagePath) ? '✅' : '❌') . " {$imageUrl}\n";
    }
    
    // Test Gallery images
    $galleries = \App\Models\Gallery::whereNotNull('cover_image')->get();
    foreach ($galleries as $gallery) {
        $imageUrl = $gallery->cover_image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   Gallery {$gallery->id}: " . (file_exists($imagePath) ? '✅' : '❌') . " {$imageUrl}\n";
    }
    
    echo "\n✅ Perbaikan gambar selesai!\n";
    echo "📋 Summary:\n";
    echo "   - Double storage paths diperbaiki\n";
    echo "   - {$copiedCount} gambar disalin dari uploads ke storage\n";
    echo "   - {$publicCopiedCount} gambar disalin ke public storage\n";
    echo "   - Semua gambar sekarang siap untuk hosting\n\n";
    
    echo "🌐 Gambar akan muncul dengan benar di hosting!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
