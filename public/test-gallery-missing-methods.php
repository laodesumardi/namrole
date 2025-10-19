<?php
// Test Gallery Missing Methods
echo "🧪 Testing Gallery Missing Methods\n";
echo "===================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test Gallery model
    $galleries = \App\Models\Gallery::all();
    echo "✅ Gallery model working: " . $galleries->count() . " records\n";
    
    foreach ($galleries as $gallery) {
        echo "✅ " . $gallery->title . " (ID: " . $gallery->id . ")\n";
        echo "   - getItemCount(): " . $gallery->getItemCount() . "\n";
        echo "   - itemCount(): " . $gallery->itemCount() . "\n";
        echo "   - item_count: " . $gallery->item_count . "\n";
        echo "   - hasItems(): " . ($gallery->hasItems() ? 'Yes' : 'No') . "\n";
        echo "   - isPublished(): " . ($gallery->isPublished() ? 'Yes' : 'No') . "\n";
        echo "   - isActive(): " . ($gallery->isActive() ? 'Yes' : 'No') . "\n";
        echo "   - isFeatured(): " . ($gallery->isFeatured() ? 'Yes' : 'No') . "\n";
        echo "   - Cover Image: " . $gallery->cover_image_url . "\n";
        echo "   - Category: " . $gallery->category_label . "\n";
        echo "   - Type: " . $gallery->type_label . "\n";
        echo "   - Status: " . $gallery->status_label . "\n";
        echo "\n";
    }
    
    echo "✅ All gallery missing methods tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>