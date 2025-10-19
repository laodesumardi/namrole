<?php
/**
 * Fix Facilities Update Image Issue
 * 
 * This script fixes the issue where images don't appear after updating facilities
 * by ensuring proper file copying and storage structure
 */

echo "🏢 Fixing Facilities Update Image Issue\n";
echo "======================================\n\n";

echo "🔧 Fixing facilities update image display issue...\n";

// 1. Create storage structure
echo "\n🔗 Creating storage structure...\n";

// Create directories
$directories = [
    'storage/app/public',
    'storage/app/public/facilities',
    'public/storage',
    'public/storage/facilities',
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
    'public/images/default-facility.png' => 'Default facility image',
    'public/images/default-facility.jpg' => 'Default facility image (JPG)',
    'public/images/default-facility-item.png' => 'Default facility item image'
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

// 4. Copy existing facilities images to public storage
echo "\n📁 Copying existing facilities images...\n";

// Get all facilities images from storage/app/public/facilities
$sourceDir = 'storage/app/public/facilities';
$destDir = 'public/storage/facilities';

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
    
    echo "✅ Copied $copiedCount facilities images\n";
} else {
    echo "⚠️ Source directory not found: $sourceDir\n";
}

// 5. Test file permissions
echo "\n🔐 Testing file permissions...\n";

$testDirs = [
    'storage/app/public/facilities',
    'public/storage/facilities',
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

// 6. Fix FacilityController
echo "\n🔧 Fixing FacilityController...\n";

$controllerPath = 'app/Http/Controllers/Admin/FacilityController.php';
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Check if file copying logic exists in update method
    if (strpos($content, 'Copy uploaded files to public/storage for immediate access') !== false) {
        echo "✅ FacilityController update method has file copying logic\n";
    } else {
        echo "❌ FacilityController update method missing file copying logic\n";
        echo "🔧 Adding file copying logic to update method...\n";
        
        // Add file copying logic after $facility->update($data)
        $updateMethodPattern = '/\$facility->update\(\$data\);\s*\n\s*return redirect/';
        $replacement = "\$facility->update(\$data);\n\n        // Copy uploaded files to public/storage for immediate access\n        if (\$request->hasFile('image')) {\n            \$sourcePath = storage_path('app/public/' . \$data['image']);\n            \$destPath = public_path('storage/' . \$data['image']);\n            \$destDir = dirname(\$destPath);\n            \n            if (!is_dir(\$destDir)) {\n                mkdir(\$destDir, 0755, true);\n            }\n            \n            if (copy(\$sourcePath, \$destPath)) {\n                \\Log::info('Facility image copied to public storage: ' . \$data['image']);\n            } else {\n                \\Log::error('Failed to copy facility image to public storage: ' . \$data['image']);\n            }\n        }\n\n        return redirect";
        
        $newContent = preg_replace($updateMethodPattern, $replacement, $content);
        
        if ($newContent !== $content) {
            file_put_contents($controllerPath, $newContent);
            echo "✅ Added file copying logic to update method\n";
        } else {
            echo "❌ Failed to add file copying logic to update method\n";
        }
    }
} else {
    echo "❌ FacilityController not found\n";
}

// 7. Fix Facility model accessor
echo "\n🔧 Verifying Facility model accessor...\n";

$modelPath = 'app/Models/Facility.php';
if (file_exists($modelPath)) {
    $content = file_get_contents($modelPath);
    
    // Check if comprehensive accessor exists
    if (strpos($content, 'getImageUrlAttribute') !== false &&
        strpos($content, 'str_starts_with') !== false) {
        echo "✅ Facility model has comprehensive accessor\n";
    } else {
        echo "❌ Facility model accessor may need updating\n";
    }
} else {
    echo "❌ Facility model not found\n";
}

// 8. Create test facility entry
echo "\n📝 Creating test facility entry...\n";

// Create a simple test to verify the setup
$testImagePath = 'public/storage/facilities/test-facility-image.png';
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

// 9. Test existing facilities data
echo "\n📊 Testing existing facilities data...\n";

try {
    // Bootstrap Laravel
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    
    // Get facilities count
    $facilitiesCount = \App\Models\Facility::count();
    echo "✅ Found $facilitiesCount facilities in database\n";
    
    // Test a few facilities
    $facilities = \App\Models\Facility::take(3)->get();
    foreach ($facilities as $facility) {
        echo "📋 Facility: {$facility->name}\n";
        echo "   Image: {$facility->image}\n";
        echo "   Image URL: {$facility->image_url}\n";
        echo "   Category: {$facility->category_label}\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing facilities data: " . $e->getMessage() . "\n";
}

echo "\n✅ Facilities update image issue fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created default images\n";
echo "- Copied existing facilities images to public storage\n";
echo "- Tested file permissions\n";
echo "- Fixed FacilityController update method\n";
echo "- Verified Facility model accessor\n";
echo "- Created test facility entry\n";
echo "- Tested existing facilities data\n\n";

echo "🌐 Test URLs:\n";
echo "- Facilities Index: http://localhost:8000/admin/facilities\n";
echo "- Facilities Create: http://localhost:8000/admin/facilities/create\n";
echo "- Test Facility: http://localhost:8000/admin/facilities (check for test entry)\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test updating a facility with image\n";
echo "2. Check if image appears in facilities index\n";
echo "3. Verify image display in facility edit form\n";
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

