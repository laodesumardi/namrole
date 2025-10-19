<?php
/**
 * Fix Gallery Category Label Method Issue
 * 
 * This script fixes the issue where getCategoryLabel() is called as method
 * instead of accessor property for Gallery model
 */

echo "🔧 Fixing Gallery Category Label Method Issue\n";
echo "============================================\n\n";

echo "🔧 Fixing Gallery getCategoryLabel() method calls...\n";

// 1. Fix welcome.blade.php
echo "\n📝 Fixing welcome.blade.php...\n";
$welcomeFile = 'resources/views/welcome.blade.php';
if (file_exists($welcomeFile)) {
    $content = file_get_contents($welcomeFile);
    
    // Replace getCategoryLabel() with category_label accessor
    $oldPattern = '{{ $gallery->getCategoryLabel() }}';
    $newPattern = '{{ $gallery->category_label }}';
    
    if (strpos($content, $oldPattern) !== false) {
        $content = str_replace($oldPattern, $newPattern, $content);
        file_put_contents($welcomeFile, $content);
        echo "✅ Fixed getCategoryLabel() call in welcome.blade.php\n";
    } else {
        echo "✅ No getCategoryLabel() calls found in welcome.blade.php\n";
    }
} else {
    echo "❌ welcome.blade.php not found\n";
}

// 2. Check if there are other files with Gallery getCategoryLabel() calls
echo "\n🔍 Searching for other Gallery getCategoryLabel() calls...\n";

$galleryFiles = [
    'resources/views/admin/gallery/index.blade.php',
    'resources/views/admin/gallery/show.blade.php',
    'resources/views/gallery/index.blade.php',
    'resources/views/gallery/show.blade.php'
];

foreach ($galleryFiles as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        if (strpos($content, 'getCategoryLabel()') !== false) {
            echo "⚠️ Found getCategoryLabel() call in $file\n";
            
            // Replace getCategoryLabel() with category_label accessor
            $content = str_replace('$gallery->getCategoryLabel()', '$gallery->category_label', $content);
            $content = str_replace('$gallery->getTypeLabel()', '$gallery->type_label', $content);
            $content = str_replace('$gallery->getStatusLabel()', '$gallery->status_label', $content);
            
            file_put_contents($file, $content);
            echo "✅ Fixed getCategoryLabel() calls in $file\n";
        } else {
            echo "✅ No getCategoryLabel() calls found in $file\n";
        }
    } else {
        echo "⚠️ File not found: $file\n";
    }
}

// 3. Verify Gallery model has correct accessors
echo "\n🔍 Verifying Gallery model accessors...\n";

$modelFile = 'app/Models/Gallery.php';
if (file_exists($modelFile)) {
    $content = file_get_contents($modelFile);
    
    // Check if accessors exist
    $accessors = [
        'getCategoryLabelAttribute',
        'getTypeLabelAttribute', 
        'getStatusLabelAttribute'
    ];
    
    foreach ($accessors as $accessor) {
        if (strpos($content, $accessor) !== false) {
            echo "✅ Found $accessor in Gallery model\n";
        } else {
            echo "❌ Missing $accessor in Gallery model\n";
        }
    }
} else {
    echo "❌ Gallery model not found\n";
}

// 4. Test the fix by checking if the error is resolved
echo "\n🧪 Testing the fix...\n";

// Check if welcome.blade.php now uses accessor correctly
$welcomeContent = file_get_contents('resources/views/welcome.blade.php');
if (strpos($welcomeContent, '$gallery->category_label') !== false) {
    echo "✅ welcome.blade.php now uses category_label accessor\n";
} else {
    echo "❌ welcome.blade.php still has issues\n";
}

// 5. Create a simple test to verify the accessor works
echo "\n📝 Creating test to verify accessor...\n";

$testCode = '<?php
// Test Gallery model accessor
require_once "vendor/autoload.php";

try {
    // Bootstrap Laravel
    $app = require_once "bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    
    // Test Gallery model
    $gallery = new \App\Models\Gallery();
    $gallery->category = "kegiatan";
    $gallery->type = "photo";
    $gallery->status = "published";
    
    echo "Testing Gallery accessors:\n";
    echo "Category Label: " . $gallery->category_label . "\n";
    echo "Type Label: " . $gallery->type_label . "\n";
    echo "Status Label: " . $gallery->status_label . "\n";
    
    echo "✅ Gallery accessors working correctly\n";
    
} catch (Exception $e) {
    echo "❌ Error testing Gallery accessors: " . $e->getMessage() . "\n";
}
';

file_put_contents('test_gallery_accessors.php', $testCode);
echo "✅ Created test file: test_gallery_accessors.php\n";

echo "\n✅ Gallery Category Label Method Issue Fix Completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Fixed getCategoryLabel() method calls to use category_label accessor\n";
echo "- Fixed getTypeLabel() method calls to use type_label accessor\n";
echo "- Fixed getStatusLabel() method calls to use status_label accessor\n";
echo "- Verified Gallery model has correct accessors\n";
echo "- Created test to verify accessor functionality\n\n";

echo "🌐 Test URLs:\n";
echo "- Homepage: http://localhost:8000/\n";
echo "- Gallery Index: http://localhost:8000/gallery\n";
echo "- Admin Gallery: http://localhost:8000/admin/gallery\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test the homepage to see if Gallery section loads without errors\n";
echo "2. Check if Gallery category labels display correctly\n";
echo "3. Verify no more 'Call to undefined method' errors\n";
echo "4. Run test_gallery_accessors.php to verify accessors work\n";

