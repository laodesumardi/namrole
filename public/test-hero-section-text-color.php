<?php
// Test Hero Section Text Color Fix
echo "🧪 Testing Hero Section Text Color Fix\n";
echo "======================================\n\n";

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
        
        if ($heroSection->text_color === '#ffffff' || $heroSection->text_color === 'white') {
            echo "   ✅ Hero section text color is white\n";
        } else {
            echo "   ❌ Hero section text color is not white: " . $heroSection->text_color . "\n";
        }
    } else {
        echo "❌ Hero section not found\n";
    }
    
    // Test all sections
    $homeSections = \App\Models\HomeSection::orderBy('sort_order')->get();
    echo "\n✅ All Home Sections (" . $homeSections->count() . " total):\n";
    
    $whiteTextCount = 0;
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Text Color: " . $section->text_color . "\n";
        echo "     Background Color: " . $section->background_color . "\n";
        
        if ($section->text_color === '#ffffff' || $section->text_color === 'white') {
            echo "     ✅ Text color is white\n";
            $whiteTextCount++;
        } else {
            echo "     ❌ Text color is not white\n";
        }
        echo "\n";
    }
    
    echo "📊 Summary:\n";
    echo "   Total sections: " . $homeSections->count() . "\n";
    echo "   Sections with white text: $whiteTextCount\n";
    echo "   Sections without white text: " . ($homeSections->count() - $whiteTextCount) . "\n";
    
    if ($whiteTextCount === $homeSections->count()) {
        echo "   ✅ All sections have white text!\n";
    } else {
        echo "   ⚠️ Some sections still need white text\n";
    }
    
    echo "✅ Hero section text color fix test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>