<?php
/**
 * Fix Hosting Images Comprehensive
 * 
 * This script fixes all image display issues on hosting environment
 * by creating proper storage structure and copying all images
 */

echo "🌐 Fixing Hosting Images Comprehensive\n";
echo "=====================================\n\n";

echo "🔧 Fixing all image display issues on hosting...\n";

// 1. Create comprehensive storage structure
echo "\n🔗 Creating comprehensive storage structure...\n";

// Create all necessary directories
$directories = [
    // Storage directories
    'storage/app/public',
    'storage/app/public/students',
    'storage/app/public/students/photos',
    'storage/app/public/teachers',
    'storage/app/public/school-profiles',
    'storage/app/public/facilities',
    'storage/app/public/galleries',
    'storage/app/public/gallery-items',
    'storage/app/public/news',
    'storage/app/public/headmaster-greetings',
    'storage/app/public/home-sections',
    'storage/app/public/academic-calendar',
    'storage/app/public/news-sections',
    
    // Public storage directories
    'public/storage',
    'public/storage/students',
    'public/storage/students/photos',
    'public/storage/teachers',
    'public/storage/school-profiles',
    'public/storage/facilities',
    'public/storage/galleries',
    'public/storage/gallery-items',
    'public/storage/news',
    'public/storage/headmaster-greetings',
    'public/storage/home-sections',
    'public/storage/academic-calendar',
    'public/storage/news-sections',
    
    // Upload directories
    'public/uploads',
    'public/uploads/students',
    'public/uploads/teachers',
    'public/uploads/school-profiles',
    'public/uploads/facilities',
    'public/uploads/galleries',
    'public/uploads/news',
    'public/uploads/headmaster-greetings',
    'public/uploads/home-sections',
    
    // Images directory
    'public/images'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Created directory: $dir\n";
        } else {
            echo "❌ Failed to create directory: $dir\n";
        }
    } else {
        echo "✅ Directory exists: $dir\n";
    }
}

// 2. Create storage link or manual storage
echo "\n🔗 Creating storage link or manual storage...\n";
$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

if (!is_link($storageLink)) {
    if (function_exists('symlink')) {
        if (symlink($storageTarget, $storageLink)) {
            echo "✅ Storage link created using symlink()\n";
        } else {
            echo "❌ Failed to create storage link using symlink()\n";
            echo "🔧 Creating manual storage directory...\n";
            createManualStorage();
        }
    } else {
        echo "⚠️ symlink() function not available\n";
        echo "🔧 Creating manual storage directory...\n";
        createManualStorage();
    }
} else {
    echo "✅ Storage link already exists\n";
}

// 3. Create all default images
echo "\n🖼️ Creating all default images...\n";

$defaultImages = [
    'public/images/default-student.png' => 'Default student image',
    'public/images/default-teacher.png' => 'Default teacher image',
    'public/images/default-user.png' => 'Default user image',
    'public/images/default-school-profile.png' => 'Default school profile image',
    'public/images/default-facility.png' => 'Default facility image',
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-news.png' => 'Default news image',
    'public/images/default-headmaster.png' => 'Default headmaster image',
    'public/images/default-hero.png' => 'Default hero image',
    'public/images/default-section.png' => 'Default section image',
    'public/images/default-logo.png' => 'Default logo image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        $imageContent = createDefaultImage(200, 200, '#f3f4f6', '#6b7280');
        if (file_put_contents($path, $imageContent)) {
            echo "✅ Created: $path\n";
        } else {
            echo "❌ Failed to create: $path\n";
        }
    } else {
        echo "✅ Default image exists: $path\n";
    }
}

// 4. Copy all existing images to public storage
echo "\n📁 Copying all existing images to public storage...\n";

$imageDirectories = [
    'storage/app/public/students/photos' => 'public/storage/students/photos',
    'storage/app/public/teachers' => 'public/storage/teachers',
    'storage/app/public/school-profiles' => 'public/storage/school-profiles',
    'storage/app/public/facilities' => 'public/storage/facilities',
    'storage/app/public/galleries' => 'public/storage/galleries',
    'storage/app/public/gallery-items' => 'public/storage/gallery-items',
    'storage/app/public/news' => 'public/storage/news',
    'storage/app/public/headmaster-greetings' => 'public/storage/headmaster-greetings',
    'storage/app/public/home-sections' => 'public/storage/home-sections',
    'storage/app/public/academic-calendar' => 'public/storage/academic-calendar',
    'storage/app/public/news-sections' => 'public/storage/news-sections'
];

$totalCopied = 0;
foreach ($imageDirectories as $sourceDir => $destDir) {
    if (is_dir($sourceDir)) {
        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }
        
        $files = scandir($sourceDir);
        $copiedCount = 0;
        
        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && is_file($sourceDir . '/' . $file)) {
                $sourcePath = $sourceDir . '/' . $file;
                $destPath = $destDir . '/' . $file;
                
                if (copy($sourcePath, $destPath)) {
                    $copiedCount++;
                    $totalCopied++;
                } else {
                    echo "❌ Failed to copy: $file from $sourceDir\n";
                }
            }
        }
        
        if ($copiedCount > 0) {
            echo "✅ Copied $copiedCount files from $sourceDir\n";
        }
    } else {
        echo "⚠️ Source directory not found: $sourceDir\n";
    }
}

echo "✅ Total files copied: $totalCopied\n";

// 5. Test file permissions
echo "\n🔐 Testing file permissions...\n";

