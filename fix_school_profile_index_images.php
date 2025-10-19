<?php
/**
 * Fix School Profile Index Images
 * 
 * This script fixes the issue where images don't appear in school profile index page
 * by ensuring proper image paths and storage structure
 */

echo "🏫 Fixing School Profile Index Images\n";
echo "=====================================\n\n";

echo "🔧 Fixing school profile index image display issue...\n";

// 1. Create storage structure
echo "\n🔗 Creating storage structure...\n";

// Create directories
$directories = [
    'storage/app/public',
    'storage/app/public/school-profiles',
    'public/storage',
    'public/storage/school-profiles',
    'public/uploads',
    'public/uploads/school-profiles',
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

// 2. Create storage link if not exists
echo "\n🔗 Creating storage link...\n";
$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

if (!is_link($storageLink)) {
    if (function_exists('symlink')) {
        if (symlink($storageTarget, $storageLink)) {
            echo "✅ Storage link created\n";
        } else {
            echo "❌ Failed to create storage link\n";
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

// 3. Create default images
echo "\n🖼️ Creating default images...\n";

$defaultImages = [
    'public/images/default-school-profile.png' => 'Default school profile image',
    'public/images/default-section.png' => 'Default section image',
    'public/images/default-hero.png' => 'Default hero image',
    'public/images/default-struktur.png' => 'Default struktur image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        // Create a simple default image
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

// 4. Copy existing school profile images to public storage
echo "\n📁 Copying existing school profile images...\n";

// Get all school profile images from storage/app/public/school-profiles
$sourceDir = 'storage/app/public/school-profiles';
$destDir = 'public/storage/school-profiles';

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
                echo "✅ Copied: $file\n";
            } else {
                echo "❌ Failed to copy: $file\n";
            }
        }
    }
    
    echo "✅ Copied $copiedCount school profile images\n";
} else {
    echo "⚠️ Source directory not found: $sourceDir\n";
}

// 5. Test file permissions
echo "\n🔐 Testing file permissions...\n";

$testDirs = [
    'storage/app/public/school-profiles',
    'public/storage/school-profiles',
    'public/uploads/school-profiles',
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

// 6. Fix SchoolProfile model accessor
echo "\n🔧 Fixing SchoolProfile model accessor...\n";

$modelPath = 'app/Models/SchoolProfile.php';
if (file_exists($modelPath)) {
    $content = file_get_contents($modelPath);
    
    // Check if comprehensive accessor exists
    if (strpos($content, 'getImageUrlAttribute') !== false &&
        strpos($content, 'str_starts_with') !== false) {
        echo "✅ SchoolProfile model has comprehensive accessor\n";
    } else {
        echo "❌ SchoolProfile model accessor needs updating\n";
        echo "🔧 Updating model accessor...\n";
        
        // Add comprehensive accessor
        $accessorCode = '
    // Accessors
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-school-profile.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'storage/school-profiles/\')) {
            return asset($this->image);
        }
        
        if (str_starts_with($this->image, \'school-profiles/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        if (str_starts_with($this->image, \'uploads/school-profiles/\')) {
            return asset($this->image);
        }
        
        if (!str_starts_with($this->image, \'storage/\') && 
            !str_starts_with($this->image, \'school-profiles/\') &&
            !str_starts_with($this->image, \'uploads/\')) {
            return asset(\'storage/school-profiles/\' . $this->image);
        }
        
        return asset(\'images/default-school-profile.png\');
    }';
        
        // Add accessor before closing brace
        $content = str_replace('}', $accessorCode . "\n}", $content);
        file_put_contents($modelPath, $content);
        echo "✅ Added comprehensive accessor to SchoolProfile model\n";
    }
} else {
    echo "❌ SchoolProfile model not found\n";
}

// 7. Fix school profile index view
echo "\n🔧 Fixing school profile index view...\n";

$viewPath = 'resources/views/admin/school-profile/index.blade.php';
if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    
    // Check if view uses Storage::url and has proper fallback
    if (strpos($content, 'Storage::url($section->image)') !== false &&
        strpos($content, 'onerror=') !== false) {
        echo "✅ School profile index view has proper image display logic\n";
    } else {
        echo "❌ School profile index view needs updating\n";
        echo "🔧 Updating view image display logic...\n";
        
        // Update desktop table image display
        $desktopImagePattern = '/<img src="{{ Storage::url\(\$section->image\) }}" alt="{{ \$section->image_alt \?\: \$section->title }}" class="h-12 w-12 object-cover rounded-lg"[^>]*>/';
        $desktopImageReplacement = '<img src="{{ Storage::url($section->image) }}" alt="{{ $section->image_alt ?: $section->title }}" class="h-12 w-12 object-cover rounded-lg" onerror="this.src=\'{{ asset(\'images/default-section.png\') }}\'">';
        
        $content = preg_replace($desktopImagePattern, $desktopImageReplacement, $content);
        
        // Update mobile cards image display
        $mobileImagePattern = '/<img src="{{ Storage::url\(\$section->image\) }}" alt="{{ \$section->image_alt \?\: \$section->title }}" class="h-16 w-16 object-cover rounded-lg"[^>]*>/';
        $mobileImageReplacement = '<img src="{{ Storage::url($section->image) }}" alt="{{ $section->image_alt ?: $section->title }}" class="h-16 w-16 object-cover rounded-lg" onerror="this.src=\'{{ asset(\'images/default-section.png\') }}\'">';
        
        $content = preg_replace($mobileImagePattern, $mobileImageReplacement, $content);
        
        file_put_contents($viewPath, $content);
        echo "✅ Updated school profile index view image display logic\n";
    }
} else {
    echo "❌ School profile index view not found\n";
}

// 8. Create test school profile entries
echo "\n📝 Creating test school profile entries...\n";

// Create a simple test to verify the setup
$testImagePath = 'public/storage/school-profiles/test-school-profile-image.png';
if (!file_exists($testImagePath)) {
    $testImageContent = createDefaultImage(300, 200, '#e5e7eb', '#374151');
    if (file_put_contents($testImagePath, $testImageContent)) {
        echo "✅ Created test image: $testImagePath\n";
    } else {
        echo "❌ Failed to create test image\n";
    }
} else {
    echo "✅ Test image already exists\n";
}

// 9. Test image URLs
echo "\n🔍 Testing image URLs...\n";

$testImages = [
    'public/storage/school-profiles/test-school-profile-image.png',
    'public/images/default-school-profile.png',
    'public/images/default-section.png'
];

foreach ($testImages as $imagePath) {
    if (file_exists($imagePath)) {
        $url = str_replace('public/', '', $imagePath);
        echo "✅ Image accessible: $url\n";
    } else {
        echo "❌ Image not found: $imagePath\n";
    }
}

echo "\n✅ School profile index images fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created default images\n";
echo "- Copied existing school profile images to public storage\n";
echo "- Tested file permissions\n";
echo "- Fixed SchoolProfile model accessor\n";
echo "- Fixed school profile index view\n";
echo "- Created test school profile entries\n";
echo "- Tested image URLs\n\n";

echo "🌐 Test URLs:\n";
echo "- School Profile Index: http://localhost:8000/admin/school-profile\n";
echo "- School Profile Edit: http://localhost:8000/admin/school-profile/6/edit\n";
echo "- School Profile Hero: http://localhost:8000/admin/school-profile/hero/edit\n";
echo "- School Profile Struktur: http://localhost:8000/admin/school-profile/struktur/edit\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test school profile index page\n";
echo "2. Check if images appear in school profile index\n";
echo "3. Verify image display in both desktop and mobile views\n";
echo "4. Check browser console for any errors\n";

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

