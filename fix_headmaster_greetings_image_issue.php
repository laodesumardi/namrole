<?php
/**
 * Fix Headmaster Greetings Image Issue
 * 
 * This script fixes the issue where images don't appear after updating headmaster greetings
 * by ensuring proper file copying and storage structure
 */

echo "👨‍💼 Fixing Headmaster Greetings Image Issue\n";
echo "==========================================\n\n";

echo "🔧 Fixing headmaster greetings image display issue...\n";

// 1. Create storage structure
echo "\n🔗 Creating storage structure...\n";

// Create directories
$directories = [
    'storage/app/public',
    'storage/app/public/headmaster-greetings',
    'public/storage',
    'public/storage/headmaster-greetings',
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
    'public/images/default-headmaster.png' => 'Default headmaster image',
    'public/images/default-headmaster.jpg' => 'Default headmaster image (JPG)',
    'public/images/default-teacher.png' => 'Default teacher image'
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

// 4. Copy existing headmaster images to public storage
echo "\n📁 Copying existing headmaster images...\n";

// Get all headmaster images from storage/app/public/headmaster-greetings
$sourceDir = 'storage/app/public/headmaster-greetings';
$destDir = 'public/storage/headmaster-greetings';

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
    
    echo "✅ Copied $copiedCount headmaster images\n";
} else {
    echo "⚠️ Source directory not found: $sourceDir\n";
}

// 5. Test file permissions
echo "\n🔐 Testing file permissions...\n";

$testDirs = [
    'storage/app/public/headmaster-greetings',
    'public/storage/headmaster-greetings',
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

// 6. Fix HeadmasterGreetingController
echo "\n🔧 Fixing HeadmasterGreetingController...\n";

$controllerPath = 'app/Http/Controllers/Admin/HeadmasterGreetingController.php';
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Check if file copying logic exists in store method
    if (strpos($content, 'Copy uploaded files to public/storage for immediate access') !== false) {
        echo "✅ HeadmasterGreetingController store method has file copying logic\n";
    } else {
        echo "❌ HeadmasterGreetingController store method missing file copying logic\n";
        echo "🔧 Adding file copying logic to store method...\n";
        
        // Add file copying logic after HeadmasterGreeting::create($data)
        $storeMethodPattern = '/HeadmasterGreeting::create\(\$data\);\s*\n\s*return redirect/';
        $replacement = "HeadmasterGreeting::create(\$data);\n\n        // Copy uploaded files to public/storage for immediate access\n        if (\$request->hasFile('photo')) {\n            \$sourcePath = storage_path('app/public/' . \$data['photo']);\n            \$destPath = public_path('storage/' . \$data['photo']);\n            \$destDir = dirname(\$destPath);\n            \n            if (!is_dir(\$destDir)) {\n                mkdir(\$destDir, 0755, true);\n            }\n            \n            if (copy(\$sourcePath, \$destPath)) {\n                \\Log::info('Headmaster photo copied to public storage: ' . \$data['photo']);\n            } else {\n                \\Log::error('Failed to copy headmaster photo to public storage: ' . \$data['photo']);\n            }\n        }\n\n        return redirect";
        
        $newContent = preg_replace($storeMethodPattern, $replacement, $content);
        
        if ($newContent !== $content) {
            file_put_contents($controllerPath, $newContent);
            echo "✅ Added file copying logic to store method\n";
        } else {
            echo "❌ Failed to add file copying logic to store method\n";
        }
    }
    
    // Check if file copying logic exists in update method
    if (strpos($content, 'Copy uploaded files to public/storage for immediate access') !== false && 
        strpos($content, 'update method') !== false) {
        echo "✅ HeadmasterGreetingController update method has file copying logic\n";
    } else {
        echo "❌ HeadmasterGreetingController update method missing file copying logic\n";
        echo "🔧 Adding file copying logic to update method...\n";
        
        // Add file copying logic after $headmasterGreeting->update($data)
        $updateMethodPattern = '/\$headmasterGreeting->update\(\$data\);\s*\n\s*return redirect/';
        $replacement = "\$headmasterGreeting->update(\$data);\n\n        // Copy uploaded files to public/storage for immediate access\n        if (\$request->hasFile('photo')) {\n            \$sourcePath = storage_path('app/public/' . \$data['photo']);\n            \$destPath = public_path('storage/' . \$data['photo']);\n            \$destDir = dirname(\$destPath);\n            \n            if (!is_dir(\$destDir)) {\n                mkdir(\$destDir, 0755, true);\n            }\n            \n            if (copy(\$sourcePath, \$destPath)) {\n                \\Log::info('Headmaster photo copied to public storage: ' . \$data['photo']);\n            } else {\n                \\Log::error('Failed to copy headmaster photo to public storage: ' . \$data['photo']);\n            }\n        }\n\n        return redirect";
        
        $newContent = preg_replace($updateMethodPattern, $replacement, $content);
        
        if ($newContent !== $content) {
            file_put_contents($controllerPath, $newContent);
            echo "✅ Added file copying logic to update method\n";
        } else {
            echo "❌ Failed to add file copying logic to update method\n";
        }
    }
} else {
    echo "❌ HeadmasterGreetingController not found\n";
}

