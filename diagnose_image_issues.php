<?php
/**
 * Diagnose Image Issues
 * 
 * This script diagnoses why images are still not appearing
 * and provides comprehensive fixes
 */

echo "🔍 Diagnosing Image Issues\n";
echo "==========================\n\n";

echo "🔧 Diagnosing why images are still not appearing...\n";

// 1. Bootstrap Laravel
echo "\n🔗 Bootstrapping Laravel...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to bootstrap Laravel: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check storage structure
echo "\n🔍 Checking storage structure...\n";

$storageDirs = [
    'storage/app/public',
    'storage/app/public/students/photos',
    'storage/app/public/teachers',
    'storage/app/public/school-profiles',
    'storage/app/public/facilities',
    'storage/app/public/galleries',
    'storage/app/public/news',
    'storage/app/public/headmaster-greetings',
    'storage/app/public/home-sections',
    'public/storage',
    'public/storage/students/photos',
    'public/storage/teachers',
    'public/storage/school-profiles',
    'public/storage/facilities',
    'public/storage/galleries',
    'public/storage/news',
    'public/storage/headmaster-greetings',
    'public/storage/home-sections',
    'public/images'
];

foreach ($storageDirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        if (is_writable($dir)) {
            echo "✅ $dir: $perms (writable)\n";
        } else {
            echo "❌ $dir: $perms (not writable)\n";
        }
    } else {
        echo "⚠️ $dir: Directory not found\n";
    }
}

// 3. Check storage link
echo "\n🔍 Checking storage link...\n";

$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

if (is_link($storageLink)) {
    $linkTarget = readlink($storageLink);
    echo "✅ Storage link exists: $storageLink -> $linkTarget\n";
    
    if ($linkTarget === $storageTarget) {
        echo "✅ Storage link is correct\n";
    } else {
        echo "❌ Storage link target is incorrect\n";
    }
} else {
    echo "⚠️ Storage link does not exist\n";
    echo "🔧 This is normal if using manual storage\n";
}

// 4. Check database image paths
echo "\n🔍 Checking database image paths...\n";

