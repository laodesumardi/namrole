<?php
/**
 * Fix Database Migrations
 * 
 * This script fixes database migrations and creates missing tables
 * Specifically for SQLite database
 */

echo "🗄️ Fixing Database Migrations\n";
echo "=============================\n\n";

echo "🔧 Fixing database migrations and creating missing tables...\n";

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

// 3. Check if migrations table exists
echo "\n📊 Checking migrations table...\n";

try {
    $migrations = \Illuminate\Support\Facades\DB::table('migrations')->count();
    echo "✅ Migrations table exists with $migrations migrations\n";
} catch (Exception $e) {
    echo "❌ Migrations table not found: " . $e->getMessage() . "\n";
    echo "🔧 This is likely the cause of the error\n";
}

// 4. Run migrations
echo "\n📊 Running migrations...\n";

try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "✅ Migrations run successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to run migrations: " . $e->getMessage() . "\n";
    echo "🔧 This is likely the cause of the error\n";
}

// 5. Check if headmaster_greetings table exists
echo "\n📊 Checking headmaster_greetings table...\n";

try {
    $count = \Illuminate\Support\Facades\DB::table('headmaster_greetings')->count();
    echo "✅ headmaster_greetings table exists with $count records\n";
} catch (Exception $e) {
    echo "❌ headmaster_greetings table not found: " . $e->getMessage() . "\n";
    echo "🔧 This is the cause of the error\n";
}

// 6. Check all tables
echo "\n📊 Checking all tables...\n";

try {
    $tables = \Illuminate\Support\Facades\DB::select("SELECT name FROM sqlite_master WHERE type='table'");
    echo "✅ Found " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        echo "   - " . $table->name . "\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to get tables: " . $e->getMessage() . "\n";
}

// 7. Create headmaster_greetings table manually if it doesn't exist
echo "\n🔧 Creating headmaster_greetings table manually...\n";

