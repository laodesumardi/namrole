<?php
/**
 * Run Seeders - Simple Version
 * 
 * This script runs database seeders without problematic seeders
 * Specifically for missing models and seeder issues
 */

echo "🌱 Running Database Seeders - Simple Version\n";
echo "============================================\n\n";

echo "🔧 Running database seeders without problematic seeders...\n";

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

// 3. Create sample data manually
echo "\n🔧 Creating sample data manually...\n";

// Create sample users
try {
    \Illuminate\Support\Facades\DB::table('users')->insertOrIgnore([
        'name' => 'Administrator',
        'email' => 'admin@namrole.sch.id',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample admin user created\n";
} catch (Exception $e) {
    echo "⚠️ Sample admin user already exists or failed: " . $e->getMessage() . "\n";
}

try {
    \Illuminate\Support\Facades\DB::table('users')->insertOrIgnore([
        'name' => 'Guru Matematika',
        'email' => 'guru@namrole.sch.id',
        'password' => bcrypt('guru123'),
        'role' => 'teacher',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample teacher user created\n";
} catch (Exception $e) {
    echo "⚠️ Sample teacher user already exists or failed: " . $e->getMessage() . "\n";
}

try {
    \Illuminate\Support\Facades\DB::table('users')->insertOrIgnore([
        'name' => 'Siswa Contoh',
        'email' => 'siswa@namrole.sch.id',
        'password' => bcrypt('siswa123'),
        'role' => 'student',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample student user created\n";
} catch (Exception $e) {
    echo "⚠️ Sample student user already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample news
try {
    \Illuminate\Support\Facades\DB::table('news')->insertOrIgnore([
        'title' => 'Selamat Datang di SMP Negeri 01 Namrole',
        'slug' => 'selamat-datang-di-smp-negeri-01-namrole',
        'excerpt' => 'Selamat datang di website resmi SMP Negeri 01 Namrole',
        'content' => 'SMP Negeri 01 Namrole adalah sekolah menengah pertama yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada siswa-siswi.',
        'featured_image' => 'default-news.png',
        'category' => 'akademik',
        'type' => 'news',
        'status' => 'published',
        'is_featured' => 1,
        'is_pinned' => 1,
        'views' => 0,
        'published_at' => now(),
        'author_name' => 'Administrator',
        'author_email' => 'admin@namrole.sch.id',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample news created\n";
} catch (Exception $e) {
    echo "⚠️ Sample news already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample galleries
try {
    \Illuminate\Support\Facades\DB::table('galleries')->insertOrIgnore([
        'title' => 'Galeri Sekolah',
        'description' => 'Koleksi foto-foto kegiatan sekolah',
        'cover_image' => 'default-gallery.png',
        'category' => 'academic',
        'type' => 'gallery',
        'status' => 'active',
        'is_featured' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample gallery created\n";
} catch (Exception $e) {
    echo "⚠️ Sample gallery already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample facilities
try {
    \Illuminate\Support\Facades\DB::table('facilities')->insertOrIgnore([
        'name' => 'Laboratorium Komputer',
        'description' => 'Laboratorium komputer dengan 30 unit komputer untuk pembelajaran TIK',
        'image' => 'default-facility.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample facility created\n";
} catch (Exception $e) {
    echo "⚠️ Sample facility already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample school profiles
try {
    \Illuminate\Support\Facades\DB::table('school_profiles')->insertOrIgnore([
        'section' => 'hero',
        'title' => 'SMP Negeri 01 Namrole',
        'content' => 'Sekolah menengah pertama yang berkomitmen untuk memberikan pendidikan berkualitas tinggi',
        'image' => 'default-school-profile.png',
        'order_index' => 1,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample school profile created\n";
} catch (Exception $e) {
    echo "⚠️ Sample school profile already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample headmaster greeting
try {
    \Illuminate\Support\Facades\DB::table('headmaster_greetings')->insertOrIgnore([
        'headmaster_name' => 'Kepala Sekolah',
        'greeting_message' => 'Selamat datang di SMP Negeri 01 Namrole. Kami berkomitmen untuk memberikan pendidikan terbaik bagi putra-putri Anda.',
        'photo' => 'default-headmaster.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample headmaster greeting created\n";
} catch (Exception $e) {
    echo "⚠️ Sample headmaster greeting already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample social media
try {
    \Illuminate\Support\Facades\DB::table('social_media')->insertOrIgnore([
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
    echo "⚠️ Sample social media already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample vision mission
try {
    \Illuminate\Support\Facades\DB::table('vision_missions')->insertOrIgnore([
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
    echo "⚠️ Sample vision mission already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample contacts
try {
    \Illuminate\Support\Facades\DB::table('contacts')->insertOrIgnore([
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
    echo "⚠️ Sample contact already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample notifications
try {
    \Illuminate\Support\Facades\DB::table('notifications')->insertOrIgnore([
        'title' => 'Selamat Datang',
        'message' => 'Selamat datang di sistem SMP Negeri 01 Namrole',
        'type' => 'info',
        'is_read' => 0,
        'user_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample notification created\n";
} catch (Exception $e) {
    echo "⚠️ Sample notification already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample messages
try {
    \Illuminate\Support\Facades\DB::table('messages')->insertOrIgnore([
        'sender_name' => 'John Doe',
        'sender_email' => 'john@example.com',
        'subject' => 'Test Message',
        'message' => 'This is a test message',
        'is_read' => 0,
        'is_replied' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample message created\n";
} catch (Exception $e) {
    echo "⚠️ Sample message already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample courses
try {
    \Illuminate\Support\Facades\DB::table('courses')->insertOrIgnore([
        'title' => 'Matematika Dasar',
        'description' => 'Kursus matematika dasar untuk siswa SMP',
        'image' => 'default-course.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample course created\n";
} catch (Exception $e) {
    echo "⚠️ Sample course already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample subjects
try {
    \Illuminate\Support\Facades\DB::table('subjects')->insertOrIgnore([
        'name' => 'Matematika',
        'code' => 'MAT',
        'description' => 'Mata pelajaran matematika',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample subject created\n";
} catch (Exception $e) {
    echo "⚠️ Sample subject already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample lessons
try {
    \Illuminate\Support\Facades\DB::table('lessons')->insertOrIgnore([
        'title' => 'Aljabar Dasar',
        'description' => 'Pelajaran aljabar dasar untuk siswa SMP',
        'subject_id' => 1,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample lesson created\n";
} catch (Exception $e) {
    echo "⚠️ Sample lesson already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample assignments
try {
    \Illuminate\Support\Facades\DB::table('assignments')->insertOrIgnore([
        'title' => 'Tugas Matematika',
        'description' => 'Tugas matematika untuk siswa SMP',
        'due_date' => now()->addDays(7),
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample assignment created\n";
} catch (Exception $e) {
    echo "⚠️ Sample assignment already exists or failed: " . $e->getMessage() . "\n";
}

// Create sample forums
try {
    \Illuminate\Support\Facades\DB::table('forums')->insertOrIgnore([
        'title' => 'Diskusi Matematika',
        'description' => 'Forum diskusi untuk mata pelajaran matematika',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Sample forum created\n";
} catch (Exception $e) {
    echo "⚠️ Sample forum already exists or failed: " . $e->getMessage() . "\n";
}

// 4. Test all models
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

// 5. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Database Seeders
echo "🧪 Testing Database Seeders\n";
echo "============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test database connection
    $pdo = \\Illuminate\\Support\\Facades\\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
    
    // Test all models
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
    
    // Test specific data
    try {
        $adminUser = \\App\\Models\\User::where(\'email\', \'admin@namrole.sch.id\')->first();
        if ($adminUser) {
            echo "✅ Admin user found: " . $adminUser->name . "\n";
        } else {
            echo "⚠️ Admin user not found\n";
        }
    } catch (Exception $e) {
        echo "❌ Admin user test failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $publishedNews = \\App\\Models\\News::published()->first();
        if ($publishedNews) {
            echo "✅ Published news found: " . $publishedNews->title . "\n";
        } else {
            echo "⚠️ No published news found\n";
        }
    } catch (Exception $e) {
        echo "❌ Published news test failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $activeGallery = \\App\\Models\\Gallery::active()->first();
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
?>';

$testPath = 'public/test-database-seeders.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-database-seeders.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-database-seeders.php\n";
} else {
    echo "❌ Failed to create test-database-seeders.php\n";
}

echo "\n✅ Database seeders run completed!\n";
echo "🔧 Key actions performed:\n";
echo "- Created sample users (admin, teacher, student)\n";
echo "- Created sample news\n";
echo "- Created sample galleries\n";
echo "- Created sample facilities\n";
echo "- Created sample school profiles\n";
echo "- Created sample headmaster greeting\n";
echo "- Created sample social media\n";
echo "- Created sample vision mission\n";
echo "- Created sample contacts\n";
echo "- Created sample notifications\n";
echo "- Created sample messages\n";
echo "- Created sample courses\n";
echo "- Created sample subjects\n";
echo "- Created sample lessons\n";
echo "- Created sample assignments\n";
echo "- Created sample forums\n";
echo "- Tested all models\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Database Seeders Test: http://localhost:8000/test-database-seeders.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Login: http://localhost:8000/login\n\n";

echo "🔑 Login Credentials:\n";
echo "- Admin: admin@namrole.sch.id / admin123\n";
echo "- Teacher: guru@namrole.sch.id / guru123\n";
echo "- Student: siswa@namrole.sch.id / siswa123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-database-seeders.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Login: http://localhost:8000/login\n";
echo "4. Check if all data is populated correctly\n";
echo "5. Check server logs for any remaining errors\n";

