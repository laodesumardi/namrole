<?php
// Test Home Sections Data Fixed
echo "🧪 Testing Home Sections Data Fixed\n";
echo "===================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HomeSection model
    $homeSections = \App\Models\HomeSection::all();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    foreach ($homeSections as $section) {
        echo "✅ " . $section->title . "\n";
        echo "   Content: " . substr($section->content, 0, 100) . "...\n";
        echo "   Image: " . $section->image . "\n";
        echo "   Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
        echo "\n";
    }
    
    // Test image files
    echo "🖼️ Testing image files:\n";
    $imageFiles = [
        'default-hero-bg.png',
        'default-about-bg.png',
        'default-facilities-bg.png',
        'default-gallery-bg.png',
        'default-news-bg.png',
        'default-achievement-bg.png',
        'default-staff-bg.png',
        'default-ppdb-bg.png',
        'default-testimonial-bg.png',
        'default-contact-bg.png'
    ];
    
    foreach ($imageFiles as $imageFile) {
        $publicPath = "images/$imageFile";
        $storagePath = "storage/home-sections/$imageFile";
        
        if (file_exists($publicPath)) {
            echo "✅ Public image exists: $imageFile\n";
        } else {
            echo "❌ Public image missing: $imageFile\n";
        }
        
        if (file_exists($storagePath)) {
            echo "✅ Storage image exists: $imageFile\n";
        } else {
            echo "❌ Storage image missing: $imageFile\n";
        }
    }
    
    echo "✅ All home sections data fixed tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>