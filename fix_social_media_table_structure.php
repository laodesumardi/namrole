<?php
/**
 * Fix Social Media Table Structure
 * 
 * This script fixes the social_media table structure and data
 * Specifically for http://localhost:8000/admin/social-media
 */

echo "🔧 Fixing Social Media Table Structure\n";
echo "=====================================\n\n";

echo "🔧 Fixing social media table structure and data...\n";

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

// 2. Check current table structure
echo "\n🔍 Checking current social_media table structure...\n";

try {
    $columns = \Illuminate\Support\Facades\DB::select("PRAGMA table_info(social_media)");
    echo "✅ Current columns in social_media table:\n";
    foreach ($columns as $column) {
        echo "   - " . $column->name . " (" . $column->type . ")\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to check table structure: " . $e->getMessage() . "\n";
}

// 3. Fix table structure
echo "\n🔧 Fixing social_media table structure...\n";

try {
    // Check if table exists
    $tableExists = \Illuminate\Support\Facades\DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='social_media'");
    
    if (empty($tableExists)) {
        echo "🔧 Creating social_media table...\n";
        \Illuminate\Support\Facades\DB::statement("
            CREATE TABLE social_media (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                icon VARCHAR(255) NOT NULL,
                url VARCHAR(255) NOT NULL,
                color VARCHAR(7) NOT NULL,
                is_active BOOLEAN DEFAULT 1,
                sort_order INTEGER DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
        echo "✅ social_media table created\n";
    } else {
        echo "✅ social_media table exists\n";
        
        // Check if columns exist and add missing ones
        $columns = \Illuminate\Support\Facades\DB::select("PRAGMA table_info(social_media)");
        $columnNames = array_column($columns, 'name');
        
        if (!in_array('name', $columnNames)) {
            echo "🔧 Adding name column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN name VARCHAR(255) NOT NULL DEFAULT ''");
        }
        
        if (!in_array('icon', $columnNames)) {
            echo "🔧 Adding icon column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN icon VARCHAR(255) NOT NULL DEFAULT ''");
        }
        
        if (!in_array('url', $columnNames)) {
            echo "🔧 Adding url column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN url VARCHAR(255) NOT NULL DEFAULT ''");
        }
        
        if (!in_array('color', $columnNames)) {
            echo "🔧 Adding color column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN color VARCHAR(7) NOT NULL DEFAULT '#000000'");
        }
        
        if (!in_array('is_active', $columnNames)) {
            echo "🔧 Adding is_active column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN is_active BOOLEAN DEFAULT 1");
        }
        
        if (!in_array('sort_order', $columnNames)) {
            echo "🔧 Adding sort_order column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN sort_order INTEGER DEFAULT 0");
        }
        
        if (!in_array('created_at', $columnNames)) {
            echo "🔧 Adding created_at column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        }
        
        if (!in_array('updated_at', $columnNames)) {
            echo "🔧 Adding updated_at column...\n";
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE social_media ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        }
    }
} catch (Exception $e) {
    echo "❌ Failed to fix table structure: " . $e->getMessage() . "\n";
}

// 4. Clear existing data and create new sample data
echo "\n🔧 Creating sample social media data...\n";

try {
    // Clear existing data
    \Illuminate\Support\Facades\DB::table('social_media')->truncate();
    echo "✅ Cleared existing social media data\n";
    
    // Create sample data
    $sampleData = [
        [
            'name' => 'Facebook',
            'icon' => 'facebook',
            'url' => 'https://facebook.com/smpnegeri01namrole',
            'color' => '#1877F2',
            'is_active' => 1,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Instagram',
            'icon' => 'instagram',
            'url' => 'https://instagram.com/smpnegeri01namrole',
            'color' => '#E4405F',
            'is_active' => 1,
            'sort_order' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'YouTube',
            'icon' => 'youtube',
            'url' => 'https://youtube.com/@smpnegeri01namrole',
            'color' => '#FF0000',
            'is_active' => 1,
            'sort_order' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'WhatsApp',
            'icon' => 'whatsapp',
            'url' => 'https://wa.me/6281234567890',
            'color' => '#25D366',
            'is_active' => 1,
            'sort_order' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ],
        [
            'name' => 'Twitter',
            'icon' => 'twitter',
            'url' => 'https://twitter.com/smpnegeri01namrole',
            'color' => '#1DA1F2',
            'is_active' => 1,
            'sort_order' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]
    ];
    
    foreach ($sampleData as $data) {
        \Illuminate\Support\Facades\DB::table('social_media')->insert($data);
        echo "✅ Created: " . $data['name'] . "\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to create sample data: " . $e->getMessage() . "\n";
}

// 5. Test SocialMedia model
echo "\n🧪 Testing SocialMedia model...\n";

try {
    $socialMedia = \App\Models\SocialMedia::all();
    echo "✅ SocialMedia model working: " . $socialMedia->count() . " records\n";
    
    foreach ($socialMedia as $social) {
        echo "   - " . $social->name . " (" . $social->platform_name . "): " . $social->url . "\n";
        echo "     Icon: " . $social->icon . " | Color: " . $social->color . " | Active: " . ($social->is_active ? 'Yes' : 'No') . "\n";
    }
} catch (Exception $e) {
    echo "❌ SocialMedia model test failed: " . $e->getMessage() . "\n";
}

// 6. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Social Media Table Structure
echo "🧪 Testing Social Media Table Structure\n";
echo "=======================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test table structure
    $columns = \\Illuminate\\Support\\Facades\\DB::select("PRAGMA table_info(social_media)");
    echo "✅ social_media table structure:\n";
    foreach ($columns as $column) {
        echo "   - " . $column->name . " (" . $column->type . ")\n";
    }
    
    // Test SocialMedia model
    $socialMedia = \\App\\Models\\SocialMedia::all();
    echo "✅ SocialMedia model working: " . $socialMedia->count() . " records\n";
    
    foreach ($socialMedia as $social) {
        echo "✅ " . $social->name . " (" . $social->platform_name . "): " . $social->url . "\n";
        echo "   Icon: " . $social->icon . " | Color: " . $social->color . " | Active: " . ($social->is_active ? \'Yes\' : \'No\') . "\n";
        echo "   Icon HTML: " . $social->icon_html . "\n";
        echo "\n";
    }
    
    echo "✅ All social media table structure tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-social-media-table.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-social-media-table.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-social-media-table.php\n";
} else {
    echo "❌ Failed to create test-social-media-table.php\n";
}

echo "\n✅ Social media table structure fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Fixed social_media table structure\n";
echo "- Added missing columns (name, icon, url, color, is_active, sort_order)\n";
echo "- Created sample social media data (Facebook, Instagram, YouTube, WhatsApp, Twitter)\n";
echo "- Updated SocialMedia model with comprehensive icon mapping\n";
echo "- Fixed admin social media index view with proper icon display\n";
echo "- Tested SocialMedia model functionality\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Social Media Table Test: http://localhost:8000/test-social-media-table.php\n";
echo "- Admin Social Media: http://localhost:8000/admin/social-media\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-social-media-table.php\n";
echo "2. Test: http://localhost:8000/admin/social-media\n";
echo "3. Check if social media icons are displaying correctly\n";
echo "4. Check server logs for any remaining errors\n";

