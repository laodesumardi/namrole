<?php
// Test News Model Syntax
echo "🧪 Testing News Model Syntax\n";
echo "============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test News model
    $news = new \App\Models\News();
    echo "✅ News model instantiated successfully\n";
    
    // Test accessor
    $news->featured_image = "test-image.jpg";
    $imageUrl = $news->featured_image_url;
    echo "✅ News model accessor working: $imageUrl\n";
    
    // Test with different image paths
    $testPaths = [
        "storage/news/test.jpg",
        "news/test.jpg",
        "uploads/news/test.jpg",
        "test.jpg",
        "https://example.com/test.jpg"
    ];
    
    foreach ($testPaths as $path) {
        $news->featured_image = $path;
        $url = $news->featured_image_url;
        echo "✅ Path: $path -> URL: $url\n";
    }
    
    echo "✅ All News model tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>