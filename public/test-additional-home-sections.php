<?php
// Test Additional Home Sections
echo "🧪 Testing Additional Home Sections\n";
echo "===================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HomeSection model
    $homeSections = \App\Models\HomeSection::orderBy('sort_order')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    echo "\n📋 All Home Sections:\n";
    foreach ($homeSections as $section) {
        echo "✅ " . $section->title . " (" . $section->section_key . ")\n";
        echo "   Subtitle: " . $section->subtitle . "\n";
        echo "   Description: " . substr($section->description, 0, 100) . "...\n";
        echo "   Image: " . $section->image . "\n";
        echo "   Button: " . $section->button_text . " -> " . $section->button_link . "\n";
        echo "   Background Color: " . $section->background_color . "\n";
        echo "   Text Color: " . $section->text_color . "\n";
        echo "   Sort Order: " . $section->sort_order . "\n";
        echo "   Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
        echo "\n";
    }
    
    // Test image files
    echo "🖼️ Testing additional image files:\n";
    $imageFiles = [
        'default-mission-bg.png',
        'default-values-bg.png',
        'default-history-bg.png',
        'default-principal-bg.png',
        'default-academic-bg.png',
        'default-extracurricular-bg.png',
        'default-facilities-detail-bg.png',
        'default-achievements-bg.png',
        'default-alumni-bg.png',
        'default-partnership-bg.png',
        'default-calendar-bg.png',
        'default-downloads-bg.png'
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
    
    echo "✅ All additional home sections tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>