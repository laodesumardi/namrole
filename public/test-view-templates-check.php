<?php
// Test View Templates Check
echo "🧪 Testing View Templates Check\n";
echo "===============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test Hero Section
    $heroSection = \App\Models\HomeSection::where('section_key', 'hero')->first();
    if ($heroSection) {
        echo "✅ Hero Section Found:\n";
        echo "   Title: " . $heroSection->title . "\n";
        echo "   Subtitle: " . $heroSection->subtitle . "\n";
        echo "   Text Color: " . $heroSection->text_color . "\n";
        echo "   Background Color: " . $heroSection->background_color . "\n";
        echo "   Active: " . ($heroSection->is_active ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ Hero section not found\n";
    }
    
    // Check if custom files exist
    $customFiles = [
        'public/css/force-white-text.css',
        'public/js/force-white-text.js'
    ];
    
    foreach ($customFiles as $file) {
        if (file_exists($file)) {
            echo "✅ Custom file exists: $file\n";
        } else {
            echo "❌ Custom file missing: $file\n";
        }
    }
    
    echo "✅ View templates check test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>