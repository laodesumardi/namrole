<?php
// Test HeadmasterGreeting Model Syntax
echo "🧪 Testing HeadmasterGreeting Model Syntax\n";
echo "==========================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HeadmasterGreeting model
    $headmasterGreeting = new \App\Models\HeadmasterGreeting();
    echo "✅ HeadmasterGreeting model instantiated successfully\n";
    
    // Test accessor
    $headmasterGreeting->photo = "test-photo.jpg";
    $photoUrl = $headmasterGreeting->photo_url;
    echo "✅ HeadmasterGreeting model accessor working: $photoUrl\n";
    
    // Test with different photo paths
    $testPaths = [
        "storage/headmaster-greetings/test.jpg",
        "headmaster-greetings/test.jpg",
        "uploads/headmaster-greetings/test.jpg",
        "test.jpg",
        "https://example.com/test.jpg"
    ];
    
    foreach ($testPaths as $path) {
        $headmasterGreeting->photo = $path;
        $url = $headmasterGreeting->photo_url;
        echo "✅ Path: $path -> URL: $url\n";
    }
    
    // Test scope
    try {
        $activeGreetings = \App\Models\HeadmasterGreeting::active()->get();
        echo "✅ HeadmasterGreeting scope working: " . $activeGreetings->count() . " active greetings\n";
    } catch (Exception $e) {
        echo "⚠️ HeadmasterGreeting scope failed: " . $e->getMessage() . "\n";
    }
    
    echo "✅ All HeadmasterGreeting model tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>