<?php

echo "🧪 Testing Direct Gallery Images Fix\n";
echo "====================================\n\n";

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

// 3. Test Gallery model
echo "\n📝 Testing Gallery model...\n";

$galleryModelPath = 'app/Models/Gallery.php';
if (file_exists($galleryModelPath)) {
    $modelContent = file_get_contents($galleryModelPath);
    
    if (strpos($modelContent, 'getImageUrlAttribute') !== false) {
        echo "   ✅ Gallery model has image URL accessor\n";
    } else {
        echo "   ❌ Gallery model missing image URL accessor\n";
    }
} else {
    echo "   ❌ Gallery model not found\n";
}

// 4. Test GalleryItem model
echo "\n📝 Testing GalleryItem model...\n";

$galleryItemModelPath = 'app/Models/GalleryItem.php';
if (file_exists($galleryItemModelPath)) {
    $modelContent = file_get_contents($galleryItemModelPath);
    
    if (strpos($modelContent, 'getImageUrlAttribute') !== false) {
        echo "   ✅ GalleryItem model has image URL accessor\n";
    } else {
        echo "   ❌ GalleryItem model missing image URL accessor\n";
    }
} else {
    echo "   ❌ GalleryItem model not found\n";
}

// 5. Test gallery views
echo "\n📝 Testing gallery views...\n";

$galleryViews = [
    'resources/views/admin/gallery/edit.blade.php',
    'resources/views/admin/gallery/index.blade.php',
    'resources/views/admin/gallery/create.blade.php'
];

foreach ($galleryViews as $viewPath) {
    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);
        
        if (strpos($viewContent, 'image_url') !== false) {
            echo "   ✅ " . basename($viewPath) . " uses image_url\n";
        } else {
            echo "   ❌ " . basename($viewPath) . " not using image_url\n";
        }
        
        if (strpos($viewContent, 'onerror') !== false) {
            echo "   ✅ " . basename($viewPath) . " has onerror fallback\n";
        } else {
            echo "   ❌ " . basename($viewPath) . " missing onerror fallback\n";
        }
    } else {
        echo "   ⚠️  " . basename($viewPath) . " not found\n";
    }
}

// 6. Test gallery items views
echo "\n📝 Testing gallery items views...\n";

$galleryItemViews = [
    'resources/views/admin/gallery-items/edit.blade.php',
    'resources/views/admin/gallery-items/index.blade.php',
    'resources/views/admin/gallery-items/create.blade.php'
];

foreach ($galleryItemViews as $viewPath) {
    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);
        
        if (strpos($viewContent, 'image_url') !== false) {
            echo "   ✅ " . basename($viewPath) . " uses image_url\n";
        } else {
            echo "   ❌ " . basename($viewPath) . " not using image_url\n";
        }
        
        if (strpos($viewContent, 'onerror') !== false) {
            echo "   ✅ " . basename($viewPath) . " has onerror fallback\n";
        } else {
            echo "   ❌ " . basename($viewPath) . " missing onerror fallback\n";
        }
    } else {
        echo "   ⚠️  " . basename($viewPath) . " not found\n";
    }
}

// 7. Test storage structure
echo "\n🔗 Testing storage structure...\n";

$storageTests = [
    'storage/app/public' => 'Storage app public',
    'storage/app/public/gallery' => 'Gallery storage',
    'storage/app/public/gallery-items' => 'Gallery items storage',
    'public/storage' => 'Public storage link',
    'public/storage/gallery' => 'Gallery public',
    'public/storage/gallery-items' => 'Gallery items public',
    'public/images' => 'Public images'
];

foreach ($storageTests as $path => $description) {
    if (is_dir($path)) {
        echo "   ✅ {$description}: {$path}\n";
        
        // Check if directory is writable
        if (is_writable($path)) {
            echo "      ✅ Writable\n";
        } else {
            echo "      ❌ Not writable\n";
        }
        
        // Count files in directory
        $files = glob($path . '/*');
        $fileCount = count($files);
        echo "      📁 Files: {$fileCount}\n";
        
        if ($fileCount > 0) {
            echo "      📄 Sample files:\n";
            for ($i = 0; $i < min(3, $fileCount); $i++) {
                $fileName = basename($files[$i]);
                $fileSize = filesize($files[$i]);
                echo "         - {$fileName} ({$fileSize} bytes)\n";
            }
        }
    } else {
        echo "   ❌ {$description}: {$path} (missing)\n";
    }
}

// 8. Test default images
echo "\n🖼️  Testing default images...\n";

$defaultImages = [
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-gallery-item.png' => 'Default gallery item image'
];

foreach ($defaultImages as $path => $description) {
    if (file_exists($path)) {
        $fileSize = filesize($path);
        echo "   ✅ {$description}: {$path} ({$fileSize} bytes)\n";
    } else {
        echo "   ❌ {$description}: {$path} (missing)\n";
    }
}

// 9. Test gallery data
echo "\n📊 Testing gallery data...\n";

