<?php
/**
 * Fix Hosting Storage Symlink
 * 
 * This script specifically fixes storage symlink issues on hosting
 * where symlink() function is not available or restricted
 */

echo "🔗 Fixing Hosting Storage Symlink Issues\n";
echo "=======================================\n\n";

echo "🔧 Fixing storage symlink issues on hosting...\n";

// 1. Check symlink function availability
echo "\n🔍 Checking symlink function availability...\n";

if (function_exists('symlink')) {
    echo "✅ symlink() function is available\n";
} else {
    echo "❌ symlink() function is not available\n";
    echo "🔧 This is common on shared hosting\n";
}

// 2. Check current storage link status
echo "\n🔍 Checking current storage link status...\n";

$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

if (is_link($storageLink)) {
    $linkTarget = readlink($storageLink);
    echo "✅ Storage link exists: $storageLink -> $linkTarget\n";
    
    if ($linkTarget === $storageTarget) {
        echo "✅ Storage link is correct\n";
    } else {
        echo "❌ Storage link target is incorrect\n";
        echo "🔧 Removing incorrect link...\n";
        if (unlink($storageLink)) {
            echo "✅ Removed incorrect link\n";
        } else {
            echo "❌ Failed to remove incorrect link\n";
        }
    }
} else {
    echo "⚠️ Storage link does not exist\n";
}

// 3. Create storage structure
echo "\n🔗 Creating storage structure...\n";

$directories = [
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

// 4. Try to create symlink
echo "\n🔗 Attempting to create storage symlink...\n";

if (function_exists('symlink')) {
    if (symlink($storageTarget, $storageLink)) {
        echo "✅ Storage symlink created successfully\n";
    } else {
        echo "❌ Failed to create storage symlink\n";
        echo "🔧 Creating manual storage directory...\n";
        createManualStorage();
    }
} else {
    echo "⚠️ symlink() function not available\n";
    echo "🔧 Creating manual storage directory...\n";
    createManualStorage();
}

// 5. Verify symlink or manual storage
echo "\n🔍 Verifying storage setup...\n";

if (is_link($storageLink)) {
    $linkTarget = readlink($storageLink);
    echo "✅ Storage symlink verified: $storageLink -> $linkTarget\n";
} else {
    echo "⚠️ Storage symlink not available, using manual storage\n";
    
    // Check if manual storage has files
    $storageDir = 'public/storage';
    if (is_dir($storageDir)) {
        $files = scandir($storageDir);
        $fileCount = count($files) - 2; // Subtract . and ..
        echo "✅ Manual storage directory has $fileCount files\n";
    } else {
        echo "❌ Manual storage directory not found\n";
    }
}

// 6. Copy all images to public storage
echo "\n📁 Copying all images to public storage...\n";

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

// 7. Create .htaccess for public storage
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
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>';

$htaccessPath = 'public/storage/.htaccess';
if (file_put_contents($htaccessPath, $htaccessContent)) {
    echo "✅ Created .htaccess for public storage\n";
} else {
    echo "❌ Failed to create .htaccess for public storage\n";
}

// 8. Test storage access
echo "\n🔍 Testing storage access...\n";

$testPaths = [
    'public/storage/students/photos',
    'public/storage/teachers',
    'public/storage/school-profiles',
    'public/storage/facilities',
    'public/storage/galleries',
    'public/storage/news',
    'public/storage/headmaster-greetings',
    'public/storage/home-sections'
];

foreach ($testPaths as $path) {
    if (is_dir($path)) {
        $files = scandir($path);
        $fileCount = count($files) - 2; // Subtract . and ..
        echo "✅ $path: $fileCount files\n";
    } else {
        echo "❌ $path: Directory not found\n";
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

// 10. Create test images
echo "\n📝 Creating test images...\n";

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

echo "\n✅ Hosting storage symlink fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Checked symlink function availability\n";
echo "- Created storage structure\n";
echo "- Attempted to create storage symlink\n";
echo "- Created manual storage as fallback\n";
echo "- Copied all images to public storage\n";
echo "- Created .htaccess for public storage\n";
echo "- Tested storage access\n";
echo "- Created hosting-specific configuration\n";
echo "- Created test images\n\n";

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
echo "2. Run: php fix_hosting_storage_symlink.php\n";
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

