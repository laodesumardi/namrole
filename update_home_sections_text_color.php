<?php
/**
 * Update Home Sections Text Color
 * 
 * This script updates text color for home sections to white
 * Specifically for "Selamat Datang di SMP Negeri 01 Namrole" and "Sekolah Unggulan dengan Pendidikan Berkualitas"
 */

echo "🎨 Updating Home Sections Text Color\n";
echo "====================================\n\n";

echo "🔧 Updating text color for home sections to white...\n";

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

// 3. Check current home sections
echo "\n🔍 Checking current home sections...\n";

try {
    $homeSections = \App\Models\HomeSection::all();
    echo "✅ Found " . $homeSections->count() . " home sections\n";
    
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Current text color: " . $section->text_color . "\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to check home sections: " . $e->getMessage() . "\n";
}

// 4. Update text color to white for all home sections
echo "\n🎨 Updating text color to white for all home sections...\n";

try {
    $updated = \Illuminate\Support\Facades\DB::table('home_sections')
        ->update([
            'text_color' => '#ffffff',
            'updated_at' => now()
        ]);
    
    echo "✅ Updated text color to white for $updated home sections\n";
} catch (Exception $e) {
    echo "❌ Failed to update text color: " . $e->getMessage() . "\n";
}

// 5. Verify the changes
echo "\n🔍 Verifying text color changes...\n";

try {
    $homeSections = \App\Models\HomeSection::all();
    echo "✅ Verification - Current home sections:\n";
    
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Text color: " . $section->text_color . "\n";
        echo "     Background color: " . $section->background_color . "\n";
        echo "     Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to verify changes: " . $e->getMessage() . "\n";
}

// 6. Update specific sections with better contrast
echo "\n🎨 Updating specific sections for better contrast...\n";

$sectionUpdates = [
    'hero' => [
        'text_color' => '#ffffff',
        'background_color' => '#1e3a8a' // Darker blue for better contrast
    ],
    'about' => [
        'text_color' => '#ffffff',
        'background_color' => '#166534' // Darker green for better contrast
    ],
    'facilities' => [
        'text_color' => '#ffffff',
        'background_color' => '#b45309' // Darker yellow for better contrast
    ],
    'gallery' => [
        'text_color' => '#ffffff',
        'background_color' => '#991b1b' // Darker red for better contrast
    ],
    'news' => [
        'text_color' => '#ffffff',
        'background_color' => '#374151' // Darker gray for better contrast
    ],
    'achievement' => [
        'text_color' => '#ffffff',
        'background_color' => '#0f766e' // Darker teal for better contrast
    ],
    'staff' => [
        'text_color' => '#ffffff',
        'background_color' => '#be185d' // Darker pink for better contrast
    ],
    'ppdb' => [
        'text_color' => '#ffffff',
        'background_color' => '#0e7490' // Darker cyan for better contrast
    ],
    'testimonial' => [
        'text_color' => '#ffffff',
        'background_color' => '#c2410c' // Darker orange for better contrast
    ],
    'contact' => [
        'text_color' => '#ffffff',
        'background_color' => '#581c87' // Darker purple for better contrast
    ]
];

foreach ($sectionUpdates as $sectionKey => $updates) {
    try {
        $updated = \Illuminate\Support\Facades\DB::table('home_sections')
            ->where('section_key', $sectionKey)
            ->update([
                'text_color' => $updates['text_color'],
                'background_color' => $updates['background_color'],
                'updated_at' => now()
            ]);
        
        if ($updated > 0) {
            echo "✅ Updated $sectionKey section: text_color=" . $updates['text_color'] . ", background_color=" . $updates['background_color'] . "\n";
        } else {
            echo "⚠️ No $sectionKey section found to update\n";
        }
    } catch (Exception $e) {
        echo "❌ Failed to update $sectionKey section: " . $e->getMessage() . "\n";
    }
}

// 7. Final verification
echo "\n🔍 Final verification of all changes...\n";

