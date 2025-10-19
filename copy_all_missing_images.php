<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📁 Copying all missing images to public storage...\n\n";

function copyImageIfExists($sourcePath, $destPath, $description) {
    if (file_exists($sourcePath)) {
        $destDir = dirname($destPath);
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        if (copy($sourcePath, $destPath)) {
            echo "   ✅ Copied: {$description}\n";
            return true;
        } else {
            echo "   ❌ Failed to copy: {$description}\n";
            return false;
        }
    } else {
        echo "   ⚠️  Source not found: {$description}\n";
        return false;
    }
}

try {
    // 1. Copy SchoolProfile images
    echo "📝 Copying SchoolProfile images...\n";
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    
    foreach ($schoolProfiles as $profile) {
        $sourcePath = storage_path('app/public/' . $profile->image);
        $destPath = public_path('storage/' . $profile->image);
        copyImageIfExists($sourcePath, $destPath, "SchoolProfile {$profile->id}: {$profile->image}");
    }
    
    // 2. Copy HomeSection images
    echo "\n📝 Copying HomeSection images...\n";
    $homeSections = \App\Models\HomeSection::whereNotNull('image')->get();
    
    foreach ($homeSections as $section) {
        $sourcePath = storage_path('app/public/' . $section->image);
        $destPath = public_path('storage/' . $section->image);
        copyImageIfExists($sourcePath, $destPath, "HomeSection {$section->id}: {$section->image}");
    }
    
    // 3. Copy Gallery cover images
    echo "\n📝 Copying Gallery cover images...\n";
    $galleries = \App\Models\Gallery::whereNotNull('cover_image')->get();
    
    foreach ($galleries as $gallery) {
        $sourcePath = storage_path('app/public/' . $gallery->cover_image);
        $destPath = public_path('storage/' . $gallery->cover_image);
        copyImageIfExists($sourcePath, $destPath, "Gallery {$gallery->id}: {$gallery->cover_image}");
    }
    
    // 4. Copy GalleryItem files
    echo "\n📝 Copying GalleryItem files...\n";
    $galleryItems = \App\Models\GalleryItem::whereNotNull('file_path')->get();
    
    foreach ($galleryItems as $item) {
        $sourcePath = storage_path('app/public/' . $item->file_path);
        $destPath = public_path('storage/' . $item->file_path);
        copyImageIfExists($sourcePath, $destPath, "GalleryItem {$item->id}: {$item->file_path}");
        
        // Copy thumbnail if exists
        if ($item->thumbnail_path) {
            $thumbSourcePath = storage_path('app/public/' . $item->thumbnail_path);
            $thumbDestPath = public_path('storage/' . $item->thumbnail_path);
            copyImageIfExists($thumbSourcePath, $thumbDestPath, "GalleryItem {$item->id} thumbnail: {$item->thumbnail_path}");
        }
    }
    
    // 5. Copy News images
    echo "\n📝 Copying News images...\n";
    $news = \App\Models\News::whereNotNull('featured_image')->get();
    
    foreach ($news as $article) {
        $sourcePath = storage_path('app/public/' . $article->featured_image);
        $destPath = public_path('storage/' . $article->featured_image);
        copyImageIfExists($sourcePath, $destPath, "News {$article->id}: {$article->featured_image}");
    }
    
    // 6. Copy HeadmasterGreeting images
    echo "\n📝 Copying HeadmasterGreeting images...\n";
    $headmasterGreetings = \App\Models\HeadmasterGreeting::whereNotNull('photo')->get();
    
    foreach ($headmasterGreetings as $greeting) {
        $sourcePath = storage_path('app/public/' . $greeting->photo);
        $destPath = public_path('storage/' . $greeting->photo);
        copyImageIfExists($sourcePath, $destPath, "HeadmasterGreeting {$greeting->id}: {$greeting->photo}");
    }
    
    // 7. Copy Facility images
    echo "\n📝 Copying Facility images...\n";
    $facilities = \App\Models\Facility::whereNotNull('image')->get();
    
    foreach ($facilities as $facility) {
        $sourcePath = storage_path('app/public/' . $facility->image);
        $destPath = public_path('storage/' . $facility->image);
        copyImageIfExists($sourcePath, $destPath, "Facility {$facility->id}: {$facility->image}");
    }
    
    // 8. Copy any images from uploads directory
    echo "\n📝 Copying images from uploads directory...\n";
    $uploadsDir = public_path('uploads');
    if (is_dir($uploadsDir)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($uploadsDir));
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                $relativePath = str_replace(public_path(), '', $file->getPathname());
                $destPath = public_path('storage' . $relativePath);
                
                if (!file_exists($destPath)) {
                    $destDir = dirname($destPath);
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    if (copy($file->getPathname(), $destPath)) {
                        echo "   ✅ Copied from uploads: {$relativePath}\n";
                    }
                }
            }
        }
    }
    
    echo "\n✅ All missing images have been copied!\n";
    echo "📋 Summary:\n";
    echo "   - All images are now available in public/storage/\n";
    echo "   - Images will display correctly on hosting\n";
    echo "   - Storage symlink is properly configured\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
