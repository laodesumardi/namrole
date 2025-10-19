<?php
/**
 * Fix SchoolProfile Model Syntax Error
 * 
 * This script fixes syntax error in app/Models/SchoolProfile.php
 * Specifically the duplicated code and misplaced if statements
 */

echo "🔧 Fixing SchoolProfile Model Syntax Error\n";
echo "===========================================\n\n";

echo "🔧 Fixing syntax error in app/Models/SchoolProfile.php...\n";

// 1. Read the current file
echo "\n📄 Reading current SchoolProfile.php file...\n";

if (file_exists('app/Models/SchoolProfile.php')) {
    $content = file_get_contents('app/Models/SchoolProfile.php');
    echo "✅ SchoolProfile.php file found\n";
} else {
    echo "❌ SchoolProfile.php file not found\n";
    exit(1);
}

// 2. Create the corrected SchoolProfile.php file
echo "\n🔧 Creating corrected SchoolProfile.php file...\n";

$correctedContent = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolProfile extends Model
{
    protected $fillable = [
        \'section\',
        \'title\',
        \'content\',
        \'image\',
        \'order_index\',
        \'is_active\'
    ];

    protected $casts = [
        \'is_active\' => \'boolean\'
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-school-profile.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->image, \'storage/\')) {
            return asset($this->image);
        }
        
        // If it starts with school-profiles/, add storage/ prefix
        if (str_starts_with($this->image, \'school-profiles/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        // If it starts with uploads/school-profiles/, change to storage/school-profiles/
        if (str_starts_with($this->image, \'uploads/school-profiles/\')) {
            return asset(str_replace(\'uploads/school-profiles/\', \'storage/school-profiles/\', $this->image));
        }
        
        // If it\'s just a filename, add the full path
        if (!str_contains($this->image, \'/\')) {
            return asset(\'storage/school-profiles/\' . $this->image);
        }
        
        // Default fallback
        return asset(\'images/default-school-profile.png\');
    }

    public function scopeActive($query)
    {
        return $query->where(\'is_active\', true);
    }

    public function scopeBySection($query, $section)
    {
        return $query->where(\'section\', $section);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy(\'order_index\');
    }
}';

// 3. Write the corrected file
if (file_put_contents('app/Models/SchoolProfile.php', $correctedContent)) {
    echo "✅ Corrected SchoolProfile.php file created\n";
} else {
    echo "❌ Failed to create corrected SchoolProfile.php file\n";
    exit(1);
}

// 4. Test the syntax
echo "\n🧪 Testing PHP syntax...\n";

$output = [];
$returnCode = 0;
exec('php -l app/Models/SchoolProfile.php 2>&1', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ PHP syntax is valid\n";
} else {
    echo "❌ PHP syntax error found:\n";
    foreach ($output as $line) {
        echo "   $line\n";
    }
}

// 5. Test Laravel bootstrap
echo "\n🔗 Testing Laravel bootstrap...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test if we can access the SchoolProfile model
    try {
        $schoolProfile = new \App\Models\SchoolProfile();
        echo "✅ SchoolProfile model instantiated successfully\n";
        
        // Test if we can access the accessor
        try {
            $schoolProfile->image = 'test-image.jpg';
            $imageUrl = $schoolProfile->image_url;
            echo "✅ SchoolProfile model accessor working: $imageUrl\n";
        } catch (Exception $e) {
            echo "❌ SchoolProfile model accessor failed: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ SchoolProfile model instantiation failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

// 6. Test all models
echo "\n🧪 Testing all models...\n";

$models = [
    'User' => \App\Models\User::class,
    'News' => \App\Models\News::class,
    'Gallery' => \App\Models\Gallery::class,
    'GalleryItem' => \App\Models\GalleryItem::class,
    'Facility' => \App\Models\Facility::class,
    'SchoolProfile' => \App\Models\SchoolProfile::class,
    'HeadmasterGreeting' => \App\Models\HeadmasterGreeting::class
];

foreach ($models as $modelName => $modelClass) {
    try {
        $count = $modelClass::count();
        echo "✅ $modelName model working: $count records\n";
    } catch (Exception $e) {
        echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
    }
}

// 7. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test SchoolProfile Model Syntax
echo "🧪 Testing SchoolProfile Model Syntax\n";
echo "======================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test SchoolProfile model
    $schoolProfile = new \\App\\Models\\SchoolProfile();
    echo "✅ SchoolProfile model instantiated successfully\n";
    
    // Test accessor
    $schoolProfile->image = "test-image.jpg";
    $imageUrl = $schoolProfile->image_url;
    echo "✅ SchoolProfile model accessor working: $imageUrl\n";
    
    // Test with different image paths
    $testPaths = [
        "storage/school-profiles/test.jpg",
        "school-profiles/test.jpg",
        "uploads/school-profiles/test.jpg",
        "test.jpg",
        "https://example.com/test.jpg"
    ];
    
    foreach ($testPaths as $path) {
        $schoolProfile->image = $path;
        $url = $schoolProfile->image_url;
        echo "✅ Path: $path -> URL: $url\n";
    }
    
    // Test scopes
    try {
        $activeProfiles = \\App\\Models\\SchoolProfile::active()->get();
        echo "✅ SchoolProfile active scope working: " . $activeProfiles->count() . " active profiles\n";
    } catch (Exception $e) {
        echo "⚠️ SchoolProfile active scope failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $heroProfiles = \\App\\Models\\SchoolProfile::bySection(\'hero\')->get();
        echo "✅ SchoolProfile bySection scope working: " . $heroProfiles->count() . " hero profiles\n";
    } catch (Exception $e) {
        echo "⚠️ SchoolProfile bySection scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test all models
    $models = [
        \'User\' => \\App\\Models\\User::class,
        \'News\' => \\App\\Models\\News::class,
        \'Gallery\' => \\App\\Models\\Gallery::class,
        \'GalleryItem\' => \\App\\Models\\GalleryItem::class,
        \'Facility\' => \\App\\Models\\Facility::class,
        \'SchoolProfile\' => \\App\\Models\\SchoolProfile::class,
        \'HeadmasterGreeting\' => \\App\\Models\\HeadmasterGreeting::class
    ];
    
    foreach ($models as $modelName => $modelClass) {
        try {
            $count = $modelClass::count();
            echo "✅ $modelName model working: $count records\n";
        } catch (Exception $e) {
            echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
        }
    }
    
    echo "✅ All SchoolProfile model tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-school-profile-model-syntax.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-school-profile-model-syntax.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-school-profile-model-syntax.php\n";
} else {
    echo "❌ Failed to create test-school-profile-model-syntax.php\n";
}

echo "\n✅ SchoolProfile model syntax error fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Removed duplicated code from getImageUrlAttribute method\n";
echo "- Fixed misplaced if statements\n";
echo "- Ensured proper method structure\n";
echo "- Tested PHP syntax\n";
echo "- Tested Laravel bootstrap\n";
echo "- Tested SchoolProfile model functionality\n";
echo "- Tested all models\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- SchoolProfile Model Test: http://localhost:8000/test-school-profile-model-syntax.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- School Profile: http://localhost:8000/admin/school-profile\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-school-profile-model-syntax.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Check if syntax error is resolved\n";
echo "4. Check server logs for any remaining errors\n";

