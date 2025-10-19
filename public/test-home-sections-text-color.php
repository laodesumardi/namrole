<?php
// Test Home Sections Text Color Update
echo "🧪 Testing Home Sections Text Color Update\n";
echo "==========================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HomeSection model
    $homeSections = \App\Models\HomeSection::orderBy('sort_order')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    echo "\n🎨 Text Color Verification:\n";
    foreach ($homeSections as $section) {
        echo "✅ " . $section->title . " (" . $section->section_key . ")\n";
        echo "   Text Color: " . $section->text_color . "\n";
        echo "   Background Color: " . $section->background_color . "\n";
        echo "   Sort Order: " . $section->sort_order . "\n";
        echo "   Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
        
        // Check if text color is white
        if ($section->text_color === '#ffffff' || $section->text_color === 'white') {
            echo "   ✅ Text color is white\n";
        } else {
            echo "   ❌ Text color is not white: " . $section->text_color . "\n";
        }
        echo "\n";
    }
    
    // Check specific sections
    echo "🔍 Specific Section Checks:\n";
    
    $heroSection = \App\Models\HomeSection::where('section_key', 'hero')->first();
    if ($heroSection) {
        echo "✅ Hero Section: " . $heroSection->title . "\n";
        echo "   Text Color: " . $heroSection->text_color . "\n";
        echo "   Background Color: " . $heroSection->background_color . "\n";
        if ($heroSection->text_color === '#ffffff' || $heroSection->text_color === 'white') {
            echo "   ✅ Hero section text color is white\n";
        } else {
            echo "   ❌ Hero section text color is not white\n";
        }
    } else {
        echo "❌ Hero section not found\n";
    }
    
    $aboutSection = \App\Models\HomeSection::where('section_key', 'about')->first();
    if ($aboutSection) {
        echo "✅ About Section: " . $aboutSection->title . "\n";
        echo "   Text Color: " . $aboutSection->text_color . "\n";
        echo "   Background Color: " . $aboutSection->background_color . "\n";
        if ($aboutSection->text_color === '#ffffff' || $aboutSection->text_color === 'white') {
            echo "   ✅ About section text color is white\n";
        } else {
            echo "   ❌ About section text color is not white\n";
        }
    } else {
        echo "❌ About section not found\n";
    }
    
    echo "✅ All home sections text color tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>