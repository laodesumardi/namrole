<?php
/**
 * Fix School Profile Database Paths
 * 
 * This script fixes the image paths stored in the database for school profiles
 * to ensure they work correctly with Storage::url()
 */

echo "🏫 Fixing School Profile Database Paths\n";
echo "======================================\n\n";

echo "🔧 Fixing school profile database image paths...\n";

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

// 2. Get all school profiles
echo "\n📊 Getting school profiles from database...\n";

try {
    $schoolProfiles = \App\Models\SchoolProfile::all();
    echo "✅ Found " . $schoolProfiles->count() . " school profiles\n";
    
    foreach ($schoolProfiles as $profile) {
        echo "📝 Profile ID: {$profile->id}\n";
        echo "   Section Key: " . ($profile->section_key ?? 'None') . "\n";
        echo "   Title: " . ($profile->title ?? 'No title') . "\n";
        echo "   Current Image: " . ($profile->image ?? 'No image') . "\n";
        
        // Fix image path if needed
        if ($profile->image) {
            $originalImage = $profile->image;
            $fixedImage = fixImagePath($originalImage);
            
            if ($originalImage !== $fixedImage) {
                echo "   🔧 Fixing path: '$originalImage' -> '$fixedImage'\n";
                $profile->image = $fixedImage;
                $profile->save();
                echo "   ✅ Path fixed and saved\n";
            } else {
                echo "   ✅ Path already correct\n";
            }
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error getting school profiles: " . $e->getMessage() . "\n";
}

// 3. Test image URLs
echo "\n🔍 Testing image URLs...\n";

try {
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    
    foreach ($schoolProfiles as $profile) {
        echo "📝 Testing Profile ID: {$profile->id}\n";
        echo "   Image: {$profile->image}\n";
        
        // Test different URL generation methods
        $storageUrl = \Illuminate\Support\Facades\Storage::url($profile->image);
        $assetUrl = asset('storage/' . $profile->image);
        $directAssetUrl = asset($profile->image);
        
        echo "   Storage::url(): $storageUrl\n";
        echo "   asset('storage/'): $assetUrl\n";
        echo "   asset(direct): $directAssetUrl\n";
        
        // Test if file exists
        $storagePath = storage_path('app/public/' . $profile->image);
        $publicPath = public_path('storage/' . $profile->image);
        $directPath = public_path($profile->image);
        
        echo "   Storage path exists: " . (file_exists($storagePath) ? 'Yes' : 'No') . "\n";
        echo "   Public path exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . "\n";
        echo "   Direct path exists: " . (file_exists($directPath) ? 'Yes' : 'No') . "\n";
        
        // Copy file if needed
        if (file_exists($storagePath) && !file_exists($publicPath)) {
            $destDir = dirname($publicPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (copy($storagePath, $publicPath)) {
                echo "   ✅ Copied to public storage\n";
            } else {
                echo "   ❌ Failed to copy to public storage\n";
            }
        }
        
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error testing image URLs: " . $e->getMessage() . "\n";
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

// 5. Test model accessor
echo "\n🧪 Testing model accessor...\n";

try {
    $schoolProfiles = \App\Models\SchoolProfile::all();
    
    foreach ($schoolProfiles as $profile) {
        echo "📝 Testing Profile ID: {$profile->id}\n";
        echo "   Original image: " . ($profile->image ?? 'No image') . "\n";
        echo "   Accessor URL: " . $profile->image_url . "\n";
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Error testing model accessor: " . $e->getMessage() . "\n";
}

echo "\n✅ School profile database paths fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Fixed image paths in database\n";
echo "- Tested image URLs\n";
echo "- Copied missing files to public storage\n";
echo "- Created missing default images\n";
echo "- Tested model accessor\n\n";

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

// Helper function to fix image paths
function fixImagePath($imagePath) {
    if (!$imagePath) {
        return $imagePath;
    }
    
    // If it's already a full URL, return as is
    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
        return $imagePath;
    }
    
    // If it starts with http:// or https://, return as is
    if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
        return $imagePath;
    }
    
    // If it starts with storage/school-profiles/, it's already correct
    if (str_starts_with($imagePath, 'storage/school-profiles/')) {
        return $imagePath;
    }
    
    // If it starts with school-profiles/, add storage/ prefix
    if (str_starts_with($imagePath, 'school-profiles/')) {
        return 'storage/' . $imagePath;
    }
    
    // If it starts with uploads/school-profiles/, change to storage/school-profiles/
    if (str_starts_with($imagePath, 'uploads/school-profiles/')) {
        return str_replace('uploads/school-profiles/', 'storage/school-profiles/', $imagePath);
    }
    
    // If it's just a filename, add the full path
    if (!str_contains($imagePath, '/')) {
        return 'storage/school-profiles/' . $imagePath;
    }
    
    // If it doesn't start with storage/, add it
    if (!str_starts_with($imagePath, 'storage/')) {
        return 'storage/' . $imagePath;
    }
    
    return $imagePath;
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

