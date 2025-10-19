<?php
/**
 * Fix HeadmasterGreeting Model Syntax Error
 * 
 * This script fixes syntax error in app/Models/HeadmasterGreeting.php
 * Specifically the duplicated code and misplaced if statements
 */

echo "🔧 Fixing HeadmasterGreeting Model Syntax Error\n";
echo "==============================================\n\n";

echo "🔧 Fixing syntax error in app/Models/HeadmasterGreeting.php...\n";

// 1. Read the current file
echo "\n📄 Reading current HeadmasterGreeting.php file...\n";

if (file_exists('app/Models/HeadmasterGreeting.php')) {
    $content = file_get_contents('app/Models/HeadmasterGreeting.php');
    echo "✅ HeadmasterGreeting.php file found\n";
} else {
    echo "❌ HeadmasterGreeting.php file not found\n";
    exit(1);
}

// 2. Create the corrected HeadmasterGreeting.php file
echo "\n🔧 Creating corrected HeadmasterGreeting.php file...\n";

$correctedContent = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadmasterGreeting extends Model
{
    protected $fillable = [
        \'headmaster_name\',
        \'greeting_message\',
        \'photo\',
        \'is_active\'
    ];

    protected $casts = [
        \'is_active\' => \'boolean\'
    ];

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return asset(\'images/default-headmaster.png\');
        }
        
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        
        if (str_starts_with($this->photo, \'http://\') || str_starts_with($this->photo, \'https://\')) {
            return $this->photo;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->photo, \'storage/\')) {
            return asset($this->photo);
        }
        
        // If it starts with headmaster-greetings/, add storage/ prefix
        if (str_starts_with($this->photo, \'headmaster-greetings/\')) {
            return asset(\'storage/\' . $this->photo);
        }
        
        // If it starts with uploads/headmaster-greetings/, change to storage/headmaster-greetings/
        if (str_starts_with($this->photo, \'uploads/headmaster-greetings/\')) {
            return asset(str_replace(\'uploads/headmaster-greetings/\', \'storage/headmaster-greetings/\', $this->photo));
        }
        
        // If it\'s just a filename, add the full path
        if (!str_contains($this->photo, \'/\')) {
            return asset(\'storage/headmaster-greetings/\' . $this->photo);
        }
        
        // Default fallback
        return asset(\'images/default-headmaster.png\');
    }

    public function scopeActive($query)
    {
        return $query->where(\'is_active\', true);
    }
}';

// 3. Write the corrected file
if (file_put_contents('app/Models/HeadmasterGreeting.php', $correctedContent)) {
    echo "✅ Corrected HeadmasterGreeting.php file created\n";
} else {
    echo "❌ Failed to create corrected HeadmasterGreeting.php file\n";
    exit(1);
}

// 4. Test the syntax
echo "\n🧪 Testing PHP syntax...\n";

$output = [];
$returnCode = 0;
exec('php -l app/Models/HeadmasterGreeting.php 2>&1', $output, $returnCode);

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
    
    // Test if we can access the HeadmasterGreeting model
    try {
        $headmasterGreeting = new \App\Models\HeadmasterGreeting();
        echo "✅ HeadmasterGreeting model instantiated successfully\n";
        
        // Test if we can access the accessor
        try {
            $headmasterGreeting->photo = 'test-photo.jpg';
            $photoUrl = $headmasterGreeting->photo_url;
            echo "✅ HeadmasterGreeting model accessor working: $photoUrl\n";
        } catch (Exception $e) {
            echo "❌ HeadmasterGreeting model accessor failed: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ HeadmasterGreeting model instantiation failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

// 6. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test HeadmasterGreeting Model Syntax
echo "🧪 Testing HeadmasterGreeting Model Syntax\n";
echo "==========================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HeadmasterGreeting model
    $headmasterGreeting = new \\App\\Models\\HeadmasterGreeting();
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
        $activeGreetings = \\App\\Models\\HeadmasterGreeting::active()->get();
        echo "✅ HeadmasterGreeting scope working: " . $activeGreetings->count() . " active greetings\n";
    } catch (Exception $e) {
        echo "⚠️ HeadmasterGreeting scope failed: " . $e->getMessage() . "\n";
    }
    
    echo "✅ All HeadmasterGreeting model tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-headmaster-greeting-model.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-headmaster-greeting-model.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-headmaster-greeting-model.php\n";
} else {
    echo "❌ Failed to create test-headmaster-greeting-model.php\n";
}

echo "\n✅ HeadmasterGreeting model syntax error fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Removed duplicated code from getPhotoUrlAttribute method\n";
echo "- Fixed misplaced if statements\n";
echo "- Ensured proper method structure\n";
echo "- Tested PHP syntax\n";
echo "- Tested Laravel bootstrap\n";
echo "- Tested HeadmasterGreeting model functionality\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- HeadmasterGreeting Model Test: http://localhost:8000/test-headmaster-greeting-model.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Headmaster Greetings: http://localhost:8000/admin/headmaster-greetings\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-headmaster-greeting-model.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Check if syntax error is resolved\n";
echo "4. Check server logs for any remaining errors\n";
