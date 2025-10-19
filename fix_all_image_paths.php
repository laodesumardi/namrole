<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Fixing all image paths for hosting...\n\n";

try {
    // 1. Fix SchoolProfile images
    echo "📝 Fixing SchoolProfile images...\n";
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    
    foreach ($schoolProfiles as $profile) {
        $originalImage = $profile->image;
        $fixedImage = $originalImage;
        
        // Fix double storage paths
        if (str_contains($originalImage, 'storage/storage/')) {
            $fixedImage = str_replace('storage/storage/', 'storage/', $originalImage);
        }
        
        // Fix uploads/school-profiles/ to school-profiles/
        if (str_starts_with($originalImage, 'uploads/school-profiles/')) {
            $fixedImage = str_replace('uploads/school-profiles/', 'school-profiles/', $originalImage);
        }
        
        // Fix storage/school-profiles/ to school-profiles/
        if (str_starts_with($originalImage, 'storage/school-profiles/')) {
            $fixedImage = str_replace('storage/school-profiles/', 'school-profiles/', $originalImage);
        }
        
        if ($originalImage !== $fixedImage) {
            $profile->update(['image' => $fixedImage]);
            echo "   ✅ Fixed: {$originalImage} → {$fixedImage}\n";
        }
    }
    
    // 2. Fix HomeSection images
    echo "\n📝 Fixing HomeSection images...\n";
    $homeSections = \App\Models\HomeSection::whereNotNull('image')->get();
    
    foreach ($homeSections as $section) {
        $originalImage = $section->image;
        $fixedImage = $originalImage;
        
        // Fix double storage paths
        if (str_contains($originalImage, 'storage/storage/')) {
            $fixedImage = str_replace('storage/storage/', 'storage/', $originalImage);
        }
        
        // Fix public/ prefix
        if (str_starts_with($originalImage, 'public/')) {
            $fixedImage = str_replace('public/', '', $originalImage);
        }
        
        // Ensure it starts with home-sections/
        if (!str_starts_with($fixedImage, 'home-sections/') && !str_starts_with($fixedImage, 'storage/')) {
            $fixedImage = 'home-sections/' . $fixedImage;
        }
        
        if ($originalImage !== $fixedImage) {
            $section->update(['image' => $fixedImage]);
            echo "   ✅ Fixed: {$originalImage} → {$fixedImage}\n";
        }
    }
    
    // 3. Fix Gallery cover images
    echo "\n📝 Fixing Gallery cover images...\n";
    $galleries = \App\Models\Gallery::whereNotNull('cover_image')->get();
    
    foreach ($galleries as $gallery) {
        $originalImage = $gallery->cover_image;
        $fixedImage = $originalImage;
        
        // Fix double storage paths
        if (str_contains($originalImage, 'storage/storage/')) {
            $fixedImage = str_replace('storage/storage/', 'storage/', $originalImage);
        }
        
        // Fix uploads/gallery/ to gallery/
        if (str_starts_with($originalImage, 'uploads/gallery/')) {
            $fixedImage = str_replace('uploads/gallery/', 'gallery/', $originalImage);
        }
        
        // Fix storage/gallery/ to gallery/
        if (str_starts_with($originalImage, 'storage/gallery/')) {
            $fixedImage = str_replace('storage/gallery/', 'gallery/', $originalImage);
        }
        
        if ($originalImage !== $fixedImage) {
            $gallery->update(['cover_image' => $fixedImage]);
            echo "   ✅ Fixed: {$originalImage} → {$fixedImage}\n";
        }
    }
    
    // 4. Fix GalleryItem file paths
    echo "\n📝 Fixing GalleryItem file paths...\n";
    $galleryItems = \App\Models\GalleryItem::whereNotNull('file_path')->get();
    
    foreach ($galleryItems as $item) {
        $originalPath = $item->file_path;
        $fixedPath = $originalPath;
        
        // Fix double storage paths
        if (str_contains($originalPath, 'storage/storage/')) {
            $fixedPath = str_replace('storage/storage/', 'storage/', $originalPath);
        }
        
        // Fix uploads/gallery-items/ to gallery-items/
        if (str_starts_with($originalPath, 'uploads/gallery-items/')) {
            $fixedPath = str_replace('uploads/gallery-items/', 'gallery-items/', $originalPath);
        }
        
        // Fix storage/gallery-items/ to gallery-items/
        if (str_starts_with($originalPath, 'storage/gallery-items/')) {
            $fixedPath = str_replace('storage/gallery-items/', 'gallery-items/', $originalPath);
        }
        
        if ($originalPath !== $fixedPath) {
            $item->update(['file_path' => $fixedPath]);
            echo "   ✅ Fixed: {$originalPath} → {$fixedPath}\n";
        }
    }
    
    // 4b. Fix GalleryItem thumbnail paths
    echo "\n📝 Fixing GalleryItem thumbnail paths...\n";
    $galleryItems = \App\Models\GalleryItem::whereNotNull('thumbnail_path')->get();
    
    foreach ($galleryItems as $item) {
        $originalThumbnail = $item->thumbnail_path;
        $fixedThumbnail = $originalThumbnail;
        
        // Fix double storage paths
        if (str_contains($originalThumbnail, 'storage/storage/')) {
            $fixedThumbnail = str_replace('storage/storage/', 'storage/', $originalThumbnail);
        }
        
        // Fix uploads/gallery-items/ to gallery-items/
        if (str_starts_with($originalThumbnail, 'uploads/gallery-items/')) {
            $fixedThumbnail = str_replace('uploads/gallery-items/', 'gallery-items/', $originalThumbnail);
        }
        
        // Fix storage/gallery-items/ to gallery-items/
        if (str_starts_with($originalThumbnail, 'storage/gallery-items/')) {
            $fixedThumbnail = str_replace('storage/gallery-items/', 'gallery-items/', $originalThumbnail);
        }
        
        if ($originalThumbnail !== $fixedThumbnail) {
            $item->update(['thumbnail_path' => $fixedThumbnail]);
            echo "   ✅ Fixed: {$originalThumbnail} → {$fixedThumbnail}\n";
        }
    }
    
    // 5. Fix News images
    echo "\n📝 Fixing News images...\n";
    $news = \App\Models\News::whereNotNull('featured_image')->get();
    
    foreach ($news as $article) {
        $originalImage = $article->featured_image;
        $fixedImage = $originalImage;
        
        // Fix double storage paths
        if (str_contains($originalImage, 'storage/storage/')) {
            $fixedImage = str_replace('storage/storage/', 'storage/', $originalImage);
        }
        
        // Fix uploads/news/ to news/
        if (str_starts_with($originalImage, 'uploads/news/')) {
            $fixedImage = str_replace('uploads/news/', 'news/', $originalImage);
        }
        
        // Fix storage/news/ to news/
        if (str_starts_with($originalImage, 'storage/news/')) {
            $fixedImage = str_replace('storage/news/', 'news/', $originalImage);
        }
        
        if ($originalImage !== $fixedImage) {
            $article->update(['featured_image' => $fixedImage]);
            echo "   ✅ Fixed: {$originalImage} → {$fixedImage}\n";
        }
    }
    
    // 6. Fix HeadmasterGreeting images
    echo "\n📝 Fixing HeadmasterGreeting images...\n";
    $headmasterGreetings = \App\Models\HeadmasterGreeting::whereNotNull('photo')->get();
    
    foreach ($headmasterGreetings as $greeting) {
        $originalImage = $greeting->photo;
        $fixedImage = $originalImage;
        
        // Fix double storage paths
        if (str_contains($originalImage, 'storage/storage/')) {
            $fixedImage = str_replace('storage/storage/', 'storage/', $originalImage);
        }
        
        // Fix uploads/headmaster-greetings/ to headmaster-greetings/
        if (str_starts_with($originalImage, 'uploads/headmaster-greetings/')) {
            $fixedImage = str_replace('uploads/headmaster-greetings/', 'headmaster-greetings/', $originalImage);
        }
        
        // Fix storage/headmaster-greetings/ to headmaster-greetings/
        if (str_starts_with($originalImage, 'storage/headmaster-greetings/')) {
            $fixedImage = str_replace('storage/headmaster-greetings/', 'headmaster-greetings/', $originalImage);
        }
        
        if ($originalImage !== $fixedImage) {
            $greeting->update(['photo' => $fixedImage]);
            echo "   ✅ Fixed: {$originalImage} → {$fixedImage}\n";
        }
    }
    
    // 7. Fix Facility images
    echo "\n📝 Fixing Facility images...\n";
    $facilities = \App\Models\Facility::whereNotNull('image')->get();
    
    foreach ($facilities as $facility) {
        $originalImage = $facility->image;
        $fixedImage = $originalImage;
        
        // Fix double storage paths
        if (str_contains($originalImage, 'storage/storage/')) {
            $fixedImage = str_replace('storage/storage/', 'storage/', $originalImage);
        }
        
        // Fix uploads/facilities/ to facilities/
        if (str_starts_with($originalImage, 'uploads/facilities/')) {
            $fixedImage = str_replace('uploads/facilities/', 'facilities/', $originalImage);
        }
        
        // Fix storage/facilities/ to facilities/
        if (str_starts_with($originalImage, 'storage/facilities/')) {
            $fixedImage = str_replace('storage/facilities/', 'facilities/', $originalImage);
        }
        
        if ($originalImage !== $fixedImage) {
            $facility->update(['image' => $fixedImage]);
            echo "   ✅ Fixed: {$originalImage} → {$fixedImage}\n";
        }
    }
    
    echo "\n✅ All image paths have been fixed!\n";
    echo "📋 Summary:\n";
    echo "   - Fixed double storage paths (storage/storage/ → storage/)\n";
    echo "   - Fixed uploads/ paths to proper storage paths\n";
    echo "   - Ensured consistent path formatting\n";
    echo "   - All images should now display correctly on hosting\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
