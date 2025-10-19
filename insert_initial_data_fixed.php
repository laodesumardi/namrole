<?php
/**
 * Insert Initial Data Fixed
 * 
 * This script inserts initial data with proper table structure handling
 * Specifically for existing database schema
 */

echo "🌱 Inserting Initial Data Fixed\n";
echo "===============================\n\n";

echo "🔧 Inserting initial data with proper table structure...\n";

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

// 3. Insert Users (with proper structure)
echo "\n👥 Inserting Users...\n";

$users = [
    [
        'name' => 'Administrator Sekolah',
        'email' => 'admin@namrole.sch.id',
        'password' => bcrypt('admin123'),
        'role' => 'admin',
        'phone' => '08123456789',
        'address' => 'Jl. Pendidikan No. 1, Namrole',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Kepala Sekolah',
        'email' => 'kepala@namrole.sch.id',
        'password' => bcrypt('kepala123'),
        'role' => 'admin',
        'phone' => '08123456790',
        'address' => 'Jl. Pendidikan No. 1, Namrole',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Siti Nurhaliza, S.Pd.',
        'email' => 'siti.nurhaliza@smpn01namrole.sch.id',
        'password' => bcrypt('guru123'),
        'role' => 'teacher',
        'phone' => '08123456791',
        'address' => 'Jl. Guru No. 5, Namrole',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Budi Santoso, S.Pd.',
        'email' => 'budi.santoso@smpn01namrole.sch.id',
        'password' => bcrypt('guru123'),
        'role' => 'teacher',
        'phone' => '08123456792',
        'address' => 'Jl. Guru No. 6, Namrole',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Ahmad Rizki',
        'email' => 'ahmad.rizki@smpn01namrole.sch.id',
        'password' => bcrypt('siswa123'),
        'role' => 'student',
        'phone' => '08123456793',
        'address' => 'Jl. Siswa No. 10, Namrole',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Sari Indah',
        'email' => 'sari.indah@smpn01namrole.sch.id',
        'password' => bcrypt('siswa123'),
        'role' => 'student',
        'phone' => '08123456794',
        'address' => 'Jl. Siswa No. 11, Namrole',
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($users as $user) {
    try {
        \Illuminate\Support\Facades\DB::table('users')->insertOrIgnore($user);
        echo "✅ Created user: " . $user['name'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create user " . $user['name'] . ": " . $e->getMessage() . "\n";
    }
}

// 4. Insert News
echo "\n📰 Inserting News...\n";

$news = [
    [
        'title' => 'Selamat Datang di SMP Negeri 01 Namrole',
        'slug' => 'selamat-datang-di-smp-negeri-01-namrole',
        'excerpt' => 'Selamat datang di website resmi SMP Negeri 01 Namrole. Kami berkomitmen untuk memberikan pendidikan berkualitas tinggi.',
        'content' => 'SMP Negeri 01 Namrole adalah sekolah menengah pertama yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada siswa-siswi. Dengan fasilitas yang memadai dan tenaga pendidik yang profesional, kami siap membimbing putra-putri Anda menuju masa depan yang cerah.',
        'featured_image' => 'default-news.png',
        'category' => 'akademik',
        'type' => 'news',
        'status' => 'published',
        'is_featured' => 1,
        'is_pinned' => 1,
        'views' => 150,
        'published_at' => now(),
        'author_name' => 'Administrator Sekolah',
        'author_email' => 'admin@namrole.sch.id',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Penerimaan Peserta Didik Baru (PPDB) 2024',
        'slug' => 'penerimaan-peserta-didik-baru-ppdb-2024',
        'excerpt' => 'Pendaftaran PPDB 2024 sudah dibuka. Segera daftarkan putra-putri Anda untuk bergabung dengan keluarga besar SMP Negeri 01 Namrole.',
        'content' => 'Penerimaan Peserta Didik Baru (PPDB) tahun 2024 sudah dibuka. Kami mengundang para orang tua untuk mendaftarkan putra-putri mereka yang akan lulus dari SD/MI untuk bergabung dengan keluarga besar SMP Negeri 01 Namrole. Persyaratan dan jadwal pendaftaran dapat dilihat di halaman PPDB.',
        'featured_image' => 'default-news.png',
        'category' => 'ppdb',
        'type' => 'announcement',
        'status' => 'published',
        'is_featured' => 1,
        'is_pinned' => 1,
        'views' => 200,
        'published_at' => now(),
        'author_name' => 'Administrator Sekolah',
        'author_email' => 'admin@namrole.sch.id',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Kegiatan Ekstrakurikuler Semester Genap',
        'slug' => 'kegiatan-ekstrakurikuler-semester-genap',
        'excerpt' => 'Berbagai kegiatan ekstrakurikuler menarik tersedia untuk siswa SMP Negeri 01 Namrole.',
        'content' => 'SMP Negeri 01 Namrole menyediakan berbagai kegiatan ekstrakurikuler yang menarik dan bermanfaat untuk mengembangkan bakat dan minat siswa. Kegiatan yang tersedia meliputi: Pramuka, PMR, Paskibra, Olahraga, Seni, dan masih banyak lagi.',
        'featured_image' => 'default-news.png',
        'category' => 'ekstrakurikuler',
        'type' => 'news',
        'status' => 'published',
        'is_featured' => 0,
        'is_pinned' => 0,
        'views' => 75,
        'published_at' => now(),
        'author_name' => 'Siti Nurhaliza, S.Pd.',
        'author_email' => 'siti.nurhaliza@smpn01namrole.sch.id',
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($news as $newsItem) {
    try {
        \Illuminate\Support\Facades\DB::table('news')->insertOrIgnore($newsItem);
        echo "✅ Created news: " . $newsItem['title'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create news " . $newsItem['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 5. Insert Galleries (with slug)
echo "\n🖼️ Inserting Galleries...\n";

$galleries = [
    [
        'title' => 'Galeri Kegiatan Sekolah',
        'slug' => 'galeri-kegiatan-sekolah',
        'description' => 'Koleksi foto-foto kegiatan sekolah yang berlangsung sepanjang tahun',
        'cover_image' => 'default-gallery.png',
        'category' => 'academic',
        'type' => 'gallery',
        'status' => 'active',
        'is_featured' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Album Ekstrakurikuler',
        'slug' => 'album-ekstrakurikuler',
        'description' => 'Dokumentasi kegiatan ekstrakurikuler siswa',
        'cover_image' => 'default-gallery.png',
        'category' => 'extracurricular',
        'type' => 'album',
        'status' => 'active',
        'is_featured' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Galeri Prestasi Siswa',
        'slug' => 'galeri-prestasi-siswa',
        'description' => 'Koleksi foto prestasi dan pencapaian siswa',
        'cover_image' => 'default-gallery.png',
        'category' => 'achievement',
        'type' => 'gallery',
        'status' => 'active',
        'is_featured' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($galleries as $gallery) {
    try {
        \Illuminate\Support\Facades\DB::table('galleries')->insertOrIgnore($gallery);
        echo "✅ Created gallery: " . $gallery['title'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create gallery " . $gallery['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 6. Insert Gallery Items (with proper column names)
echo "\n🖼️ Inserting Gallery Items...\n";

$galleryItems = [
    [
        'gallery_id' => 1,
        'title' => 'Upacara Bendera',
        'description' => 'Upacara bendera setiap hari Senin',
        'image_path' => 'default-gallery-item.png',
        'is_featured' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'gallery_id' => 1,
        'title' => 'Kegiatan Belajar Mengajar',
        'description' => 'Aktivitas pembelajaran di kelas',
        'image_path' => 'default-gallery-item.png',
        'is_featured' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'gallery_id' => 2,
        'title' => 'Latihan Pramuka',
        'description' => 'Kegiatan latihan pramuka',
        'image_path' => 'default-gallery-item.png',
        'is_featured' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($galleryItems as $item) {
    try {
        \Illuminate\Support\Facades\DB::table('gallery_items')->insertOrIgnore($item);
        echo "✅ Created gallery item: " . $item['title'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create gallery item " . $item['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 7. Insert Facilities
echo "\n🏢 Inserting Facilities...\n";

$facilities = [
    [
        'name' => 'Laboratorium Komputer',
        'description' => 'Laboratorium komputer dengan 30 unit komputer untuk pembelajaran TIK',
        'image' => 'default-facility.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Laboratorium IPA',
        'description' => 'Laboratorium IPA yang dilengkapi dengan peralatan praktikum',
        'image' => 'default-facility.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Perpustakaan',
        'description' => 'Perpustakaan dengan koleksi buku yang lengkap',
        'image' => 'default-facility.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Lapangan Olahraga',
        'description' => 'Lapangan olahraga untuk kegiatan olahraga dan upacara',
        'image' => 'default-facility.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($facilities as $facility) {
    try {
        \Illuminate\Support\Facades\DB::table('facilities')->insertOrIgnore($facility);
        echo "✅ Created facility: " . $facility['name'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create facility " . $facility['name'] . ": " . $e->getMessage() . "\n";
    }
}

// 8. Insert School Profiles (with proper column names)
echo "\n🏫 Inserting School Profiles...\n";

$schoolProfiles = [
    [
        'title' => 'SMP Negeri 01 Namrole',
        'content' => 'Sekolah menengah pertama yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada siswa-siswi di wilayah Namrole dan sekitarnya.',
        'image' => 'default-school-profile.png',
        'order_index' => 1,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Tentang Sekolah',
        'content' => 'SMP Negeri 01 Namrole didirikan pada tahun 1985 dan telah meluluskan ribuan siswa yang berprestasi di berbagai bidang.',
        'image' => 'default-school-profile.png',
        'order_index' => 2,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Visi Sekolah',
        'content' => 'Menjadi sekolah unggulan yang menghasilkan generasi berkarakter, berprestasi, dan berakhlak mulia.',
        'image' => 'default-school-profile.png',
        'order_index' => 3,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($schoolProfiles as $profile) {
    try {
        \Illuminate\Support\Facades\DB::table('school_profiles')->insertOrIgnore($profile);
        echo "✅ Created school profile: " . $profile['title'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create school profile " . $profile['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 9. Insert Headmaster Greetings
echo "\n👨‍💼 Inserting Headmaster Greetings...\n";

$headmasterGreetings = [
    [
        'headmaster_name' => 'Drs. H. Ahmad Fauzi, M.Pd.',
        'greeting_message' => 'Selamat datang di SMP Negeri 01 Namrole. Kami berkomitmen untuk memberikan pendidikan terbaik bagi putra-putri Anda. Dengan dukungan tenaga pendidik yang profesional dan fasilitas yang memadai, kami siap membimbing siswa menuju masa depan yang cerah.',
        'photo' => 'default-headmaster.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($headmasterGreetings as $greeting) {
    try {
        \Illuminate\Support\Facades\DB::table('headmaster_greetings')->insertOrIgnore($greeting);
        echo "✅ Created headmaster greeting: " . $greeting['headmaster_name'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create headmaster greeting " . $greeting['headmaster_name'] . ": " . $e->getMessage() . "\n";
    }
}

// 10. Insert Social Media
echo "\n📱 Inserting Social Media...\n";

$socialMedia = [
    [
        'platform' => 'Facebook',
        'icon' => 'facebook',
        'url' => 'https://facebook.com/smpnegeri01namrole',
        'is_active' => 1,
        'order_index' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'platform' => 'Instagram',
        'icon' => 'instagram',
        'url' => 'https://instagram.com/smpnegeri01namrole',
        'is_active' => 1,
        'order_index' => 2,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'platform' => 'YouTube',
        'icon' => 'youtube',
        'url' => 'https://youtube.com/@smpnegeri01namrole',
        'is_active' => 1,
        'order_index' => 3,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'platform' => 'WhatsApp',
        'icon' => 'whatsapp',
        'url' => 'https://wa.me/6281234567890',
        'is_active' => 1,
        'order_index' => 4,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($socialMedia as $social) {
    try {
        \Illuminate\Support\Facades\DB::table('social_media')->insertOrIgnore($social);
        echo "✅ Created social media: " . $social['platform'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create social media " . $social['platform'] . ": " . $e->getMessage() . "\n";
    }
}

// 11. Insert Vision Missions
echo "\n🎯 Inserting Vision Missions...\n";

$visionMissions = [
    [
        'type' => 'vision',
        'title' => 'Visi Sekolah',
        'content' => 'Menjadi sekolah unggulan yang menghasilkan generasi berkarakter, berprestasi, dan berakhlak mulia',
        'is_active' => 1,
        'order_index' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'type' => 'mission',
        'title' => 'Misi Sekolah',
        'content' => '1. Menyelenggarakan pendidikan yang berkualitas dan berkarakter\n2. Mengembangkan potensi siswa secara optimal\n3. Membentuk siswa yang berakhlak mulia dan berprestasi\n4. Menjalin kerjasama yang harmonis dengan masyarakat',
        'is_active' => 1,
        'order_index' => 2,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($visionMissions as $vm) {
    try {
        \Illuminate\Support\Facades\DB::table('vision_missions')->insertOrIgnore($vm);
        echo "✅ Created vision mission: " . $vm['title'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create vision mission " . $vm['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 12. Insert Contacts
echo "\n📞 Inserting Contacts...\n";

$contacts = [
    [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '08123456789',
        'subject' => 'Informasi PPDB',
        'message' => 'Saya ingin bertanya tentang informasi PPDB 2024',
        'is_active' => 1,
        'is_read' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'phone' => '08123456790',
        'subject' => 'Kegiatan Ekstrakurikuler',
        'message' => 'Apakah ada kegiatan ekstrakurikuler untuk siswa kelas 7?',
        'is_active' => 1,
        'is_read' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($contacts as $contact) {
    try {
        \Illuminate\Support\Facades\DB::table('contacts')->insertOrIgnore($contact);
        echo "✅ Created contact: " . $contact['name'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create contact " . $contact['name'] . ": " . $e->getMessage() . "\n";
    }
}

// 13. Insert Notifications
echo "\n🔔 Inserting Notifications...\n";

$notifications = [
    [
        'title' => 'Selamat Datang',
        'message' => 'Selamat datang di sistem SMP Negeri 01 Namrole',
        'type' => 'info',
        'is_read' => 0,
        'user_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'PPDB 2024 Dibuka',
        'message' => 'Pendaftaran PPDB 2024 sudah dibuka. Segera daftarkan putra-putri Anda.',
        'type' => 'announcement',
        'is_read' => 0,
        'user_id' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($notifications as $notification) {
    try {
        \Illuminate\Support\Facades\DB::table('notifications')->insertOrIgnore($notification);
        echo "✅ Created notification: " . $notification['title'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create notification " . $notification['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 14. Insert Messages
echo "\n💬 Inserting Messages...\n";

$messages = [
    [
        'sender_name' => 'John Doe',
        'sender_email' => 'john@example.com',
        'subject' => 'Informasi Sekolah',
        'message' => 'Saya ingin mendapatkan informasi lebih lanjut tentang SMP Negeri 01 Namrole',
        'is_read' => 0,
        'is_replied' => 0,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'sender_name' => 'Jane Smith',
        'sender_email' => 'jane@example.com',
        'subject' => 'Kegiatan Sekolah',
        'message' => 'Apakah ada kegiatan khusus yang diadakan sekolah?',
        'is_read' => 1,
        'is_replied' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($messages as $message) {
    try {
        \Illuminate\Support\Facades\DB::table('messages')->insertOrIgnore($message);
        echo "✅ Created message: " . $message['subject'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create message " . $message['subject'] . ": " . $e->getMessage() . "\n";
    }
}

// 15. Insert Subjects
echo "\n📖 Inserting Subjects...\n";

$subjects = [
    [
        'name' => 'Matematika',
        'code' => 'MAT',
        'description' => 'Mata pelajaran matematika',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'Bahasa Indonesia',
        'code' => 'BIN',
        'description' => 'Mata pelajaran bahasa Indonesia',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'name' => 'IPA',
        'code' => 'IPA',
        'description' => 'Mata pelajaran IPA',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($subjects as $subject) {
    try {
        \Illuminate\Support\Facades\DB::table('subjects')->insertOrIgnore($subject);
        echo "✅ Created subject: " . $subject['name'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create subject " . $subject['name'] . ": " . $e->getMessage() . "\n";
    }
}

// 16. Test all models
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
    'Subject' => \App\Models\Subject::class
];

foreach ($models as $modelName => $modelClass) {
    try {
        $count = $modelClass::count();
        echo "✅ $modelName model working: $count records\n";
    } catch (Exception $e) {
        echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
    }
}

// 17. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Initial Data Fixed
echo "🧪 Testing Initial Data Fixed\n";
echo "=============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
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
        \'VisionMission\' => \\App\\Models\\VisionMission::class,
        \'Subject\' => \\App\\Models\\Subject::class
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
    
    echo "✅ All initial data fixed tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-initial-data-fixed.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-initial-data-fixed.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-initial-data-fixed.php\n";
} else {
    echo "❌ Failed to create test-initial-data-fixed.php\n";
}

echo "\n✅ Initial data fixed insertion completed!\n";
echo "🔧 Key data inserted:\n";
echo "- Users (Admin, Teachers, Students)\n";
echo "- News (Published articles)\n";
echo "- Galleries (School galleries with slugs)\n";
echo "- Gallery Items (Gallery photos with proper columns)\n";
echo "- Facilities (School facilities)\n";
echo "- School Profiles (School information with proper columns)\n";
echo "- Headmaster Greetings (Principal messages)\n";
echo "- Social Media (Social media links)\n";
echo "- Vision Missions (School vision and mission)\n";
echo "- Contacts (Contact messages)\n";
echo "- Notifications (System notifications)\n";
echo "- Messages (Internal messages)\n";
echo "- Subjects (School subjects)\n";
echo "- Tested all models\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Initial Data Fixed Test: http://localhost:8000/test-initial-data-fixed.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Login: http://localhost:8000/login\n\n";

echo "🔑 Login Credentials:\n";
echo "- Admin: admin@namrole.sch.id / admin123\n";
echo "- Teacher: siti.nurhaliza@smpn01namrole.sch.id / guru123\n";
echo "- Student: ahmad.rizki@smpn01namrole.sch.id / siswa123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-initial-data-fixed.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Login: http://localhost:8000/login\n";
echo "4. Check if all data is populated correctly\n";
echo "5. Check server logs for any remaining errors\n";
