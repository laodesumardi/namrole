<?php
/**
 * Test Hosting Images
 * 
 * This script tests image display on hosting environment
 * and provides diagnostic information
 */

echo "🧪 Testing Hosting Images\n";
echo "========================\n\n";

echo "🔧 Testing image display on hosting...\n";

// 1. Check hosting environment
echo "\n🔍 Checking hosting environment...\n";

echo "✅ PHP Version: " . PHP_VERSION . "\n";
echo "✅ Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "✅ Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";
echo "✅ Current Directory: " . getcwd() . "\n";

// 2. Check symlink function
echo "\n🔍 Checking symlink function...\n";

if (function_exists('symlink')) {
    echo "✅ symlink() function is available\n";
} else {
    echo "❌ symlink() function is not available\n";
    echo "🔧 This is common on shared hosting\n";
}

// 3. Check storage structure
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

// 4. Check storage link
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

// 5. Test image files
echo "\n🔍 Testing image files...\n";

$testImages = [
    'public/storage/students/photos/test-student.png',
    'public/storage/teachers/test-teacher.png',
    'public/storage/school-profiles/test-school-profile.png',
    'public/storage/facilities/test-facility.png',
    'public/storage/galleries/test-gallery.png',
    'public/storage/news/test-news.png',
    'public/images/default-student.png',
    'public/images/default-teacher.png',
    'public/images/default-school-profile.png',
    'public/images/default-facility.png',
    'public/images/default-gallery.png',
    'public/images/default-news.png'
];

foreach ($testImages as $imagePath) {
    if (file_exists($imagePath)) {
        $size = filesize($imagePath);
        echo "✅ $imagePath: $size bytes\n";
    } else {
        echo "❌ $imagePath: File not found\n";
    }
}

// 6. Test image URLs
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

// 7. Test .htaccess
echo "\n🔍 Testing .htaccess files...\n";

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

// 8. Test file permissions
echo "\n🔍 Testing file permissions...\n";

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

// 9. Test Laravel configuration
echo "\n🔍 Testing Laravel configuration...\n";

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

// 10. Test database connection
echo "\n🔍 Testing database connection...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test database connection
    $pdo = \DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Test image-related models
    $userCount = \App\Models\User::count();
    echo "✅ Users in database: $userCount\n";
    
    $usersWithPhotos = \App\Models\User::whereNotNull('photo')->count();
    echo "✅ Users with photos: $usersWithPhotos\n";
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

echo "\n✅ Hosting images test completed!\n";
echo "🔧 Test results summary:\n";
echo "- Hosting environment checked\n";
echo "- Symlink function availability checked\n";
echo "- Storage structure checked\n";
echo "- Storage link checked\n";
echo "- Image files tested\n";
echo "- Image URLs tested\n";
echo "- .htaccess files tested\n";
echo "- File permissions tested\n";
echo "- Laravel configuration tested\n";
echo "- Database connection tested\n\n";

echo "🌐 Test URLs for hosting:\n";
echo "- Student Profile: https://yourdomain.com/student/profile/edit\n";
echo "- Teacher Profile: https://yourdomain.com/teacher/profile/edit\n";
echo "- Admin Gallery: https://yourdomain.com/admin/gallery\n";
echo "- Admin News: https://yourdomain.com/admin/news\n";
echo "- Admin Facilities: https://yourdomain.com/admin/facilities\n";
echo "- Admin School Profile: https://yourdomain.com/admin/school-profile\n\n";

echo "🔑 Admin Login for hosting:\n";
echo "- URL: https://yourdomain.com/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps for hosting:\n";
echo "1. Run: php fix_hosting_images_comprehensive.php\n";
echo "2. Run: php fix_hosting_storage_symlink.php\n";
echo "3. Test all image uploads and displays\n";
echo "4. Check browser console for any errors\n";
echo "5. Verify all images are accessible via web\n";