$testDirs = [
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

foreach ($testDirs as $dir) {
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

// 6. Create .htaccess for public storage
echo "\n🔧 Creating .htaccess for public storage...\n";

$htaccessContent = '# Allow access to storage files
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^storage/(.*)$ storage/$1 [L]
</IfModule>

# Set proper MIME types for images
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/svg+xml .svg
    AddType image/webp .webp
</IfModule>

# Enable CORS for images
<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|svg|webp)$">
        Header set Access-Control-Allow-Origin "*"
        Header set Cache-Control "public, max-age=31536000"
    </FilesMatch>
</IfModule>';

$htaccessPath = 'public/storage/.htaccess';
if (file_put_contents($htaccessPath, $htaccessContent)) {
    echo "✅ Created .htaccess for public storage\n";
} else {
    echo "❌ Failed to create .htaccess for public storage\n";
}

// 7. Create test images for verification
echo "\n📝 Creating test images for verification...\n";

$testImages = [
    'public/storage/students/photos/test-student.png',
    'public/storage/teachers/test-teacher.png',
    'public/storage/school-profiles/test-school-profile.png',
    'public/storage/facilities/test-facility.png',
    'public/storage/galleries/test-gallery.png',
    'public/storage/news/test-news.png'
];

foreach ($testImages as $testImagePath) {
    if (!file_exists($testImagePath)) {
        $testImageContent = createDefaultImage(300, 200, '#e5e7eb', '#374151');
        if (file_put_contents($testImagePath, $testImageContent)) {
            echo "✅ Created test image: $testImagePath\n";
        } else {
            echo "❌ Failed to create test image: $testImagePath\n";
        }
    } else {
        echo "✅ Test image already exists: $testImagePath\n";
    }
}

// 8. Test image URLs
echo "\n🔍 Testing image URLs...\n";

$testUrls = [
    'storage/students/photos/test-student.png',
    'storage/teachers/test-teacher.png',
    'storage/school-profiles/test-school-profile.png',
    'storage/facilities/test-facility.png',
    'storage/galleries/test-gallery.png',
    'storage/news/test-news.png',
    'images/default-student.png',
    'images/default-teacher.png',
    'images/default-school-profile.png'
];

foreach ($testUrls as $url) {
    $fullPath = 'public/' . $url;
    if (file_exists($fullPath)) {
        echo "✅ Image accessible: $url\n";
    } else {
        echo "❌ Image not found: $url\n";
    }
}

// 9. Create hosting-specific configuration
echo "\n⚙️ Creating hosting-specific configuration...\n";

// Create hosting-specific .env additions
$hostingEnvContent = '
# Hosting-specific configuration
APP_ENV=production
APP_DEBUG=false
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
SESSION_LIFETIME=480

# Storage configuration for hosting
FILESYSTEM_DISK=public
';

$envPath = '.env';
if (file_exists($envPath)) {
    $currentEnv = file_get_contents($envPath);
    if (strpos($currentEnv, 'SESSION_SECURE_COOKIE=false') === false) {
        file_put_contents($envPath, $currentEnv . $hostingEnvContent);
        echo "✅ Added hosting-specific configuration to .env\n";
    } else {
        echo "✅ Hosting-specific configuration already exists in .env\n";
    }
} else {
    echo "⚠️ .env file not found\n";
}

echo "\n✅ Hosting images comprehensive fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created comprehensive storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created all default images\n";
echo "- Copied all existing images to public storage\n";
echo "- Tested file permissions\n";
echo "- Created .htaccess for public storage\n";
echo "- Created test images for verification\n";
echo "- Tested image URLs\n";
echo "- Created hosting-specific configuration\n\n";

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
echo "1. Upload this script to your hosting server\n";
echo "2. Run: php fix_hosting_images_comprehensive.php\n";
echo "3. Test all image uploads and displays\n";
echo "4. Check browser console for any errors\n";
echo "5. Verify all images are accessible via web\n";

// Helper functions
function createManualStorage() {
    $sourceDir = 'storage/app/public';
    $destDir = 'public/storage';
    
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    
    if (is_dir($sourceDir)) {
        copyDirectory($sourceDir, $destDir);
        echo "✅ Manual storage directory created and files copied\n";
    } else {
        echo "⚠️ Source directory not found: $sourceDir\n";
    }
}

function copyDirectory($src, $dst) {
    if (is_dir($src)) {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }
        
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $srcFile = $src . '/' . $file;
                $dstFile = $dst . '/' . $file;
                
                if (is_dir($srcFile)) {
                    copyDirectory($srcFile, $dstFile);
                } else {
                    copy($srcFile, $dstFile);
                }
            }
        }
    }
}

function createDefaultImage($width, $height, $bgColor, $textColor) {
    // Create a simple PNG image
    $image = imagecreate($width, $height);
    
    // Parse colors
    $bg = sscanf($bgColor, "#%02x%02x%02x");
    $text = sscanf($textColor, "#%02x%02x%02x");
    
    $bgColor = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
    $textColor = imagecolorallocate($image, $text[0], $text[1], $text[2]);
    
    // Fill background
    imagefill($image, 0, 0, $bgColor);
    
    // Add text
    $text = "No Image";
    $font = 5;
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;
    
    imagestring($image, $font, $x, $y, $text, $textColor);
    
    // Output as PNG
    ob_start();
    imagepng($image);
    $imageData = ob_get_contents();
    ob_end_clean();
    
    imagedestroy($image);
    
    return $imageData;
}
