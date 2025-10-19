<?php

echo "🔧 Direct Gallery Images Fix\n";
echo "===========================\n\n";

// 1. Check if we're in Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: Not in Laravel project directory\n";
    echo "Please run this script from your Laravel project root\n";
    exit(1);
}

echo "✅ Laravel project detected\n";

// 2. Bootstrap Laravel
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Error bootstrapping Laravel: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Create storage link manually
echo "\n🔗 Creating storage link manually...\n";

$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

// Remove existing link if it exists
if (is_link($storageLink)) {
    unlink($storageLink);
    echo "   🔧 Removed existing storage link\n";
}

// Create manual storage directory
if (!is_dir($storageLink)) {
    mkdir($storageLink, 0755, true);
    echo "   ✅ Created public/storage directory\n";
}

// Copy all files from storage/app/public to public/storage
if (is_dir('storage/app/public')) {
    copyDirectory('storage/app/public', 'public/storage');
    echo "   ✅ Copied all storage files to public storage\n";
}

// 4. Fix Gallery model
echo "\n📝 Fixing Gallery model...\n";

$galleryModelPath = 'app/Models/Gallery.php';
if (file_exists($galleryModelPath)) {
    $modelContent = file_get_contents($galleryModelPath);
    
    // Add image URL accessor if not exists
    if (strpos($modelContent, 'getImageUrlAttribute') === false) {
        $newAccessor = '
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-gallery.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'gallery/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        if (str_starts_with($this->image, \'storage/\')) {
            return asset($this->image);
        }
        
        if (!str_starts_with($this->image, \'gallery/\') && 
            !str_starts_with($this->image, \'storage/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        return asset(\'images/default-gallery.png\');
    }';
        
        // Add before the last closing brace
        $modelContent = str_replace('}', $newAccessor . "\n}", $modelContent);
        file_put_contents($galleryModelPath, $modelContent);
        echo "   ✅ Added image URL accessor to Gallery model\n";
    } else {
        echo "   ✅ Gallery model already has image URL accessor\n";
    }
} else {
    echo "   ❌ Gallery model not found\n";
}

// 5. Fix GalleryItem model
echo "\n📝 Fixing GalleryItem model...\n";

$galleryItemModelPath = 'app/Models/GalleryItem.php';
if (file_exists($galleryItemModelPath)) {
    $modelContent = file_get_contents($galleryItemModelPath);
    
    // Add image URL accessor if not exists
    if (strpos($modelContent, 'getImageUrlAttribute') === false) {
        $newAccessor = '
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-gallery-item.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'gallery-items/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        if (str_starts_with($this->image, \'storage/\')) {
            return asset($this->image);
        }
        
        if (!str_starts_with($this->image, \'gallery-items/\') && 
            !str_starts_with($this->image, \'storage/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        return asset(\'images/default-gallery-item.png\');
    }';
        
        // Add before the last closing brace
        $modelContent = str_replace('}', $newAccessor . "\n}", $modelContent);
        file_put_contents($galleryItemModelPath, $modelContent);
        echo "   ✅ Added image URL accessor to GalleryItem model\n";
    } else {
        echo "   ✅ GalleryItem model already has image URL accessor\n";
    }
} else {
    echo "   ❌ GalleryItem model not found\n";
}

// 6. Fix gallery edit view
echo "\n📝 Fixing gallery edit view...\n";

$galleryEditViewPath = 'resources/views/admin/gallery/edit.blade.php';
if (file_exists($galleryEditViewPath)) {
    $viewContent = file_get_contents($galleryEditViewPath);
    
    // Replace image display with proper URL generation
    $oldImageDisplay = '{{ $gallery->image }}';
    $newImageDisplay = '{{ $gallery->image_url }}';
    $viewContent = str_replace($oldImageDisplay, $newImageDisplay, $viewContent);
    
    // Add onerror fallback
    $imageTagPattern = '/<img([^>]*?)src="([^"]*?)"([^>]*?)>/';
    $viewContent = preg_replace_callback($imageTagPattern, function($matches) {
        $fullTag = $matches[0];
        if (strpos($fullTag, 'onerror') === false) {
            return str_replace('>', ' onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'">', $fullTag);
        }
        return $fullTag;
    }, $viewContent);
    
    file_put_contents($galleryEditViewPath, $viewContent);
    echo "   ✅ Fixed gallery edit view\n";
} else {
    echo "   ❌ Gallery edit view not found\n";
}

// 7. Fix gallery index view
echo "\n📝 Fixing gallery index view...\n";

$galleryIndexViewPath = 'resources/views/admin/gallery/index.blade.php';
if (file_exists($galleryIndexViewPath)) {
    $viewContent = file_get_contents($galleryIndexViewPath);
    
    // Replace image display with proper URL generation
    $oldImageDisplay = '{{ $gallery->image }}';
    $newImageDisplay = '{{ $gallery->image_url }}';
    $viewContent = str_replace($oldImageDisplay, $newImageDisplay, $viewContent);
    
    // Add onerror fallback
    $imageTagPattern = '/<img([^>]*?)src="([^"]*?)"([^>]*?)>/';
    $viewContent = preg_replace_callback($imageTagPattern, function($matches) {
        $fullTag = $matches[0];
        if (strpos($fullTag, 'onerror') === false) {
            return str_replace('>', ' onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'">', $fullTag);
        }
        return $fullTag;
    }, $viewContent);
    
    file_put_contents($galleryIndexViewPath, $viewContent);
    echo "   ✅ Fixed gallery index view\n";
} else {
    echo "   ❌ Gallery index view not found\n";
}

// 8. Fix gallery items views
echo "\n📝 Fixing gallery items views...\n";

$galleryItemViews = [
    'resources/views/admin/gallery-items/edit.blade.php',
    'resources/views/admin/gallery-items/index.blade.php',
    'resources/views/admin/gallery-items/create.blade.php'
];

foreach ($galleryItemViews as $viewPath) {
    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);
        
        // Replace image display with proper URL generation
        $oldImageDisplay = '{{ $item->image }}';
        $newImageDisplay = '{{ $item->image_url }}';
        $viewContent = str_replace($oldImageDisplay, $newImageDisplay, $viewContent);
        
        // Add onerror fallback
        $imageTagPattern = '/<img([^>]*?)src="([^"]*?)"([^>]*?)>/';
        $viewContent = preg_replace_callback($imageTagPattern, function($matches) {
            $fullTag = $matches[0];
            if (strpos($fullTag, 'onerror') === false) {
                return str_replace('>', ' onerror="this.src=\'{{ asset(\'images/default-gallery-item.png\') }}\'">', $fullTag);
            }
            return $fullTag;
        }, $viewContent);
        
        file_put_contents($viewPath, $viewContent);
        echo "   ✅ Fixed view: " . basename($viewPath) . "\n";
    } else {
        echo "   ⚠️  View not found: " . basename($viewPath) . "\n";
    }
}

// 9. Create default images
echo "\n🖼️  Creating default images...\n";

$defaultImages = [
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-gallery-item.png' => 'Default gallery item image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        echo "   🔧 Creating {$description}...\n";
        
        // Create a simple default image (300x200 PNG)
        $image = imagecreate(300, 200);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 100, 100, 100);
        $borderColor = imagecolorallocate($image, 200, 200, 200);
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Add border
        imagerectangle($image, 0, 0, 299, 199, $borderColor);
        
        // Add text
        $text = 'Default Image';
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = (300 - $textWidth) / 2;
        $y = (200 - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $textColor);
        
        if (imagepng($image, $path)) {
            echo "   ✅ Created: {$path}\n";
        } else {
            echo "   ❌ Failed to create: {$path}\n";
        }
        
        imagedestroy($image);
    } else {
        echo "   ✅ Already exists: {$path}\n";
    }
}

