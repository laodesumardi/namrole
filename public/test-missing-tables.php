<?php
// Test Missing Tables
echo "🧪 Testing Missing Tables\n";
echo "=========================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test database connection
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Test all tables
    $tables = \Illuminate\Support\Facades\DB::select("SELECT name FROM sqlite_master WHERE type='table'");
    echo "✅ Found " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        echo "   - " . $table->name . "\n";
    }
    
    // Test Contact model
    try {
        $contact = \App\Models\Contact::active()->first();
        if ($contact) {
            echo "✅ Contact model working: " . $contact->name . "\n";
        } else {
            echo "⚠️ No active contact found\n";
        }
    } catch (Exception $e) {
        echo "❌ Contact model failed: " . $e->getMessage() . "\n";
    }
    
    // Test other models
    $models = [
        'User' => \App\Models\User::class,
        'News' => \App\Models\News::class,
        'Gallery' => \App\Models\Gallery::class,
        'Facility' => \App\Models\Facility::class,
        'SchoolProfile' => \App\Models\SchoolProfile::class,
        'HeadmasterGreeting' => \App\Models\HeadmasterGreeting::class,
        'Contact' => \App\Models\Contact::class,
        'SocialMedia' => \App\Models\SocialMedia::class,
        'VisionMission' => \App\Models\VisionMission::class
    ];
    
    foreach ($models as $modelName => $modelClass) {
        try {
            $count = $modelClass::count();
            echo "✅ $modelName model working: $count records\n";
        } catch (Exception $e) {
            echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
        }
    }
    
    echo "✅ All missing tables tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>