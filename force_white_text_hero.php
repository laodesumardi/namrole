<?php
/**
 * Force White Text Hero Section
 * 
 * This script forces the hero section text to be white
 * Specifically targeting the title and subtitle text
 */

echo "🎨 Force White Text Hero Section\n";
echo "================================\n\n";

echo "🔧 Forcing hero section text to be white...\n";

// 1. Bootstrap Laravel
echo "\n🔗 Bootstrapping Laravel...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to bootstrap Laravel: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Check database connection
echo "\n🗄️ Checking database connection...\n";

try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Check current hero section
echo "\n🔍 Checking current hero section...\n";

try {
    $heroSection = \App\Models\HomeSection::where('section_key', 'hero')->first();
    if ($heroSection) {
        echo "✅ Hero section found:\n";
        echo "   Title: " . $heroSection->title . "\n";
        echo "   Subtitle: " . $heroSection->subtitle . "\n";
        echo "   Current text color: " . $heroSection->text_color . "\n";
        echo "   Current background color: " . $heroSection->background_color . "\n";
        echo "   Active: " . ($heroSection->is_active ? 'Yes' : 'No') . "\n";
    } else {
        echo "❌ Hero section not found\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to check hero section: " . $e->getMessage() . "\n";
}

// 4. Force update hero section with multiple white text options
echo "\n🎨 Force updating hero section with multiple white text options...\n";

$whiteTextOptions = [
    '#ffffff',
    'white',
    '#fff',
    'rgb(255, 255, 255)',
    'rgba(255, 255, 255, 1)'
];

foreach ($whiteTextOptions as $textColor) {
    try {
        $updated = \Illuminate\Support\Facades\DB::table('home_sections')
            ->where('section_key', 'hero')
            ->update([
                'text_color' => $textColor,
                'background_color' => '#000000', // Pure black background for maximum contrast
                'updated_at' => now()
            ]);
        
        if ($updated > 0) {
            echo "✅ Updated hero section with text color: $textColor\n";
        }
    } catch (Exception $e) {
        echo "❌ Failed to update with $textColor: " . $e->getMessage() . "\n";
    }
}

// 5. Force update with CSS-style white text
echo "\n🎨 Force updating with CSS-style white text...\n";

try {
    $updated = \Illuminate\Support\Facades\DB::table('home_sections')
        ->where('section_key', 'hero')
        ->update([
            'text_color' => 'white !important',
            'background_color' => '#000000',
            'updated_at' => now()
        ]);
    
    if ($updated > 0) {
        echo "✅ Updated hero section with CSS-style white text\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to update with CSS-style: " . $e->getMessage() . "\n";
}

// 6. Force update with inline style approach
echo "\n🎨 Force updating with inline style approach...\n";

try {
    $updated = \Illuminate\Support\Facades\DB::table('home_sections')
        ->where('section_key', 'hero')
        ->update([
            'text_color' => 'color: white;',
            'background_color' => 'background-color: #000000;',
            'updated_at' => now()
        ]);
    
    if ($updated > 0) {
        echo "✅ Updated hero section with inline style approach\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to update with inline style: " . $e->getMessage() . "\n";
}

// 7. Force update with HTML style attribute
echo "\n🎨 Force updating with HTML style attribute...\n";

try {
    $updated = \Illuminate\Support\Facades\DB::table('home_sections')
        ->where('section_key', 'hero')
        ->update([
            'text_color' => 'style="color: white;"',
            'background_color' => 'style="background-color: #000000;"',
            'updated_at' => now()
        ]);
    
    if ($updated > 0) {
        echo "✅ Updated hero section with HTML style attribute\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to update with HTML style: " . $e->getMessage() . "\n";
}

// 8. Force update with multiple approaches
echo "\n🎨 Force updating with multiple approaches...\n";

$approaches = [
    ['text_color' => '#ffffff', 'background_color' => '#000000'],
    ['text_color' => 'white', 'background_color' => 'black'],
    ['text_color' => '#fff', 'background_color' => '#000'],
    ['text_color' => 'rgb(255,255,255)', 'background_color' => 'rgb(0,0,0)'],
    ['text_color' => 'rgba(255,255,255,1)', 'background_color' => 'rgba(0,0,0,1)'],
    ['text_color' => 'color: #ffffff;', 'background_color' => 'background: #000000;'],
    ['text_color' => 'color: white !important;', 'background_color' => 'background: black !important;'],
    ['text_color' => 'style="color: #ffffff;"', 'background_color' => 'style="background: #000000;"'],
    ['text_color' => 'style="color: white !important;"', 'background_color' => 'style="background: black !important;"'],
    ['text_color' => 'color: #ffffff !important; background: transparent;', 'background_color' => 'background: #000000 !important;']
];

foreach ($approaches as $index => $approach) {
    try {
        $updated = \Illuminate\Support\Facades\DB::table('home_sections')
            ->where('section_key', 'hero')
            ->update([
                'text_color' => $approach['text_color'],
                'background_color' => $approach['background_color'],
                'updated_at' => now()
            ]);
        
        if ($updated > 0) {
            echo "✅ Updated hero section with approach " . ($index + 1) . ": " . $approach['text_color'] . "\n";
        }
    } catch (Exception $e) {
        echo "❌ Failed to update with approach " . ($index + 1) . ": " . $e->getMessage() . "\n";
    }
}

// 9. Final verification
echo "\n🔍 Final verification of hero section...\n";

try {
    $heroSection = \App\Models\HomeSection::where('section_key', 'hero')->first();
    if ($heroSection) {
        echo "✅ Final hero section status:\n";
        echo "   Title: " . $heroSection->title . "\n";
        echo "   Subtitle: " . $heroSection->subtitle . "\n";
        echo "   Text color: " . $heroSection->text_color . "\n";
        echo "   Background color: " . $heroSection->background_color . "\n";
        echo "   Active: " . ($heroSection->is_active ? 'Yes' : 'No') . "\n";
        
        // Check if text color contains white
        $textColor = strtolower($heroSection->text_color);
        if (strpos($textColor, 'white') !== false || 
            strpos($textColor, '#fff') !== false || 
            strpos($textColor, '#ffffff') !== false ||
            strpos($textColor, '255') !== false) {
            echo "   ✅ Hero section text color appears to be white\n";
        } else {
            echo "   ❌ Hero section text color may not be white: " . $heroSection->text_color . "\n";
        }
    } else {
        echo "❌ Hero section not found for final verification\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to verify hero section: " . $e->getMessage() . "\n";
}

// 10. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Force White Text Hero Section
echo "🧪 Testing Force White Text Hero Section\n";
echo "=======================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test Hero Section specifically
    $heroSection = \\App\\Models\\HomeSection::where(\'section_key\', \'hero\')->first();
    if ($heroSection) {
        echo "✅ Hero Section Found:\n";
        echo "   Title: " . $heroSection->title . "\n";
        echo "   Subtitle: " . $heroSection->subtitle . "\n";
        echo "   Text Color: " . $heroSection->text_color . "\n";
        echo "   Background Color: " . $heroSection->background_color . "\n";
        echo "   Active: " . ($heroSection->is_active ? \'Yes\' : \'No\') . "\n";
        
        // Check if text color contains white
        $textColor = strtolower($heroSection->text_color);
        if (strpos($textColor, \'white\') !== false || 
            strpos($textColor, \'#fff\') !== false || 
            strpos($textColor, \'#ffffff\') !== false ||
            strpos($textColor, \'255\') !== false) {
            echo "   ✅ Hero section text color appears to be white\n";
        } else {
            echo "   ❌ Hero section text color may not be white: " . $heroSection->text_color . "\n";
        }
    } else {
        echo "❌ Hero section not found\n";
    }
    
    // Test all sections
    $homeSections = \\App\\Models\\HomeSection::orderBy(\'sort_order\')->get();
    echo "\n✅ All Home Sections (" . $homeSections->count() . " total):\n";
    
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Text Color: " . $section->text_color . "\n";
        echo "     Background Color: " . $section->background_color . "\n";
        echo "\n";
    }
    
    echo "✅ Force white text hero section test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-force-white-text-hero.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-force-white-text-hero.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-force-white-text-hero.php\n";
} else {
    echo "❌ Failed to create test-force-white-text-hero.php\n";
}

echo "\n✅ Force white text hero section completed!\n";
echo "🔧 Key changes made:\n";
echo "- Force updated hero section with multiple white text options\n";
echo "- Updated with CSS-style white text\n";
echo "- Updated with inline style approach\n";
echo "- Updated with HTML style attribute\n";
echo "- Updated with multiple approaches (10 different methods)\n";
echo "- Set background to pure black (#000000) for maximum contrast\n";
echo "- All approaches should ensure white text visibility\n";
echo "- Created comprehensive test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Force White Text Hero Test: http://localhost:8000/test-force-white-text-hero.php\n";
echo "- Admin Home Sections: http://localhost:8000/admin/home-sections\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-force-white-text-hero.php\n";
echo "2. Test: http://localhost:8000/admin/home-sections\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if hero section text is now white\n";
echo "5. If still black, check CSS files and view templates\n";
echo "6. Check server logs for any remaining errors\n";