// 10. Fix database paths
echo "\n📊 Fixing database paths...\n";

try {
    // Fix gallery image paths
    $galleries = DB::table('galleries')->get();
    echo "   📊 Found " . count($galleries) . " galleries\n";
    
    $fixedGalleries = 0;
    foreach ($galleries as $gallery) {
        if ($gallery->image) {
            $cleanPath = $gallery->image;
            
            // Remove 'storage/' prefix if exists
            if (strpos($cleanPath, 'storage/') === 0) {
                $cleanPath = substr($cleanPath, 8);
                DB::table('galleries')
                    ->where('id', $gallery->id)
                    ->update(['image' => $cleanPath]);
                echo "   🔧 Fixed gallery ID {$gallery->id}: {$cleanPath}\n";
                $fixedGalleries++;
            }
        }
    }
    
    // Fix gallery item image paths
    $galleryItems = DB::table('gallery_items')->get();
    echo "   📊 Found " . count($galleryItems) . " gallery items\n";
    
    $fixedItems = 0;
    foreach ($galleryItems as $item) {
        if ($item->image) {
            $cleanPath = $item->image;
            
            // Remove 'storage/' prefix if exists
            if (strpos($cleanPath, 'storage/') === 0) {
                $cleanPath = substr($cleanPath, 8);
                DB::table('gallery_items')
                    ->where('id', $item->id)
                    ->update(['image' => $cleanPath]);
                echo "   🔧 Fixed item ID {$item->id}: {$cleanPath}\n";
                $fixedItems++;
            }
        }
    }
    
    echo "\n   📊 Database Fix Summary:\n";
    echo "   - Galleries fixed: {$fixedGalleries}\n";
    echo "   - Gallery items fixed: {$fixedItems}\n";
    
} catch (Exception $e) {
    echo "   ❌ Error fixing database paths: " . $e->getMessage() . "\n";
}