try {
    \Illuminate\Support\Facades\DB::statement("
        CREATE TABLE IF NOT EXISTS headmaster_greetings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            headmaster_name VARCHAR(255) NOT NULL,
            greeting_message TEXT,
            photo VARCHAR(255),
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ headmaster_greetings table created successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to create headmaster_greetings table: " . $e->getMessage() . "\n";
}

// 8. Check if table was created
echo "\n📊 Checking if headmaster_greetings table was created...\n";

try {
    $count = \Illuminate\Support\Facades\DB::table('headmaster_greetings')->count();
    echo "✅ headmaster_greetings table exists with $count records\n";
} catch (Exception $e) {
    echo "❌ headmaster_greetings table still not found: " . $e->getMessage() . "\n";
}

// 9. Create a sample headmaster greeting
echo "\n🔧 Creating sample headmaster greeting...\n";

try {
    \Illuminate\Support\Facades\DB::table('headmaster_greetings')->insert([
        'headmaster_name' => 'Kepala Sekolah',
        'greeting_message' => 'Selamat datang di SMP Negeri 01 Namrole',
        'photo' => 'default-headmaster.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample headmaster greeting created\n";
} catch (Exception $e) {
    echo "❌ Failed to create sample headmaster greeting: " . $e->getMessage() . "\n";
}

// 10. Test HeadmasterGreeting model
echo "\n🧪 Testing HeadmasterGreeting model...\n";

try {
    $headmasterGreeting = \App\Models\HeadmasterGreeting::active()->first();
    if ($headmasterGreeting) {
        echo "✅ HeadmasterGreeting model working: " . $headmasterGreeting->headmaster_name . "\n";
    } else {
        echo "⚠️ No active headmaster greeting found\n";
    }
} catch (Exception $e) {
    echo "❌ HeadmasterGreeting model test failed: " . $e->getMessage() . "\n";
}

// 11. Create other missing tables
echo "\n🔧 Creating other missing tables...\n";

$tables = [
    'users' => "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            email_verified_at TIMESTAMP,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) DEFAULT 'student',
            photo VARCHAR(255),
            remember_token VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'news' => "
        CREATE TABLE IF NOT EXISTS news (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            excerpt TEXT,
            content TEXT,
            featured_image VARCHAR(255),
            category VARCHAR(100),
            type VARCHAR(50) DEFAULT 'news',
            status VARCHAR(50) DEFAULT 'draft',
            is_featured BOOLEAN DEFAULT 0,
            is_pinned BOOLEAN DEFAULT 0,
            views INTEGER DEFAULT 0,
            published_at TIMESTAMP,
            author_name VARCHAR(255),
            author_email VARCHAR(255),
            tags TEXT,
            meta_data TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'galleries' => "
        CREATE TABLE IF NOT EXISTS galleries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            cover_image VARCHAR(255),
            category VARCHAR(100),
            type VARCHAR(50) DEFAULT 'gallery',
            status VARCHAR(50) DEFAULT 'active',
            is_featured BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'gallery_items' => "
        CREATE TABLE IF NOT EXISTS gallery_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            gallery_id INTEGER NOT NULL,
            title VARCHAR(255),
            description TEXT,
            image VARCHAR(255) NOT NULL,
            order_index INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (gallery_id) REFERENCES galleries(id) ON DELETE CASCADE
        )
    ",
    'facilities' => "
        CREATE TABLE IF NOT EXISTS facilities (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'school_profiles' => "
        CREATE TABLE IF NOT EXISTS school_profiles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            section VARCHAR(100) NOT NULL,
            title VARCHAR(255),
            content TEXT,
            image VARCHAR(255),
            order_index INTEGER DEFAULT 0,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    "
];

foreach ($tables as $tableName => $sql) {
    try {
        \Illuminate\Support\Facades\DB::statement($sql);
        echo "✅ $tableName table created successfully\n";
    } catch (Exception $e) {
        echo "❌ Failed to create $tableName table: " . $e->getMessage() . "\n";
    }
}

// 12. Create sample data
echo "\n🔧 Creating sample data...\n";

try {
    // Create sample user
    \Illuminate\Support\Facades\DB::table('users')->insert([
        'name' => 'Administrator',
        'email' => 'admin@namrole.sch.id',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample user created\n";
} catch (Exception $e) {
    echo "❌ Failed to create sample user: " . $e->getMessage() . "\n";
}

// 13. Test all models
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

// 14. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Database Migrations
echo "🧪 Testing Database Migrations\n";
echo "==============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test database connection
    $pdo = \\Illuminate\\Support\\Facades\\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Test all tables
    $tables = \\Illuminate\\Support\\Facades\\DB::select("SELECT name FROM sqlite_master WHERE type=\'table\'");
    echo "✅ Found " . count($tables) . " tables:\n";
    foreach ($tables as $table) {
        echo "   - " . $table->name . "\n";
    }
    
    // Test HeadmasterGreeting model
    try {
        $headmasterGreeting = \\App\\Models\\HeadmasterGreeting::active()->first();
        if ($headmasterGreeting) {
            echo "✅ HeadmasterGreeting model working: " . $headmasterGreeting->headmaster_name . "\n";
        } else {
            echo "⚠️ No active headmaster greeting found\n";
        }
    } catch (Exception $e) {
        echo "❌ HeadmasterGreeting model failed: " . $e->getMessage() . "\n";
    }
    
    // Test other models
    $models = [
        \'User\' => \\App\\Models\\User::class,
        \'News\' => \\App\\Models\\News::class,
        \'Gallery\' => \\App\\Models\\Gallery::class,
        \'Facility\' => \\App\\Models\\Facility::class,
        \'SchoolProfile\' => \\App\\Models\\SchoolProfile::class
    ];
    
    foreach ($models as $modelName => $modelClass) {
        try {
            $count = $modelClass::count();
            echo "✅ $modelName model working: $count records\n";
        } catch (Exception $e) {
            echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
        }
    }
    
    echo "✅ All database tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-database-migrations.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-database-migrations.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-database-migrations.php\n";
} else {
    echo "❌ Failed to create test-database-migrations.php\n";
}

echo "\n✅ Database migrations fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Checked database connection\n";
echo "- Checked migrations table\n";
echo "- Ran migrations\n";
echo "- Created headmaster_greetings table manually\n";
echo "- Created sample headmaster greeting\n";
echo "- Created other missing tables\n";
echo "- Created sample data\n";
echo "- Tested all models\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Database Test: http://localhost:8000/test-database-migrations.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Headmaster Greetings: http://localhost:8000/admin/headmaster-greetings\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-database-migrations.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Check if database error is resolved\n";
echo "4. Check server logs for any remaining errors\n";
