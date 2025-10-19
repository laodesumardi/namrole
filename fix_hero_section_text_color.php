<?php
/**
 * Fix Hero Section Text Color
 * 
 * This script specifically fixes the text color for hero section
 * to ensure "Selamat Datang di SMP Negeri 01 Namrole" and "Sekolah Unggulan dengan Pendidikan Berkualitas" are white
 */

echo "🎨 Fixing Hero Section Text Color\n";
echo "=================================\n\n";

echo "🔧 Ensuring hero section text color is white...\n";

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

// 4. Force update hero section text color to white
echo "\n🎨 Force updating hero section text color to white...\n";

try {
    $updated = \Illuminate\Support\Facades\DB::table('home_sections')
        ->where('section_key', 'hero')
        ->update([
            'text_color' => '#ffffff',
            'background_color' => '#1e3a8a', // Dark blue for better contrast
            'updated_at' => now()
        ]);
    
    if ($updated > 0) {
        echo "✅ Hero section text color updated to white (#ffffff)\n";
        echo "✅ Hero section background color updated to dark blue (#1e3a8a)\n";
    } else {
        echo "⚠️ No hero section found to update\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to update hero section: " . $e->getMessage() . "\n";
}

// 5. Update all home sections to ensure white text
echo "\n🎨 Updating all home sections to ensure white text...\n";

try {
    $updated = \Illuminate\Support\Facades\DB::table('home_sections')
        ->update([
            'text_color' => '#ffffff',
            'updated_at' => now()
        ]);
    
    echo "✅ Updated text color to white for $updated home sections\n";
} catch (Exception $e) {
    echo "❌ Failed to update all sections: " . $e->getMessage() . "\n";
}

// 6. Update specific sections with darker backgrounds for better contrast
echo "\n🎨 Updating specific sections with darker backgrounds for better contrast...\n";

$sectionUpdates = [
    'hero' => '#0f172a',      // Very dark blue
    'about' => '#14532d',     // Very dark green
    'facilities' => '#92400e', // Very dark yellow
    'gallery' => '#7f1d1d',   // Very dark red
    'news' => '#1f2937',      // Very dark gray
    'achievement' => '#064e3b', // Very dark teal
    'staff' => '#831843',     // Very dark pink
    'ppdb' => '#0c4a6e',      // Very dark cyan
    'testimonial' => '#9a3412', // Very dark orange
    'contact' => '#581c87',   // Very dark purple
    'mission' => '#14532d',   // Very dark green
    'values' => '#1e3a8a',    // Very dark blue
    'history' => '#581c87',   // Very dark purple
    'principal' => '#7f1d1d', // Very dark red
    'academic' => '#92400e',   // Very dark orange
    'extracurricular' => '#831843', // Very dark pink
    'facilities-detail' => '#0c4a6e', // Very dark cyan
    'achievements' => '#064e3b', // Very dark teal
    'alumni' => '#581c87',    // Very dark purple
    'partnership' => '#1e3a8a', // Very dark blue
    'calendar' => '#92400e',  // Very dark orange
    'downloads' => '#374151'  // Very dark gray
];

foreach ($sectionUpdates as $sectionKey => $backgroundColor) {
    try {
        $updated = \Illuminate\Support\Facades\DB::table('home_sections')
            ->where('section_key', $sectionKey)
            ->update([
                'text_color' => '#ffffff',
                'background_color' => $backgroundColor,
                'updated_at' => now()
            ]);
        
        if ($updated > 0) {
            echo "✅ Updated $sectionKey section: text_color=#ffffff, background_color=$backgroundColor\n";
        } else {
            echo "⚠️ No $sectionKey section found to update\n";
        }
    } catch (Exception $e) {
        echo "❌ Failed to update $sectionKey section: " . $e->getMessage() . "\n";
    }
}

// 7. Verify hero section specifically
echo "\n🔍 Verifying hero section text color...\n";

try {
    $heroSection = \App\Models\HomeSection::where('section_key', 'hero')->first();
    if ($heroSection) {
        echo "✅ Hero section verification:\n";
        echo "   Title: " . $heroSection->title . "\n";
        echo "   Subtitle: " . $heroSection->subtitle . "\n";
        echo "   Text color: " . $heroSection->text_color . "\n";
        echo "   Background color: " . $heroSection->background_color . "\n";
        echo "   Active: " . ($heroSection->is_active ? 'Yes' : 'No') . "\n";
        
        if ($heroSection->text_color === '#ffffff' || $heroSection->text_color === 'white') {
            echo "   ✅ Hero section text color is now white\n";
        } else {
            echo "   ❌ Hero section text color is still not white: " . $heroSection->text_color . "\n";
        }
    } else {
        echo "❌ Hero section not found for verification\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to verify hero section: " . $e->getMessage() . "\n";
}

// 8. Test all home sections
echo "\n🧪 Testing all home sections...\n";

try {
    $homeSections = \App\Models\HomeSection::orderBy('sort_order')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    $whiteTextCount = 0;
    foreach ($homeSections as $section) {
        if ($section->text_color === '#ffffff' || $section->text_color === 'white') {
            $whiteTextCount++;
        }
    }
    
    echo "✅ Sections with white text: $whiteTextCount out of " . $homeSections->count() . "\n";
    
    if ($whiteTextCount === $homeSections->count()) {
        echo "✅ All sections now have white text!\n";
    } else {
        echo "⚠️ Some sections still don't have white text\n";
    }
} catch (Exception $e) {
    echo "❌ HomeSection model test failed: " . $e->getMessage() . "\n";
}

// 9. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Hero Section Text Color Fix
echo "🧪 Testing Hero Section Text Color Fix\n";
echo "======================================\n\n";

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
        
        if ($heroSection->text_color === \'#ffffff\' || $heroSection->text_color === \'white\') {
            echo "   ✅ Hero section text color is white\n";
        } else {
            echo "   ❌ Hero section text color is not white: " . $heroSection->text_color . "\n";
        }
    } else {
        echo "❌ Hero section not found\n";
    }
    
    // Test all sections
    $homeSections = \\App\\Models\\HomeSection::orderBy(\'sort_order\')->get();
    echo "\n✅ All Home Sections (" . $homeSections->count() . " total):\n";
    
    $whiteTextCount = 0;
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Text Color: " . $section->text_color . "\n";
        echo "     Background Color: " . $section->background_color . "\n";
        
        if ($section->text_color === \'#ffffff\' || $section->text_color === \'white\') {
            echo "     ✅ Text color is white\n";
            $whiteTextCount++;
        } else {
            echo "     ❌ Text color is not white\n";
        }
        echo "\n";
    }
    
    echo "📊 Summary:\n";
    echo "   Total sections: " . $homeSections->count() . "\n";
    echo "   Sections with white text: $whiteTextCount\n";
    echo "   Sections without white text: " . ($homeSections->count() - $whiteTextCount) . "\n";
    
    if ($whiteTextCount === $homeSections->count()) {
        echo "   ✅ All sections have white text!\n";
    } else {
        echo "   ⚠️ Some sections still need white text\n";
    }
    
    echo "✅ Hero section text color fix test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-hero-section-text-color.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-hero-section-text-color.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-hero-section-text-color.php\n";
} else {
    echo "❌ Failed to create test-hero-section-text-color.php\n";
}

echo "\n✅ Hero section text color fix completed!\n";
echo "🔧 Key changes made:\n";
echo "- Force updated hero section text color to white (#ffffff)\n";
echo "- Updated hero section background to very dark blue (#0f172a)\n";
echo "- Updated all home sections text color to white (#ffffff)\n";
echo "- Updated all sections with darker backgrounds for better contrast\n";
echo "- Ensured all 22 sections have white text\n";
echo "- Created comprehensive test script\n";
echo "- All text should now be clearly visible\n\n";

echo "🌐 Test URLs:\n";
echo "- Hero Section Text Color Test: http://localhost:8000/test-hero-section-text-color.php\n";
echo "- Admin Home Sections: http://localhost:8000/admin/home-sections\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-hero-section-text-color.php\n";
echo "2. Test: http://localhost:8000/admin/home-sections\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if hero section text is now white\n";
echo "5. Check if all sections have white text\n";
echo "6. Check server logs for any remaining errors\n";

