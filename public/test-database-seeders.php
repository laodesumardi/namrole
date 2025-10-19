<?php
// Test Database Seeders
echo "🧪 Testing Database Seeders\n";
echo "============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test database connection
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Test all models
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
    
    // Test specific data
    try {
        $adminUser = \App\Models\User::where('email', 'admin@namrole.sch.id')->first();
        if ($adminUser) {
            echo "✅ Admin user found: " . $adminUser->name . "\n";
        } else {
            echo "⚠️ Admin user not found\n";
        }
    } catch (Exception $e) {
        echo "❌ Admin user test failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $publishedNews = \App\Models\News::published()->first();
        if ($publishedNews) {
            echo "✅ Published news found: " . $publishedNews->title . "\n";
        } else {
            echo "⚠️ No published news found\n";
        }
    } catch (Exception $e) {
        echo "❌ Published news test failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $activeGallery = \App\Models\Gallery::active()->first();
        if ($activeGallery) {
            echo "✅ Active gallery found: " . $activeGallery->title . "\n";
        } else {
            echo "⚠️ No active gallery found\n";
        }
    } catch (Exception $e) {
        echo "❌ Active gallery test failed: " . $e->getMessage() . "\n";
    }
    
    echo "✅ All database seeder tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>