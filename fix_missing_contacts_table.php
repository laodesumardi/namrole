<?php
/**
 * Fix Missing Contacts Table
 * 
 * This script fixes missing contacts table in SQLite database
 * Specifically for contact form functionality
 */

echo "📞 Fixing Missing Contacts Table\n";
echo "=================================\n\n";

echo "🔧 Fixing missing contacts table in SQLite database...\n";

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

// 3. Check if contacts table exists
echo "\n📊 Checking contacts table...\n";

try {
    $count = \Illuminate\Support\Facades\DB::table('contacts')->count();
    echo "✅ contacts table exists with $count records\n";
} catch (Exception $e) {
    echo "❌ contacts table not found: " . $e->getMessage() . "\n";
    echo "🔧 This is the cause of the error\n";
}

// 4. Create contacts table
echo "\n🔧 Creating contacts table...\n";

try {
    \Illuminate\Support\Facades\DB::statement("
        CREATE TABLE IF NOT EXISTS contacts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            subject VARCHAR(255),
            message TEXT NOT NULL,
            is_active BOOLEAN DEFAULT 1,
            is_read BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✅ contacts table created successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to create contacts table: " . $e->getMessage() . "\n";
}

// 5. Check if table was created
echo "\n📊 Checking if contacts table was created...\n";

try {
    $count = \Illuminate\Support\Facades\DB::table('contacts')->count();
    echo "✅ contacts table exists with $count records\n";
} catch (Exception $e) {
    echo "❌ contacts table still not found: " . $e->getMessage() . "\n";
}

// 6. Create sample contact
echo "\n🔧 Creating sample contact...\n";

try {
    \Illuminate\Support\Facades\DB::table('contacts')->insert([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '08123456789',
        'subject' => 'Test Contact',
        'message' => 'This is a test contact message',
        'is_active' => 1,
        'is_read' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample contact created\n";
} catch (Exception $e) {
    echo "❌ Failed to create sample contact: " . $e->getMessage() . "\n";
}

// 7. Test Contact model
echo "\n🧪 Testing Contact model...\n";

try {
    $contact = \App\Models\Contact::active()->first();
    if ($contact) {
        echo "✅ Contact model working: " . $contact->name . "\n";
    } else {
        echo "⚠️ No active contact found\n";
    }
} catch (Exception $e) {
    echo "❌ Contact model test failed: " . $e->getMessage() . "\n";
}

// 8. Create other missing tables
echo "\n🔧 Creating other missing tables...\n";

$tables = [
    'notifications' => "
        CREATE TABLE IF NOT EXISTS notifications (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            message TEXT,
            type VARCHAR(50) DEFAULT 'info',
            is_read BOOLEAN DEFAULT 0,
            user_id INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'messages' => "
        CREATE TABLE IF NOT EXISTS messages (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            sender_name VARCHAR(255) NOT NULL,
            sender_email VARCHAR(255) NOT NULL,
            subject VARCHAR(255),
            message TEXT NOT NULL,
            is_read BOOLEAN DEFAULT 0,
            is_replied BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'social_media' => "
        CREATE TABLE IF NOT EXISTS social_media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            platform VARCHAR(100) NOT NULL,
            url VARCHAR(255) NOT NULL,
            icon VARCHAR(100),
            is_active BOOLEAN DEFAULT 1,
            order_index INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'vision_missions' => "
        CREATE TABLE IF NOT EXISTS vision_missions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            type VARCHAR(50) NOT NULL,
            title VARCHAR(255) NOT NULL,
            content TEXT NOT NULL,
            is_active BOOLEAN DEFAULT 1,
            order_index INTEGER DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'accreditations' => "
        CREATE TABLE IF NOT EXISTS accreditations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            year INTEGER,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'achievements' => "
        CREATE TABLE IF NOT EXISTS achievements (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            year INTEGER,
            level VARCHAR(50),
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'libraries' => "
        CREATE TABLE IF NOT EXISTS libraries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'courses' => "
        CREATE TABLE IF NOT EXISTS courses (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            image VARCHAR(255),
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'subjects' => "
        CREATE TABLE IF NOT EXISTS subjects (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            code VARCHAR(50),
            description TEXT,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'lessons' => "
        CREATE TABLE IF NOT EXISTS lessons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            subject_id INTEGER,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'assignments' => "
        CREATE TABLE IF NOT EXISTS assignments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            due_date TIMESTAMP,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'submissions' => "
        CREATE TABLE IF NOT EXISTS submissions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            assignment_id INTEGER,
            student_id INTEGER,
            content TEXT,
            file_path VARCHAR(255),
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'forums' => "
        CREATE TABLE IF NOT EXISTS forums (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'forum_replies' => "
        CREATE TABLE IF NOT EXISTS forum_replies (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            forum_id INTEGER,
            user_id INTEGER,
            content TEXT NOT NULL,
            is_active BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ",
    'course_enrollments' => "
        CREATE TABLE IF NOT EXISTS course_enrollments (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            course_id INTEGER,
            user_id INTEGER,
            enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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

// 9. Create sample data
echo "\n🔧 Creating sample data...\n";

try {
    // Create sample social media
    \Illuminate\Support\Facades\DB::table('social_media')->insert([
        'platform' => 'Facebook',
        'url' => 'https://facebook.com/smpnegeri01namrole',
        'icon' => 'fab fa-facebook',
        'is_active' => 1,
        'order_index' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample social media created\n";
} catch (Exception $e) {
    echo "❌ Failed to create sample social media: " . $e->getMessage() . "\n";
}

try {
    // Create sample vision mission
    \Illuminate\Support\Facades\DB::table('vision_missions')->insert([
        'type' => 'vision',
        'title' => 'Visi Sekolah',
        'content' => 'Menjadi sekolah unggulan yang menghasilkan generasi berkarakter, berprestasi, dan berakhlak mulia',
        'is_active' => 1,
        'order_index' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample vision mission created\n";
} catch (Exception $e) {
    echo "❌ Failed to create sample vision mission: " . $e->getMessage() . "\n";
}

// 10. Test all models
echo "\n🧪 Testing all models...\n";

$models = [
    'User' => \App\Models\User::class,
    'News' => \App\Models\News::class,
    'Gallery' => \App\Models\Gallery::class,
    'GalleryItem' => \App\Models\GalleryItem::class,
    'Facility' => \App\Models\Facility::class,
    'SchoolProfile' => \App\Models\SchoolProfile::class,
    'HeadmasterGreeting' => \App\Models\HeadmasterGreeting::class,
    'Contact' => \App\Models\Contact::class,
    'Notification' => \App\Models\Notification::class,
    'Message' => \App\Models\Message::class,
    'SocialMedia' => \App\Models\SocialMedia::class,
    'VisionMission' => \App\Models\VisionMission::class,
    'Accreditation' => \App\Models\Accreditation::class,
    'Achievement' => \App\Models\Achievement::class,
    'Library' => \App\Models\Library::class,
    'Course' => \App\Models\Course::class,
    'Subject' => \App\Models\Subject::class,
    'Lesson' => \App\Models\Lesson::class,
    'Assignment' => \App\Models\Assignment::class,
    'Submission' => \App\Models\Submission::class,
    'Forum' => \App\Models\Forum::class,
    'ForumReply' => \App\Models\ForumReply::class,
    'CourseEnrollment' => \App\Models\CourseEnrollment::class
];

foreach ($models as $modelName => $modelClass) {
    try {
        $count = $modelClass::count();
        echo "✅ $modelName model working: $count records\n";
    } catch (Exception $e) {
        echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
    }
}

// 11. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Missing Tables
echo "🧪 Testing Missing Tables\n";
echo "=========================\n\n";

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
    
    // Test Contact model
    try {
        $contact = \\App\\Models\\Contact::active()->first();
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
        \'User\' => \\App\\Models\\User::class,
        \'News\' => \\App\\Models\\News::class,
        \'Gallery\' => \\App\\Models\\Gallery::class,
        \'Facility\' => \\App\\Models\\Facility::class,
        \'SchoolProfile\' => \\App\\Models\\SchoolProfile::class,
        \'HeadmasterGreeting\' => \\App\\Models\\HeadmasterGreeting::class,
        \'Contact\' => \\App\\Models\\Contact::class,
        \'SocialMedia\' => \\App\\Models\\SocialMedia::class,
        \'VisionMission\' => \\App\\Models\\VisionMission::class
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
?>';

$testPath = 'public/test-missing-tables.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-missing-tables.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-missing-tables.php\n";
} else {
    echo "❌ Failed to create test-missing-tables.php\n";
}

echo "\n✅ Missing contacts table fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created contacts table\n";
echo "- Created sample contact data\n";
echo "- Created other missing tables\n";
echo "- Created sample data for other tables\n";
echo "- Tested all models\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Missing Tables Test: http://localhost:8000/test-missing-tables.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Contact: http://localhost:8000/kontak\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-missing-tables.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Check if contacts table error is resolved\n";
echo "4. Check server logs for any remaining errors\n";
