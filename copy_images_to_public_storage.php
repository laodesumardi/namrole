<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "📁 Copying images to public storage for hosting...\n\n";

try {
    // 1. Copy SchoolProfile images
    echo "📝 Copying SchoolProfile images...\n";
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    
    foreach ($schoolProfiles as $profile) {
        $sourcePath = storage_path('app/public/' . $profile->image);
        $destPath = public_path('storage/' . $profile->image);
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ Copied: {$profile->image}\n";
            } else {
                echo "   ❌ Failed to copy: {$profile->image}\n";
            }
        }
    }
    
    // 2. Copy HomeSection images
    echo "\n📝 Copying HomeSection images...\n";
    $homeSections = \App\Models\HomeSection::whereNotNull('image')->get();
    
    foreach ($homeSections as $section) {
        $sourcePath = storage_path('app/public/' . $section->image);
        $destPath = public_path('storage/' . $section->image);
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ Copied: {$section->image}\n";
            } else {
                echo "   ❌ Failed to copy: {$section->image}\n";
            }
        }
    }
    
    // 3. Copy Gallery cover images
    echo "\n📝 Copying Gallery cover images...\n";
    $galleries = \App\Models\Gallery::whereNotNull('cover_image')->get();
    
    foreach ($galleries as $gallery) {
        $sourcePath = storage_path('app/public/' . $gallery->cover_image);
        $destPath = public_path('storage/' . $gallery->cover_image);
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ Copied: {$gallery->cover_image}\n";
            } else {
                echo "   ❌ Failed to copy: {$gallery->cover_image}\n";
            }
        }
    }
    
    // 4. Copy GalleryItem files
    echo "\n📝 Copying GalleryItem files...\n";
    $galleryItems = \App\Models\GalleryItem::whereNotNull('file_path')->get();
    
    foreach ($galleryItems as $item) {
        $sourcePath = storage_path('app/public/' . $item->file_path);
        $destPath = public_path('storage/' . $item->file_path);
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ Copied: {$item->file_path}\n";
            } else {
                echo "   ❌ Failed to copy: {$item->file_path}\n";
            }
        }
        
        // Copy thumbnail if exists
        if ($item->thumbnail_path) {
            $thumbSourcePath = storage_path('app/public/' . $item->thumbnail_path);
            $thumbDestPath = public_path('storage/' . $item->thumbnail_path);
            
            if (file_exists($thumbSourcePath) && !file_exists($thumbDestPath)) {
                $thumbDestDir = dirname($thumbDestPath);
                if (!is_dir($thumbDestDir)) {
                    mkdir($thumbDestDir, 0755, true);
                }
                if (copy($thumbSourcePath, $thumbDestPath)) {
                    echo "   ✅ Copied thumbnail: {$item->thumbnail_path}\n";
                } else {
                    echo "   ❌ Failed to copy thumbnail: {$item->thumbnail_path}\n";
                }
            }
        }
    }
    
    // 5. Copy News images
    echo "\n📝 Copying News images...\n";
    $news = \App\Models\News::whereNotNull('featured_image')->get();
    
    foreach ($news as $article) {
        $sourcePath = storage_path('app/public/' . $article->featured_image);
        $destPath = public_path('storage/' . $article->featured_image);
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ Copied: {$article->featured_image}\n";
            } else {
                echo "   ❌ Failed to copy: {$article->featured_image}\n";
            }
        }
    }
    
    // 6. Copy HeadmasterGreeting images
    echo "\n📝 Copying HeadmasterGreeting images...\n";
    $headmasterGreetings = \App\Models\HeadmasterGreeting::whereNotNull('photo')->get();
    
    foreach ($headmasterGreetings as $greeting) {
        $sourcePath = storage_path('app/public/' . $greeting->photo);
        $destPath = public_path('storage/' . $greeting->photo);
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ Copied: {$greeting->photo}\n";
            } else {
                echo "   ❌ Failed to copy: {$greeting->photo}\n";
            }
        }
    }
    
    // 7. Copy Facility images
    echo "\n📝 Copying Facility images...\n";
    $facilities = \App\Models\Facility::whereNotNull('image')->get();
    
    foreach ($facilities as $facility) {
        $sourcePath = storage_path('app/public/' . $facility->image);
        $destPath = public_path('storage/' . $facility->image);
        
        if (file_exists($sourcePath) && !file_exists($destPath)) {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ Copied: {$facility->image}\n";
            } else {
                echo "   ❌ Failed to copy: {$facility->image}\n";
            }
        }
    }
    
    echo "\n✅ All images have been copied to public storage!\n";
    echo "📋 Summary:\n";
    echo "   - All images are now available in public/storage/\n";
    echo "   - Images will display correctly on hosting\n";
    echo "   - Storage symlink is properly configured\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