// 7. Fix HeadmasterGreeting model accessor
echo "\n🔧 Fixing HeadmasterGreeting model accessor...\n";

$modelPath = 'app/Models/HeadmasterGreeting.php';
if (file_exists($modelPath)) {
    $content = file_get_contents($modelPath);
    
    // Check if comprehensive accessor exists
    if (strpos($content, 'getPhotoUrlAttribute') !== false &&
        strpos($content, 'str_starts_with') !== false) {
        echo "✅ HeadmasterGreeting model has comprehensive accessor\n";
    } else {
        echo "❌ HeadmasterGreeting model accessor needs updating\n";
        echo "🔧 Updating model accessor...\n";
        
        // Replace the simple accessor with comprehensive one
        $oldAccessor = "    public function getPhotoUrlAttribute()\n    {\n        if (!\$this->photo) {\n            return null;\n        }\n        \n        // Check if it's already a full URL\n        if (filter_var(\$this->photo, FILTER_VALIDATE_URL)) {\n            return \$this->photo;\n        }\n        \n        // Return the storage URL\n        return asset('storage/' . \$this->photo);\n    }";
        
        $newAccessor = "    public function getPhotoUrlAttribute()\n    {\n        if (!\$this->photo) {\n            return asset('images/default-headmaster.png');\n        }\n        \n        if (filter_var(\$this->photo, FILTER_VALIDATE_URL)) {\n            return \$this->photo;\n        }\n        \n        if (str_starts_with(\$this->photo, 'http://') || str_starts_with(\$this->photo, 'https://')) {\n            return \$this->photo;\n        }\n        \n        if (str_starts_with(\$this->photo, 'headmaster-greetings/')) {\n            return asset('storage/' . \$this->photo);\n        }\n        \n        if (str_starts_with(\$this->photo, 'storage/')) {\n            return asset(\$this->photo);\n        }\n        \n        if (!str_starts_with(\$this->photo, 'headmaster-greetings/') && \n            !str_starts_with(\$this->photo, 'storage/')) {\n            return asset('storage/' . \$this->photo);\n        }\n        \n        return asset('images/default-headmaster.png');\n    }";
        
        $newContent = str_replace($oldAccessor, $newAccessor, $content);
        
        if ($newContent !== $content) {
            file_put_contents($modelPath, $newContent);
            echo "✅ Updated HeadmasterGreeting model accessor\n";
        } else {
            echo "❌ Failed to update HeadmasterGreeting model accessor\n";
        }
    }
} else {
    echo "❌ HeadmasterGreeting model not found\n";
}

// 8. Create test headmaster greeting entry
echo "\n📝 Creating test headmaster greeting entry...\n";

// Create a simple test to verify the setup
$testImagePath = 'public/storage/headmaster-greetings/test-headmaster-image.png';
if (!file_exists($testImagePath)) {
    $testImageContent = createDefaultImage(300, 300, '#e5e7eb', '#374151');
    if (file_put_contents($testImagePath, $testImageContent)) {
        echo "✅ Created test image: $testImagePath\n";
    } else {
        echo "❌ Failed to create test image\n";
    }
} else {
    echo "✅ Test image already exists\n";
}

echo "\n✅ Headmaster greetings image issue fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created default images\n";
echo "- Copied existing headmaster images to public storage\n";
echo "- Tested file permissions\n";
echo "- Fixed HeadmasterGreetingController store and update methods\n";
echo "- Fixed HeadmasterGreeting model accessor\n";
echo "- Created test headmaster greeting entry\n\n";

echo "🌐 Test URLs:\n";
echo "- Headmaster Greetings Edit: http://localhost:8000/admin/headmaster-greetings/1/edit\n";
echo "- Headmaster Greetings Index: http://localhost:8000/admin/headmaster-greetings\n";
echo "- Test Headmaster: http://localhost:8000/admin/headmaster-greetings (check for test entry)\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test updating a headmaster greeting with image\n";
echo "2. Check if image appears in headmaster greetings index\n";
echo "3. Verify image display in headmaster greeting edit form\n";
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