// 11. Clear cache
echo "\n🧹 Clearing cache...\n";

try {
    // Clear config cache
    if (file_exists('bootstrap/cache/config.php')) {
        unlink('bootstrap/cache/config.php');
        echo "   ✅ Config cache cleared\n";
    }
    
    // Clear route cache
    if (file_exists('bootstrap/cache/routes.php')) {
        unlink('bootstrap/cache/routes.php');
        echo "   ✅ Route cache cleared\n";
    }
    
    // Clear view cache
    $viewCachePath = 'storage/framework/views';
    if (is_dir($viewCachePath)) {
        $files = glob($viewCachePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "   ✅ View cache cleared\n";
    }
    
    echo "   ✅ All caches cleared\n";
} catch (Exception $e) {
    echo "   ❌ Error clearing cache: " . $e->getMessage() . "\n";
}

echo "\n✅ Direct gallery images fix completed!\n";
echo "🔧 Key improvements applied:\n";
echo "   - Created manual storage link\n";
echo "   - Fixed Gallery model with image URL accessor\n";
echo "   - Fixed GalleryItem model with image URL accessor\n";
echo "   - Fixed gallery edit view\n";
echo "   - Fixed gallery index view\n";
echo "   - Fixed gallery items views\n";
echo "   - Created default images\n";
echo "   - Fixed database paths\n";
echo "   - Cleared all caches\n";
echo "\n🌐 Test your gallery edits:\n";
echo "   - Gallery Index: http://localhost:8000/admin/gallery\n";
echo "   - Gallery Edit: http://localhost:8000/admin/gallery/2/edit\n";
echo "   - Check if images appear in edit forms\n";
echo "   - Test image upload functionality\n";
echo "\n🔑 Admin Login:\n";
echo "   - Email: admin@namrole.sch.id\n";
echo "   - Password: admin123\n";

// Helper function to copy directory recursively
function copyDirectory($source, $destination) {
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        $target = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        
        if ($item->isDir()) {
            if (!is_dir($target)) {
                mkdir($target, 0755, true);
            }
        } else {
            copy($item, $target);
        }
    }
}

