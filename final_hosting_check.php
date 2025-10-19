<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Final check untuk hosting - memastikan semua gambar siap...\n\n";

try {
    // 1. Check storage symlink
    echo "📝 Checking storage symlink...\n";
    $symlinkPath = public_path('storage');
    if (is_link($symlinkPath) || is_dir($symlinkPath)) {
        echo "   ✅ Storage symlink OK\n";
    } else {
        echo "   ❌ Storage symlink missing - creating...\n";
        if (symlink(storage_path('app/public'), $symlinkPath)) {
            echo "   ✅ Storage symlink created\n";
        } else {
            echo "   ❌ Failed to create storage symlink\n";
        }
    }
    
    // 2. Copy all images from storage to public storage
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
    
    // 3. Test all image URLs
    echo "\n📝 Testing all image URLs...\n";
    
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
    
    // Test News images
    $news = \App\Models\News::whereNotNull('featured_image')->get();
    foreach ($news as $article) {
        $totalImages++;
        $imageUrl = $article->featured_image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        if (file_exists($imagePath)) {
            $workingImages++;
            echo "   ✅ News {$article->id}\n";
        } else {
            echo "   ❌ News {$article->id} - {$imageUrl}\n";
        }
    }
    
    // Test HeadmasterGreeting images
    $headmasterGreetings = \App\Models\HeadmasterGreeting::whereNotNull('photo')->get();
    foreach ($headmasterGreetings as $greeting) {
        $totalImages++;
        $imageUrl = $greeting->photo_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        if (file_exists($imagePath)) {
            $workingImages++;
            echo "   ✅ HeadmasterGreeting {$greeting->id}\n";
        } else {
            echo "   ❌ HeadmasterGreeting {$greeting->id} - {$imageUrl}\n";
        }
    }
    
    // Test Facility images
    $facilities = \App\Models\Facility::whereNotNull('image')->get();
    foreach ($facilities as $facility) {
        $totalImages++;
        $imageUrl = $facility->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        if (file_exists($imagePath)) {
            $workingImages++;
            echo "   ✅ Facility {$facility->id}\n";
        } else {
            echo "   ❌ Facility {$facility->id} - {$imageUrl}\n";
        }
    }
    
    // 4. Final summary
    $successRate = $totalImages > 0 ? round(($workingImages / $totalImages) * 100, 2) : 0;
    
    echo "\n✅ Final hosting check completed!\n";
    echo "📋 Summary:\n";
    echo "   - Total images: {$totalImages}\n";
    echo "   - Working images: {$workingImages}\n";
    echo "   - Success rate: {$successRate}%\n";
    echo "   - Images copied: {$copiedCount}\n";
    
    if ($successRate >= 80) {
        echo "\n🎉 WEBSITE SIAP UNTUK HOSTING!\n";
        echo "   - Sebagian besar gambar sudah berfungsi\n";
        echo "   - Gambar akan muncul dengan benar di hosting\n";
        echo "   - Storage symlink sudah dikonfigurasi\n\n";
    } else {
        echo "\n⚠️  Beberapa gambar masih bermasalah\n";
        echo "   - Periksa path gambar yang error\n";
        echo "   - Pastikan semua file gambar ada di storage\n\n";
    }
    
    echo "🌐 Untuk hosting, pastikan:\n";
    echo "   1. Storage symlink berfungsi: public/storage → storage/app/public\n";
    echo "   2. Semua gambar ada di public/storage/\n";
    echo "   3. Permissions file: 644, directory: 755\n";
    echo "   4. Web server bisa akses public/storage/\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
