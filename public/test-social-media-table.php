<?php
// Test Social Media Table Structure
echo "🧪 Testing Social Media Table Structure\n";
echo "=======================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test table structure
    $columns = \Illuminate\Support\Facades\DB::select("PRAGMA table_info(social_media)");
    echo "✅ social_media table structure:\n";
    foreach ($columns as $column) {
        echo "   - " . $column->name . " (" . $column->type . ")\n";
    }
    
    // Test SocialMedia model
    $socialMedia = \App\Models\SocialMedia::all();
    echo "✅ SocialMedia model working: " . $socialMedia->count() . " records\n";
    
    foreach ($socialMedia as $social) {
        echo "✅ " . $social->name . " (" . $social->platform_name . "): " . $social->url . "\n";
        echo "   Icon: " . $social->icon . " | Color: " . $social->color . " | Active: " . ($social->is_active ? 'Yes' : 'No') . "\n";
        echo "   Icon HTML: " . $social->icon_html . "\n";
        echo "\n";
    }
    
    echo "✅ All social media table structure tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>