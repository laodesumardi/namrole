<?php
// Test Force White Text Hero Section
echo "🧪 Testing Force White Text Hero Section\n";
echo "=======================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test Hero Section specifically
    $heroSection = \App\Models\HomeSection::where('section_key', 'hero')->first();
    if ($heroSection) {
        echo "✅ Hero Section Found:\n";
        echo "   Title: " . $heroSection->title . "\n";
        echo "   Subtitle: " . $heroSection->subtitle . "\n";
        echo "   Text Color: " . $heroSection->text_color . "\n";
        echo "   Background Color: " . $heroSection->background_color . "\n";
        echo "   Active: " . ($heroSection->is_active ? 'Yes' : 'No') . "\n";
        
        // Check if text color contains white
        $textColor = strtolower($heroSection->text_color);
        if (strpos($textColor, 'white') !== false || 
            strpos($textColor, '#fff') !== false || 
            strpos($textColor, '#ffffff') !== false ||
            strpos($textColor, '255') !== false) {
            echo "   ✅ Hero section text color appears to be white\n";
        } else {
            echo "   ❌ Hero section text color may not be white: " . $heroSection->text_color . "\n";
        }
    } else {
        echo "❌ Hero section not found\n";
    }
    
    // Test all sections
    $homeSections = \App\Models\HomeSection::orderBy('sort_order')->get();
    echo "\n✅ All Home Sections (" . $homeSections->count() . " total):\n";
    
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Text Color: " . $section->text_color . "\n";
        echo "     Background Color: " . $section->background_color . "\n";
        echo "\n";
    }
    
    echo "✅ Force white text hero section test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>