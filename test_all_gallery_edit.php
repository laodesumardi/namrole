<?php

echo "🧪 Testing All Gallery Edit Images\n";
echo "==================================\n\n";

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

// 3. Test all galleries for edit pages
echo "\n📊 Testing all galleries for edit pages...\n";

try {
    $galleries = DB::table('galleries')->get();
    echo "   📊 Found " . count($galleries) . " galleries\n";
    
    $galleriesWithImages = 0;
    $galleriesWithoutImages = 0;
    $galleriesWithDescription = 0;
    $galleriesWithoutDescription = 0;
    $galleriesWithWorkingImages = 0;
    $galleriesWithBrokenImages = 0;
    
    foreach ($galleries as $gallery) {
        echo "   🔍 Gallery ID {$gallery->id}: {$gallery->title}\n";
        echo "      📝 Description: " . (empty($gallery->description) ? 'Empty' : substr($gallery->description, 0, 50) . '...') . "\n";
        echo "      🔗 Slug: {$gallery->slug}\n";
        if (property_exists($gallery, 'is_active')) {
            echo "      ✅ Active: " . ($gallery->is_active ? 'Yes' : 'No') . "\n";
        } else {
            echo "      ✅ Active: Column not found\n";
        }
        
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
            
            // Test URL accessibility
            $context = stream_context_create([
                'http' => [
                    'timeout' => 5,
                    'method' => 'HEAD'
                ]
            ]);
            
            $headers = @get_headers($imageUrl, 1, $context);
            if ($headers && strpos($headers[0], '200') !== false) {
                echo "         ✅ Image URL is accessible\n";
            } else {
                echo "         ❌ Image URL is not accessible\n";
            }
            
        } else {
            echo "      🖼️  Image: None\n";
            $galleriesWithoutImages++;
        }
        
        if (!empty($gallery->description)) {
            $galleriesWithDescription++;
        } else {
            $galleriesWithoutDescription++;
        }
        
        // Generate edit URL
        $editUrl = "http://localhost:8000/admin/gallery/{$gallery->id}/edit";
        echo "      🔗 Edit URL: {$editUrl}\n";
    }
    
    echo "\n   📊 Gallery Summary:\n";
    echo "   - Total galleries: " . count($galleries) . "\n";
    echo "   - Galleries with images: {$galleriesWithImages}\n";
    echo "   - Galleries without images: {$galleriesWithoutImages}\n";
    echo "   - Galleries with description: {$galleriesWithDescription}\n";
    echo "   - Galleries without description: {$galleriesWithoutDescription}\n";
    echo "   - Galleries with working images: {$galleriesWithWorkingImages}\n";
    echo "   - Galleries with broken images: {$galleriesWithBrokenImages}\n";
    
} catch (Exception $e) {
    echo "   ❌ Error testing galleries: " . $e->getMessage() . "\n";
}

// 4. Test all gallery items for edit pages
echo "\n📊 Testing all gallery items for edit pages...\n";

try {
    $galleryItems = DB::table('gallery_items')->get();
    echo "   📊 Found " . count($galleryItems) . " gallery items\n";
    
    $itemsWithImages = 0;
    $itemsWithoutImages = 0;
    $itemsWithDescription = 0;
    $itemsWithoutDescription = 0;
    $itemsWithWorkingImages = 0;
    $itemsWithBrokenImages = 0;
    
    foreach ($galleryItems as $item) {
        echo "   🔍 Item ID {$item->id}: {$item->title}\n";
        echo "      📝 Description: " . (empty($item->description) ? 'Empty' : substr($item->description, 0, 50) . '...') . "\n";
        echo "      🏷️  Gallery ID: {$item->gallery_id}\n";
        if (property_exists($item, 'is_active')) {
            echo "      ✅ Active: " . ($item->is_active ? 'Yes' : 'No') . "\n";
        } else {
            echo "      ✅ Active: Column not found\n";
        }
        
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
        
        if (!empty($item->description)) {
            $itemsWithDescription++;
        } else {
            $itemsWithoutDescription++;
        }
    }
    
    echo "\n   📊 Gallery Items Summary:\n";
    echo "   - Total items: " . count($galleryItems) . "\n";
    echo "   - Items with images: {$itemsWithImages}\n";
    echo "   - Items without images: {$itemsWithoutImages}\n";
    echo "   - Items with description: {$itemsWithDescription}\n";
    echo "   - Items without description: {$itemsWithoutDescription}\n";
    echo "   - Items with working images: {$itemsWithWorkingImages}\n";
    echo "   - Items with broken images: {$itemsWithBrokenImages}\n";
    
} catch (Exception $e) {
    echo "   ❌ Error testing gallery items: " . $e->getMessage() . "\n";
}