try {
    $galleries = DB::table('galleries')->get();
    echo "   📊 Found " . count($galleries) . " galleries\n";
    
    $galleriesWithImages = 0;
    $galleriesWithoutImages = 0;
    $galleriesWithWorkingImages = 0;
    $galleriesWithBrokenImages = 0;
    
    foreach ($galleries as $gallery) {
        echo "   🔍 Gallery ID {$gallery->id}: {$gallery->title}\n";
        
        if ($gallery->image) {
            echo "      🖼️  Image: {$gallery->image}\n";
            $galleriesWithImages++;
            
            // Test image paths
            $storagePath = storage_path('app/public/' . $gallery->image);
            $publicPath = public_path('storage/' . $gallery->image);
            
            if (file_exists($storagePath)) {
                echo "         ✅ Storage: {$storagePath}\n";
                $fileSize = filesize($storagePath);
                echo "         📏 File size: {$fileSize} bytes\n";
            } else {
                echo "         ❌ Storage: {$storagePath} (missing)\n";
            }
            
            if (file_exists($publicPath)) {
                echo "         ✅ Public: {$publicPath}\n";
                $fileSize = filesize($publicPath);
                echo "         📏 File size: {$fileSize} bytes\n";
                $galleriesWithWorkingImages++;
            } else {
                echo "         ❌ Public: {$publicPath} (missing)\n";
                $galleriesWithBrokenImages++;
            }
            
            // Test image URL
            $imageUrl = asset('storage/' . $gallery->image);
            echo "         🌐 URL: {$imageUrl}\n";
            
        } else {
            echo "      🖼️  Image: None\n";
            $galleriesWithoutImages++;
        }
        
        // Generate edit URL
        $editUrl = "http://localhost:8000/admin/gallery/{$gallery->id}/edit";
        echo "      🔗 Edit URL: {$editUrl}\n";
    }
    
    echo "\n   📊 Gallery Summary:\n";
    echo "   - Total galleries: " . count($galleries) . "\n";
    echo "   - Galleries with images: {$galleriesWithImages}\n";
    echo "   - Galleries without images: {$galleriesWithoutImages}\n";
    echo "   - Galleries with working images: {$galleriesWithWorkingImages}\n";
    echo "   - Galleries with broken images: {$galleriesWithBrokenImages}\n";
    
} catch (Exception $e) {
    echo "   ❌ Error testing gallery data: " . $e->getMessage() . "\n";
}

// 10. Test gallery items data
echo "\n📊 Testing gallery items data...\n";

try {
    $galleryItems = DB::table('gallery_items')->get();
    echo "   📊 Found " . count($galleryItems) . " gallery items\n";
    
    $itemsWithImages = 0;
    $itemsWithoutImages = 0;
    $itemsWithWorkingImages = 0;
    $itemsWithBrokenImages = 0;
    
    foreach ($galleryItems as $item) {
        echo "   🔍 Item ID {$item->id}: {$item->title}\n";
        
        if ($item->image) {
            echo "      🖼️  Image: {$item->image}\n";
            $itemsWithImages++;
            
            // Test image paths
            $storagePath = storage_path('app/public/' . $item->image);
            $publicPath = public_path('storage/' . $item->image);
            
            if (file_exists($storagePath)) {
                echo "         ✅ Storage: {$storagePath}\n";
                $fileSize = filesize($storagePath);
                echo "         📏 File size: {$fileSize} bytes\n";
            } else {
                echo "         ❌ Storage: {$storagePath} (missing)\n";
            }
            
            if (file_exists($publicPath)) {
                echo "         ✅ Public: {$publicPath}\n";
                $fileSize = filesize($publicPath);
                echo "         📏 File size: {$fileSize} bytes\n";
                $itemsWithWorkingImages++;
            } else {
                echo "         ❌ Public: {$publicPath} (missing)\n";
                $itemsWithBrokenImages++;
            }
            
            // Test image URL
            $imageUrl = asset('storage/' . $item->image);
            echo "         🌐 URL: {$imageUrl}\n";
            
        } else {
            echo "      🖼️  Image: None\n";
            $itemsWithoutImages++;
        }
    }
    
    echo "\n   📊 Gallery Items Summary:\n";
    echo "   - Total items: " . count($galleryItems) . "\n";
    echo "   - Items with images: {$itemsWithImages}\n";
    echo "   - Items without images: {$itemsWithoutImages}\n";
    echo "   - Items with working images: {$itemsWithWorkingImages}\n";
    echo "   - Items with broken images: {$itemsWithBrokenImages}\n";
    
} catch (Exception $e) {
    echo "   ❌ Error testing gallery items data: " . $e->getMessage() . "\n";
}

// 11. Test admin access
echo "\n👤 Testing admin access...\n";

try {
    $adminUsers = DB::table('users')->where('role', 'admin')->get();
    echo "   📊 Admin users: " . count($adminUsers) . "\n";
    
    foreach ($adminUsers as $admin) {
        echo "   👤 Admin: {$admin->name}\n";
        echo "      📧 Email: {$admin->email}\n";
        echo "      📅 Created: {$admin->created_at}\n";
    }
    
    if (count($adminUsers) === 0) {
        echo "   ❌ No admin users found\n";
        echo "   🔧 You need to create an admin user to access the admin panel\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error testing admin access: " . $e->getMessage() . "\n";
}

echo "\n✅ Direct gallery images test completed!\n";
echo "🔧 Test results summary:\n";
echo "   - Gallery model tested\n";
echo "   - GalleryItem model tested\n";
echo "   - Gallery views tested\n";
echo "   - Gallery items views tested\n";
echo "   - Storage structure tested\n";
echo "   - Default images tested\n";
echo "   - Gallery data tested\n";
echo "   - Gallery items data tested\n";
echo "   - Admin access tested\n";
echo "\n🌐 Next steps:\n";
echo "   - Check any ❌ errors above\n";
echo "   - Run fix scripts if needed\n";
echo "   - Test admin panel access\n";
echo "   - Verify image uploads work\n";
echo "   - Test all gallery edit pages\n";
echo "\n🔑 Admin Login:\n";
echo "   - URL: http://localhost:8000/login\n";
echo "   - Email: admin@namrole.sch.id\n";
echo "   - Password: admin123\n";
echo "\n🌐 Test URLs:\n";
echo "   - Gallery Index: http://localhost:8000/admin/gallery\n";
echo "   - Check all gallery edit URLs above\n";

