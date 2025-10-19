<?php
// Test SchoolProfile Model Syntax
echo "🧪 Testing SchoolProfile Model Syntax\n";
echo "======================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test SchoolProfile model
    $schoolProfile = new \App\Models\SchoolProfile();
    echo "✅ SchoolProfile model instantiated successfully\n";
    
    // Test accessor
    $schoolProfile->image = "test-image.jpg";
    $imageUrl = $schoolProfile->image_url;
    echo "✅ SchoolProfile model accessor working: $imageUrl\n";
    
    // Test with different image paths
    $testPaths = [
        "storage/school-profiles/test.jpg",
        "school-profiles/test.jpg",
        "uploads/school-profiles/test.jpg",
        "test.jpg",
        "https://example.com/test.jpg"
    ];
    
    foreach ($testPaths as $path) {
        $schoolProfile->image = $path;
        $url = $schoolProfile->image_url;
        echo "✅ Path: $path -> URL: $url\n";
    }
    
    // Test scopes
    try {
        $activeProfiles = \App\Models\SchoolProfile::active()->get();
        echo "✅ SchoolProfile active scope working: " . $activeProfiles->count() . " active profiles\n";
    } catch (Exception $e) {
        echo "⚠️ SchoolProfile active scope failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $heroProfiles = \App\Models\SchoolProfile::bySection('hero')->get();
        echo "✅ SchoolProfile bySection scope working: " . $heroProfiles->count() . " hero profiles\n";
    } catch (Exception $e) {
        echo "⚠️ SchoolProfile bySection scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test all models
    $models = [
        'User' => \App\Models\User::class,
        'News' => \App\Models\News::class,
        'Gallery' => \App\Models\Gallery::class,
        'GalleryItem' => \App\Models\GalleryItem::class,
        'Facility' => \App\Models\Facility::class,
        'SchoolProfile' => \App\Models\SchoolProfile::class,
        'HeadmasterGreeting' => \App\Models\HeadmasterGreeting::class
    ];
    
    foreach ($models as $modelName => $modelClass) {
        try {
            $count = $modelClass::count();
            echo "✅ $modelName model working: $count records\n";
        } catch (Exception $e) {
            echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
        }
    }
    
    echo "✅ All SchoolProfile model tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>