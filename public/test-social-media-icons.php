<?php
// Test Social Media Icons
echo "🧪 Testing Social Media Icons\n";
echo "=============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test SocialMedia model
    $socialMedia = \App\Models\SocialMedia::all();
    echo "✅ SocialMedia model working: " . $socialMedia->count() . " records\n";
    
    foreach ($socialMedia as $social) {
        echo "✅ " . $social->name . " (" . $social->platform_name . "): " . $social->url . "\n";
        echo "   Icon HTML: " . $social->icon_html . "\n";
        echo "   Color: " . $social->color . "\n";
        echo "   Active: " . ($social->is_active ? 'Yes' : 'No') . "\n";
        echo "   Sort Order: " . $social->sort_order . "\n";
        echo "\n";
    }
    
    echo "✅ All social media tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>