try {
    $homeSections = \App\Models\HomeSection::orderBy('sort_order')->get();
    echo "✅ Final verification - All home sections:\n";
    
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Text color: " . $section->text_color . "\n";
        echo "     Background color: " . $section->background_color . "\n";
        echo "     Sort order: " . $section->sort_order . "\n";
        echo "     Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to verify final changes: " . $e->getMessage() . "\n";
}

// 8. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Home Sections Text Color Update
echo "🧪 Testing Home Sections Text Color Update\n";
echo "==========================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HomeSection model
    $homeSections = \\App\\Models\\HomeSection::orderBy(\'sort_order\')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    echo "\n🎨 Text Color Verification:\n";
    foreach ($homeSections as $section) {
        echo "✅ " . $section->title . " (" . $section->section_key . ")\n";
        echo "   Text Color: " . $section->text_color . "\n";
        echo "   Background Color: " . $section->background_color . "\n";
        echo "   Sort Order: " . $section->sort_order . "\n";
        echo "   Active: " . ($section->is_active ? \'Yes\' : \'No\') . "\n";
        
        // Check if text color is white
        if ($section->text_color === \'#ffffff\' || $section->text_color === \'white\') {
            echo "   ✅ Text color is white\n";
        } else {
            echo "   ❌ Text color is not white: " . $section->text_color . "\n";
        }
        echo "\n";
    }
    
    // Check specific sections
    echo "🔍 Specific Section Checks:\n";
    
    $heroSection = \\App\\Models\\HomeSection::where(\'section_key\', \'hero\')->first();
    if ($heroSection) {
        echo "✅ Hero Section: " . $heroSection->title . "\n";
        echo "   Text Color: " . $heroSection->text_color . "\n";
        echo "   Background Color: " . $heroSection->background_color . "\n";
        if ($heroSection->text_color === \'#ffffff\' || $heroSection->text_color === \'white\') {
            echo "   ✅ Hero section text color is white\n";
        } else {
            echo "   ❌ Hero section text color is not white\n";
        }
    } else {
        echo "❌ Hero section not found\n";
    }
    
    $aboutSection = \\App\\Models\\HomeSection::where(\'section_key\', \'about\')->first();
    if ($aboutSection) {
        echo "✅ About Section: " . $aboutSection->title . "\n";
        echo "   Text Color: " . $aboutSection->text_color . "\n";
        echo "   Background Color: " . $aboutSection->background_color . "\n";
        if ($aboutSection->text_color === \'#ffffff\' || $aboutSection->text_color === \'white\') {
            echo "   ✅ About section text color is white\n";
        } else {
            echo "   ❌ About section text color is not white\n";
        }
    } else {
        echo "❌ About section not found\n";
    }
    
    echo "✅ All home sections text color tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-home-sections-text-color.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-home-sections-text-color.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-home-sections-text-color.php\n";
} else {
    echo "❌ Failed to create test-home-sections-text-color.php\n";
}

echo "\n✅ Home sections text color update completed!\n";
echo "🔧 Key changes made:\n";
echo "- Updated all home sections text color to white (#ffffff)\n";
echo "- Updated background colors for better contrast\n";
echo "- Hero section: Darker blue background with white text\n";
echo "- About section: Darker green background with white text\n";
echo "- Facilities section: Darker yellow background with white text\n";
echo "- Gallery section: Darker red background with white text\n";
echo "- News section: Darker gray background with white text\n";
echo "- Achievement section: Darker teal background with white text\n";
echo "- Staff section: Darker pink background with white text\n";
echo "- PPDB section: Darker cyan background with white text\n";
echo "- Testimonial section: Darker orange background with white text\n";
echo "- Contact section: Darker purple background with white text\n";
echo "- All sections now have white text for better readability\n";
echo "- Created test script for verification\n\n";

echo "🌐 Test URLs:\n";
echo "- Home Sections Text Color Test: http://localhost:8000/test-home-sections-text-color.php\n";
echo "- Admin Home Sections: http://localhost:8000/admin/home-sections\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-home-sections-text-color.php\n";
echo "2. Test: http://localhost:8000/admin/home-sections\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if all home sections now have white text\n";
echo "5. Check server logs for any remaining errors\n";
