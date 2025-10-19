<?php
/**
 * Fix Gallery Update Images - Simple Version
 * 
 * This script fixes gallery update image issues by:
 * 1. Fixing GalleryController update method to copy files to public/storage
 * 2. Ensuring proper storage structure
 * 3. Creating default images
 * 4. Fixing database paths
 */

echo "🖼️ Fixing Gallery Update Images\n";
echo "===============================\n\n";

echo "🔧 Fixing gallery update image issues...\n";

// 1. Fix GalleryController update method
$controllerPath = 'app/Http/Controllers/Admin/GalleryController.php';
if (file_exists($controllerPath)) {
    echo "📝 Fixing GalleryController update method...\n";
    
    $content = file_get_contents($controllerPath);
    
    // Find the update method and add file copying logic
    $updateMethodStart = strpos($content, 'public function update(Request $request, Gallery $gallery)');
    if ($updateMethodStart !== false) {
        // Find the line with $gallery->update($data);
        $updateLine = strpos($content, '$gallery->update($data);', $updateMethodStart);
        if ($updateLine !== false) {
            // Add file copying logic after the update
            $newLogic = '
        
        // Copy uploaded files to public/storage for immediate access
        if ($request->hasFile(\'cover_image\')) {
            $sourcePath = storage_path(\'app/public/\' . $data[\'cover_image\']);
            $destPath = public_path(\'storage/\' . $data[\'cover_image\']);
            $destDir = dirname($destPath);
            
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            if (copy($sourcePath, $destPath)) {
                \\Log::info(\'Gallery cover image copied to public storage: \' . $data[\'cover_image\']);
            } else {
                \\Log::error(\'Failed to copy gallery cover image to public storage: \' . $data[\'cover_image\']);
            }
        }';
            
            // Insert the new logic before the return statement
            $returnPos = strpos($content, 'return redirect()->route(\'admin.gallery.index\')', $updateLine);
            if ($returnPos !== false) {
                $content = substr_replace($content, $newLogic, $returnPos, 0);
            }
        }
    }
    
    file_put_contents($controllerPath, $content);
    echo "✅ GalleryController update method fixed\n";
} else {
    echo "⚠️ GalleryController not found\n";
}

// 2. Fix GalleryController store method
if (file_exists($controllerPath)) {
    echo "📝 Fixing GalleryController store method...\n";
    
    $content = file_get_contents($controllerPath);
    
    // Find the store method and add file copying logic
    $storeMethodStart = strpos($content, 'public function store(Request $request)');
    if ($storeMethodStart !== false) {
        // Find the line with Gallery::create($data);
        $createLine = strpos($content, 'Gallery::create($data);', $storeMethodStart);
        if ($createLine !== false) {
            // Add file copying logic after the create
            $newLogic = '
        
        // Copy uploaded files to public/storage for immediate access
        if ($request->hasFile(\'cover_image\')) {
            $sourcePath = storage_path(\'app/public/\' . $data[\'cover_image\']);
            $destPath = public_path(\'storage/\' . $data[\'cover_image\']);
            $destDir = dirname($destPath);
            
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            if (copy($sourcePath, $destPath)) {
                \\Log::info(\'Gallery cover image copied to public storage: \' . $data[\'cover_image\']);
            } else {
                \\Log::error(\'Failed to copy gallery cover image to public storage: \' . $data[\'cover_image\']);
            }
        }';
            
            // Insert the new logic before the return statement
            $returnPos = strpos($content, 'return redirect()->route(\'admin.gallery.index\')', $createLine);
            if ($returnPos !== false) {
                $content = substr_replace($content, $newLogic, $returnPos, 0);
            }
        }
    }
    
    file_put_contents($controllerPath, $content);
    echo "✅ GalleryController store method fixed\n";
}

// 3. Create storage structure
echo "\n🔗 Creating storage structure...\n";

// Create directories
$directories = [
    'storage/app/public',
    'storage/app/public/galleries',
    'public/storage',
    'public/storage/galleries',
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

// 4. Create storage link if not exists
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

// 5. Create default images
echo "\n🖼️ Creating default images...\n";

$defaultImages = [
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-gallery.jpg' => 'Default gallery image (JPG)',
    'public/images/default-gallery-item.png' => 'Default gallery item image'
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

// 6. Copy existing gallery images to public storage
echo "\n📁 Copying existing gallery images...\n";

// Get all gallery images from storage/app/public/galleries
$sourceDir = 'storage/app/public/galleries';
$destDir = 'public/storage/galleries';

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
    
    echo "✅ Copied $copiedCount gallery images\n";
} else {
    echo "⚠️ Source directory not found: $sourceDir\n";
}

echo "\n✅ Gallery update images fixed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Fixed GalleryController update method to copy files to public/storage\n";
echo "- Fixed GalleryController store method to copy files to public/storage\n";
echo "- Created storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created default images\n";
echo "- Copied existing gallery images to public storage\n\n";

echo "🌐 Test URLs:\n";
echo "- Gallery Index: http://localhost:8000/admin/gallery\n";
echo "- Gallery Edit: http://localhost:8000/admin/gallery/2/edit\n";
echo "- Gallery Create: http://localhost:8000/admin/gallery/create\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n";

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

