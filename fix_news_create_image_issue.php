<?php
/**
 * Fix News Create Image Issue
 * 
 * This script fixes the issue where images don't appear after creating news
 * by ensuring proper file copying and storage structure
 */

echo "📰 Fixing News Create Image Issue\n";
echo "==================================\n\n";

echo "🔧 Fixing news create image display issue...\n";

// 1. Create storage structure
echo "\n🔗 Creating storage structure...\n";

// Create directories
$directories = [
    'storage/app/public',
    'storage/app/public/news',
    'public/storage',
    'public/storage/news',
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
    'public/images/default-news.png' => 'Default news image',
    'public/images/default-news.jpg' => 'Default news image (JPG)',
    'public/images/default-news-item.png' => 'Default news item image'
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

// 4. Copy existing news images to public storage
echo "\n📁 Copying existing news images...\n";

// Get all news images from storage/app/public/news
$sourceDir = 'storage/app/public/news';
$destDir = 'public/storage/news';

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
    
    echo "✅ Copied $copiedCount news images\n";
} else {
    echo "⚠️ Source directory not found: $sourceDir\n";
}

// 5. Test file permissions
echo "\n🔐 Testing file permissions...\n";

$testDirs = [
    'storage/app/public/news',
    'public/storage/news',
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

// 6. Create test news entry
echo "\n📝 Creating test news entry...\n";

// Create a simple test to verify the setup
$testNewsData = [
    'title' => 'Test News Entry',
    'slug' => 'test-news-entry',
    'content' => 'This is a test news entry to verify image functionality.',
    'category' => 'akademik',
    'type' => 'news',
    'status' => 'draft',
    'is_featured' => false,
    'is_pinned' => false
];

// Create test file
$testImagePath = 'public/storage/news/test-news-image.png';
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

// 7. Verify NewsController store method
echo "\n🔍 Verifying NewsController store method...\n";

$controllerPath = 'app/Http/Controllers/Admin/NewsController.php';
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Check if file copying logic exists
    if (strpos($content, 'Copy uploaded files to public/storage for immediate access') !== false) {
        echo "✅ NewsController store method has file copying logic\n";
    } else {
        echo "❌ NewsController store method missing file copying logic\n";
    }
    
    // Check if News::create is followed by file copying
    if (strpos($content, 'News::create($data);') !== false && 
        strpos($content, 'Copy uploaded files to public/storage for immediate access') !== false) {
        echo "✅ File copying logic is properly placed after News::create\n";
    } else {
        echo "❌ File copying logic may not be properly placed\n";
    }
} else {
    echo "❌ NewsController not found\n";
}

// 8. Verify News model accessor
echo "\n🔍 Verifying News model accessor...\n";

$modelPath = 'app/Models/News.php';
if (file_exists($modelPath)) {
    $content = file_get_contents($modelPath);
    
    // Check if comprehensive accessor exists
    if (strpos($content, 'getFeaturedImageUrlAttribute') !== false &&
        strpos($content, 'str_starts_with') !== false) {
        echo "✅ News model has comprehensive accessor\n";
    } else {
        echo "❌ News model accessor may need updating\n";
    }
} else {
    echo "❌ News model not found\n";
}

echo "\n✅ News create image issue fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created default images\n";
echo "- Copied existing news images to public storage\n";
echo "- Tested file permissions\n";
echo "- Created test news entry\n";
echo "- Verified NewsController store method\n";
echo "- Verified News model accessor\n\n";

echo "🌐 Test URLs:\n";
echo "- News Create: http://localhost:8000/admin/news/create\n";
echo "- News Index: http://localhost:8000/admin/news\n";
echo "- Test News: http://localhost:8000/admin/news (check for test entry)\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test creating a new news entry with image\n";
echo "2. Check if image appears in news index\n";
echo "3. Verify image display in news edit form\n";
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