try {
    // Check users with photos
    $usersWithPhotos = \App\Models\User::whereNotNull('photo')->get();
    echo "✅ Users with photos: " . $usersWithPhotos->count() . "\n";
    
    foreach ($usersWithPhotos as $user) {
        echo "📝 User ID: {$user->id}, Name: {$user->name}\n";
        echo "   Photo: {$user->photo}\n";
        echo "   Photo URL: {$user->photo_url}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $user->photo);
        $publicPath = public_path('storage/' . $user->photo);
        
        echo "   Storage path exists: " . (file_exists($storagePath) ? 'Yes' : 'No') . "\n";
        echo "   Public path exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . "\n";
        
        if (file_exists($storagePath) && !file_exists($publicPath)) {
            echo "   🔧 Need to copy from storage to public\n";
        }
        echo "\n";
    }
    
    // Check school profiles with images
    $schoolProfilesWithImages = \App\Models\SchoolProfile::whereNotNull('image')->get();
    echo "✅ School profiles with images: " . $schoolProfilesWithImages->count() . "\n";
    
    foreach ($schoolProfilesWithImages as $profile) {
        echo "📝 Profile ID: {$profile->id}, Title: {$profile->title}\n";
        echo "   Image: {$profile->image}\n";
        echo "   Image URL: {$profile->image_url}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $profile->image);
        $publicPath = public_path('storage/' . $profile->image);
        
        echo "   Storage path exists: " . (file_exists($storagePath) ? 'Yes' : 'No') . "\n";
        echo "   Public path exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . "\n";
        
        if (file_exists($storagePath) && !file_exists($publicPath)) {
            echo "   🔧 Need to copy from storage to public\n";
        }
        echo "\n";
    }
    
    // Check facilities with images
    $facilitiesWithImages = \App\Models\Facility::whereNotNull('image')->get();
    echo "✅ Facilities with images: " . $facilitiesWithImages->count() . "\n";
    
    foreach ($facilitiesWithImages as $facility) {
        echo "📝 Facility ID: {$facility->id}, Name: {$facility->name}\n";
        echo "   Image: {$facility->image}\n";
        echo "   Image URL: {$facility->image_url}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $facility->image);
        $publicPath = public_path('storage/' . $facility->image);
        
        echo "   Storage path exists: " . (file_exists($storagePath) ? 'Yes' : 'No') . "\n";
        echo "   Public path exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . "\n";
        
        if (file_exists($storagePath) && !file_exists($publicPath)) {
            echo "   🔧 Need to copy from storage to public\n";
        }
        echo "\n";
    }
    
    // Check galleries with images
    $galleriesWithImages = \App\Models\Gallery::whereNotNull('cover_image')->get();
    echo "✅ Galleries with images: " . $galleriesWithImages->count() . "\n";
    
    foreach ($galleriesWithImages as $gallery) {
        echo "📝 Gallery ID: {$gallery->id}, Title: {$gallery->title}\n";
        echo "   Cover Image: {$gallery->cover_image}\n";
        echo "   Cover Image URL: {$gallery->cover_image_url}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $gallery->cover_image);
        $publicPath = public_path('storage/' . $gallery->cover_image);
        
        echo "   Storage path exists: " . (file_exists($storagePath) ? 'Yes' : 'No') . "\n";
        echo "   Public path exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . "\n";
        
        if (file_exists($storagePath) && !file_exists($publicPath)) {
            echo "   🔧 Need to copy from storage to public\n";
        }
        echo "\n";
    }
    
    // Check news with images
    $newsWithImages = \App\Models\News::whereNotNull('featured_image')->get();
    echo "✅ News with images: " . $newsWithImages->count() . "\n";
    
    foreach ($newsWithImages as $news) {
        echo "📝 News ID: {$news->id}, Title: {$news->title}\n";
        echo "   Featured Image: {$news->featured_image}\n";
        echo "   Featured Image URL: {$news->featured_image_url}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $news->featured_image);
        $publicPath = public_path('storage/' . $news->featured_image);
        
        echo "   Storage path exists: " . (file_exists($storagePath) ? 'Yes' : 'No') . "\n";
        echo "   Public path exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . "\n";
        
        if (file_exists($storagePath) && !file_exists($publicPath)) {
            echo "   🔧 Need to copy from storage to public\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error checking database: " . $e->getMessage() . "\n";
}

// 5. Test image URLs
echo "\n🔍 Testing image URLs...\n";

$baseUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$testUrls = [
    'storage/students/photos/test-student.png',
    'storage/teachers/test-teacher.png',
    'storage/school-profiles/test-school-profile.png',
    'storage/facilities/test-facility.png',
    'storage/galleries/test-gallery.png',
    'storage/news/test-news.png',
    'images/default-student.png',
    'images/default-teacher.png',
    'images/default-school-profile.png',
    'images/default-facility.png',
    'images/default-gallery.png',
    'images/default-news.png'
];

foreach ($testUrls as $url) {
    $fullUrl = $baseUrl . '/' . $url;
    $imagePath = 'public/' . $url;
    
    if (file_exists($imagePath)) {
        echo "✅ $url: File exists\n";
        echo "   URL: $fullUrl\n";
    } else {
        echo "❌ $url: File not found\n";
    }
}

// 6. Check .htaccess files
echo "\n🔍 Checking .htaccess files...\n";

$htaccessFiles = [
    'public/.htaccess',
    'public/storage/.htaccess'
];

foreach ($htaccessFiles as $htaccessFile) {
    if (file_exists($htaccessFile)) {
        $size = filesize($htaccessFile);
        echo "✅ $htaccessFile: $size bytes\n";
    } else {
        echo "⚠️ $htaccessFile: File not found\n";
    }
}

// 7. Check file permissions
echo "\n🔍 Checking file permissions...\n";

$testFiles = [
    'public/storage/students/photos/test-student.png',
    'public/storage/teachers/test-teacher.png',
    'public/storage/school-profiles/test-school-profile.png',
    'public/images/default-student.png',
    'public/images/default-teacher.png'
];

foreach ($testFiles as $file) {
    if (file_exists($file)) {
        $perms = substr(sprintf('%o', fileperms($file)), -4);
        if (is_readable($file)) {
            echo "✅ $file: $perms (readable)\n";
        } else {
            echo "❌ $file: $perms (not readable)\n";
        }
    } else {
        echo "⚠️ $file: File not found\n";
    }
}

// 8. Check Laravel configuration
echo "\n🔍 Checking Laravel configuration...\n";

if (file_exists('.env')) {
    echo "✅ .env file exists\n";
    
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'APP_ENV=production') !== false) {
        echo "✅ APP_ENV=production\n";
    } else {
        echo "⚠️ APP_ENV not set to production\n";
    }
    
    if (strpos($envContent, 'APP_DEBUG=false') !== false) {
        echo "✅ APP_DEBUG=false\n";
    } else {
        echo "⚠️ APP_DEBUG not set to false\n";
    }
    
    if (strpos($envContent, 'SESSION_SECURE_COOKIE=false') !== false) {
        echo "✅ SESSION_SECURE_COOKIE=false\n";
    } else {
        echo "⚠️ SESSION_SECURE_COOKIE not set to false\n";
    }
} else {
    echo "❌ .env file not found\n";
}

echo "\n✅ Image issues diagnosis completed!\n";
echo "🔧 Diagnosis results:\n";
echo "- Storage structure checked\n";
echo "- Storage link checked\n";
echo "- Database image paths checked\n";
echo "- Image URLs tested\n";
echo "- .htaccess files checked\n";
echo "- File permissions checked\n";
echo "- Laravel configuration checked\n\n";

echo "📝 Next Steps:\n";
echo "1. Run: php fix_all_image_issues.php\n";
echo "2. Test all image uploads and displays\n";
echo "3. Check browser console for any errors\n";
echo "4. Verify all images are accessible via web\n";