// 5. Test storage structure for all gallery edits
echo "\n🔗 Testing storage structure for all gallery edits...\n";

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

// 6. Test admin access
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

// 7. Test routes for all gallery edits
echo "\n🛣️  Testing routes for all gallery edits...\n";

try {
    $routes = [
        'admin.gallery.index' => 'Admin Gallery Index',
        'admin.gallery.create' => 'Admin Gallery Create',
        'admin.gallery.edit' => 'Admin Gallery Edit',
        'admin.gallery.update' => 'Admin Gallery Update',
        'admin.gallery.show' => 'Admin Gallery Show',
        'login' => 'Login',
        'admin.dashboard' => 'Admin Dashboard'
    ];
    
    foreach ($routes as $route => $description) {
        try {
            $url = route($route);
            echo "   ✅ {$description}: {$url}\n";
        } catch (Exception $e) {
            echo "   ❌ {$description}: Route not found\n";
        }
    }
    
    // Test specific gallery edit routes
    echo "\n   🔗 Testing specific gallery edit routes:\n";
    $galleries = DB::table('galleries')->get();
    foreach ($galleries as $gallery) {
        try {
            $editUrl = route('admin.gallery.edit', ['gallery' => $gallery->id]);
            echo "   ✅ Gallery ID {$gallery->id} Edit: {$editUrl}\n";
        } catch (Exception $e) {
            echo "   ❌ Gallery ID {$gallery->id} Edit: Route not found\n";
        }
    }
    
} catch (Exception $e) {
    echo "   ❌ Error testing routes: " . $e->getMessage() . "\n";
}

// 8. Test file permissions for all gallery edits
echo "\n🔐 Testing file permissions for all gallery edits...\n";

$permissionTests = [
    'storage/app/public' => 'Storage app public',
    'storage/app/public/gallery' => 'Gallery storage',
    'storage/app/public/gallery-items' => 'Gallery items storage',
    'public/storage' => 'Public storage',
    'public/storage/gallery' => 'Gallery public',
    'public/storage/gallery-items' => 'Gallery items public'
];

foreach ($permissionTests as $path => $description) {
    if (is_dir($path)) {
        $perms = fileperms($path);
        $permString = substr(sprintf('%o', $perms), -4);
        echo "   📁 {$description}: {$permString}\n";
        
        if (is_writable($path)) {
            echo "      ✅ Writable\n";
        } else {
            echo "      ❌ Not writable\n";
        }
    } else {
        echo "   ❌ {$description}: Directory not found\n";
    }
}

// 9. Generate all gallery edit URLs
echo "\n🌐 Generating all gallery edit URLs...\n";

try {
    $galleries = DB::table('galleries')->get();
    echo "   📊 All Gallery Edit URLs:\n";
    
    foreach ($galleries as $gallery) {
        $editUrl = "http://localhost:8000/admin/gallery/{$gallery->id}/edit";
        echo "   🔗 Gallery ID {$gallery->id} ({$gallery->title}): {$editUrl}\n";
    }
    
    echo "\n   📊 Total Gallery Edit URLs: " . count($galleries) . "\n";
    
} catch (Exception $e) {
    echo "   ❌ Error generating URLs: " . $e->getMessage() . "\n";
}

echo "\n✅ All gallery edit images test completed!\n";
echo "🔧 Test results summary:\n";
echo "   - All galleries tested\n";
echo "   - All gallery items tested\n";
echo "   - Storage structure tested\n";
echo "   - Admin access tested\n";
echo "   - Routes tested\n";
echo "   - File permissions tested\n";
echo "   - All gallery edit URLs generated\n";
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

