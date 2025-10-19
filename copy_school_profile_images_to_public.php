<?php
/**
 * Copy School Profile Images to Public Storage
 * 
 * This script copies school profile images from storage/app/public to public/storage
 * to ensure they are accessible via web
 */

echo "🏫 Copying School Profile Images to Public Storage\n";
echo "==================================================\n\n";

echo "🔧 Copying school profile images to public storage...\n";

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

// 2. Get all school profiles with images
echo "\n📊 Getting school profiles with images...\n";

try {
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    echo "✅ Found " . $schoolProfiles->count() . " school profiles with images\n";
    
    foreach ($schoolProfiles as $profile) {
        echo "📝 Profile ID: {$profile->id}\n";
        echo "   Section Key: " . ($profile->section_key ?? 'None') . "\n";
        echo "   Title: " . ($profile->title ?? 'No title') . "\n";
        echo "   Image: {$profile->image}\n";
        
        // Determine source and destination paths
        $imagePath = $profile->image;
        
        // Remove storage/ prefix if present
        if (str_starts_with($imagePath, 'storage/')) {
            $imagePath = substr($imagePath, 8); // Remove 'storage/' prefix
        }
        
        $sourcePath = storage_path('app/public/' . $imagePath);
        $destPath = public_path('storage/' . $imagePath);
        $destDir = dirname($destPath);
        
        echo "   Source: $sourcePath\n";
        echo "   Destination: $destPath\n";
        
        // Check if source file exists
        if (file_exists($sourcePath)) {
            echo "   ✅ Source file exists\n";
            
            // Create destination directory if it doesn't exist
            if (!is_dir($destDir)) {
                if (mkdir($destDir, 0755, true)) {
                    echo "   ✅ Created destination directory\n";
                } else {
                    echo "   ❌ Failed to create destination directory\n";
                    continue;
                }
            }
            
            // Copy file
            if (copy($sourcePath, $destPath)) {
                echo "   ✅ File copied successfully\n";
            } else {
                echo "   ❌ Failed to copy file\n";
            }
        } else {
            echo "   ❌ Source file does not exist\n";
            
            // Try alternative paths
            $altPaths = [
                storage_path('app/public/school-profiles/' . basename($imagePath)),
                public_path('uploads/school-profiles/' . basename($imagePath)),
                public_path('storage/school-profiles/' . basename($imagePath))
            ];
            
            $found = false;
            foreach ($altPaths as $altPath) {
                if (file_exists($altPath)) {
                    echo "   🔍 Found in alternative path: $altPath\n";
                    
                    // Create destination directory if it doesn't exist
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0755, true);
                    }
                    
                    // Copy file
                    if (copy($altPath, $destPath)) {
                        echo "   ✅ File copied from alternative path\n";
                        $found = true;
                        break;
                    }
                }
            }
            
            if (!$found) {
                echo "   ⚠️ File not found in any location\n";
            }
        }
        
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error processing school profiles: " . $e->getMessage() . "\n";
}

// 3. Test copied images
echo "\n🔍 Testing copied images...\n";

try {
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    
    foreach ($schoolProfiles as $profile) {
        echo "📝 Testing Profile ID: {$profile->id}\n";
        echo "   Image: {$profile->image}\n";
        echo "   Accessor URL: " . $profile->image_url . "\n";
        
        // Test if file exists in public storage
        $imagePath = $profile->image;
        if (str_starts_with($imagePath, 'storage/')) {
            $imagePath = substr($imagePath, 8);
        }
        
        $publicPath = public_path('storage/' . $imagePath);
        if (file_exists($publicPath)) {
            echo "   ✅ File exists in public storage\n";
        } else {
            echo "   ❌ File not found in public storage\n";
        }
        
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error testing copied images: " . $e->getMessage() . "\n";
}

// 4. Create missing default images
echo "\n🖼️ Creating missing default images...\n";

$defaultImages = [
    'public/images/default-school-profile.png' => 'Default school profile image',
    'public/images/default-section.png' => 'Default section image',
    'public/images/default-hero.png' => 'Default hero image',
    'public/images/default-struktur.png' => 'Default struktur image'
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

echo "\n✅ School profile images copy completed!\n";
echo "🔧 Key actions performed:\n";
echo "- Copied school profile images to public storage\n";
echo "- Tested copied images\n";
echo "- Created missing default images\n\n";

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

