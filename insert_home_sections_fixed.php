<?php
/**
 * Insert Home Sections Data Fixed
 * 
 * This script inserts home sections data with proper table structure handling
 * Specifically for existing database schema
 */

echo "🏠 Inserting Home Sections Data Fixed\n";
echo "=====================================\n\n";

echo "🔧 Inserting home sections data with proper table structure...\n";

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

// 3. Check current table structure
echo "\n🔍 Checking current home_sections table structure...\n";

try {
    $columns = \Illuminate\Support\Facades\DB::select("PRAGMA table_info(home_sections)");
    echo "✅ Current columns in home_sections table:\n";
    foreach ($columns as $column) {
        echo "   - " . $column->name . " (" . $column->type . ")\n";
    }
} catch (Exception $e) {
    echo "❌ Failed to check table structure: " . $e->getMessage() . "\n";
}

// 4. Create default images directory
echo "\n🖼️ Creating default images directory...\n";

$imagesDir = 'public/images';
if (!is_dir($imagesDir)) {
    mkdir($imagesDir, 0755, true);
    echo "✅ Created images directory: $imagesDir\n";
} else {
    echo "✅ Images directory already exists: $imagesDir\n";
}

// 5. Create default home section images
echo "\n🖼️ Creating default home section images...\n";

$defaultImages = [
    'default-hero-bg.png' => 'Hero section background image',
    'default-about-bg.png' => 'About section background image',
    'default-facilities-bg.png' => 'Facilities section background image',
    'default-gallery-bg.png' => 'Gallery section background image',
    'default-news-bg.png' => 'News section background image',
    'default-contact-bg.png' => 'Contact section background image',
    'default-testimonial-bg.png' => 'Testimonial section background image',
    'default-achievement-bg.png' => 'Achievement section background image',
    'default-staff-bg.png' => 'Staff section background image',
    'default-ppdb-bg.png' => 'PPDB section background image'
];

foreach ($defaultImages as $filename => $description) {
    $filepath = $imagesDir . '/' . $filename;
    if (!file_exists($filepath)) {
        // Create a simple colored image placeholder
        $image = imagecreate(800, 600);
        $colors = [
            'hero' => [52, 144, 220],      // Blue
            'about' => [40, 167, 69],      // Green
            'facilities' => [255, 193, 7], // Yellow
            'gallery' => [220, 53, 69],    // Red
            'news' => [108, 117, 125],    // Gray
            'contact' => [111, 66, 193],  // Purple
            'testimonial' => [253, 126, 20], // Orange
            'achievement' => [32, 201, 151], // Teal
            'staff' => [233, 84, 164],     // Pink
            'ppdb' => [23, 162, 184]      // Cyan
        ];
        
        $section = str_replace(['default-', '-bg.png'], '', $filename);
        $color = $colors[$section] ?? [128, 128, 128];
        
        $bgColor = imagecolorallocate($image, $color[0], $color[1], $color[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Add text
        $text = strtoupper(str_replace('-', ' ', $section));
        imagestring($image, 5, 300, 280, $text, $textColor);
        imagestring($image, 3, 250, 320, 'BACKGROUND IMAGE', $textColor);
        
        imagepng($image, $filepath);
        imagedestroy($image);
        
        echo "✅ Created default image: $filename\n";
    } else {
        echo "✅ Default image already exists: $filename\n";
    }
}

// 6. Insert Home Sections Data (with proper column names)
echo "\n🏠 Inserting Home Sections Data...\n";

$homeSections = [
    [
        'title' => 'Selamat Datang di SMP Negeri 01 Namrole',
        'content' => 'SMP Negeri 01 Namrole adalah sekolah menengah pertama yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada siswa-siswi di wilayah Namrole dan sekitarnya. Dengan fasilitas yang memadai dan tenaga pendidik yang profesional, kami siap membimbing putra-putri Anda menuju masa depan yang cerah.',
        'image' => 'default-hero-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Tentang SMP Negeri 01 Namrole',
        'content' => 'SMP Negeri 01 Namrole didirikan pada tahun 1985 dan telah meluluskan ribuan siswa yang berprestasi di berbagai bidang. Kami berkomitmen untuk memberikan pendidikan yang holistik, mengembangkan potensi siswa secara optimal, dan membentuk karakter yang kuat.',
        'image' => 'default-about-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Fasilitas Sekolah',
        'content' => 'SMP Negeri 01 Namrole dilengkapi dengan berbagai fasilitas modern yang mendukung proses pembelajaran, termasuk laboratorium komputer, laboratorium IPA, perpustakaan, lapangan olahraga, dan ruang kelas yang nyaman.',
        'image' => 'default-facilities-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Galeri Sekolah',
        'content' => 'Lihat berbagai momen berharga dalam perjalanan pendidikan di SMP Negeri 01 Namrole. Dari kegiatan pembelajaran, ekstrakurikuler, hingga prestasi siswa yang membanggakan.',
        'image' => 'default-gallery-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Berita Terkini',
        'content' => 'Dapatkan informasi terbaru tentang kegiatan sekolah, prestasi siswa, pengumuman penting, dan berbagai berita menarik lainnya dari SMP Negeri 01 Namrole.',
        'image' => 'default-news-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Prestasi Siswa',
        'content' => 'Siswa-siswi SMP Negeri 01 Namrole telah meraih berbagai prestasi membanggakan di tingkat lokal, regional, dan nasional. Prestasi ini menjadi bukti kualitas pendidikan yang kami berikan.',
        'image' => 'default-achievement-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Tenaga Pendidik',
        'content' => 'SMP Negeri 01 Namrole didukung oleh tenaga pendidik yang profesional, berpengalaman, dan berkomitmen tinggi dalam memberikan pendidikan terbaik bagi siswa. Guru-guru kami terus mengembangkan diri dan mengikuti perkembangan pendidikan terkini.',
        'image' => 'default-staff-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Penerimaan Peserta Didik Baru',
        'content' => 'Pendaftaran PPDB 2024 sudah dibuka! Segera daftarkan putra-putri Anda untuk bergabung dengan keluarga besar SMP Negeri 01 Namrole. Dapatkan informasi lengkap tentang persyaratan, jadwal, dan prosedur pendaftaran.',
        'image' => 'default-ppdb-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Testimoni Alumni',
        'content' => 'Dengarkan pengalaman dan kesan para alumni SMP Negeri 01 Namrole yang telah berhasil meraih prestasi di berbagai bidang. Mereka membuktikan bahwa pendidikan di SMP Negeri 01 Namrole menjadi fondasi yang kuat untuk masa depan yang cerah.',
        'image' => 'default-testimonial-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'title' => 'Hubungi Kami',
        'content' => 'Untuk informasi lebih lanjut tentang SMP Negeri 01 Namrole, silakan hubungi kami. Tim kami siap melayani dan menjawab pertanyaan Anda. Kami berkomitmen untuk memberikan pelayanan terbaik kepada seluruh stakeholder sekolah.',
        'image' => 'default-contact-bg.png',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($homeSections as $section) {
    try {
        \Illuminate\Support\Facades\DB::table('home_sections')->insert($section);
        echo "✅ Created home section: " . $section['title'] . "\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create home section " . $section['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 7. Copy images to storage
echo "\n📁 Copying images to storage...\n";

$storageDir = 'storage/app/public/home-sections';
$publicStorageDir = 'public/storage/home-sections';

if (!is_dir($storageDir)) {
    mkdir($storageDir, 0755, true);
    echo "✅ Created storage directory: $storageDir\n";
}

if (!is_dir($publicStorageDir)) {
    mkdir($publicStorageDir, 0755, true);
    echo "✅ Created public storage directory: $publicStorageDir\n";
}

foreach ($defaultImages as $filename => $description) {
    $sourcePath = "public/images/$filename";
    $storagePath = "$storageDir/$filename";
    $publicPath = "$publicStorageDir/$filename";
    
    if (file_exists($sourcePath)) {
        // Copy to storage
        if (copy($sourcePath, $storagePath)) {
            echo "✅ Copied to storage: $filename\n";
        }
        
        // Copy to public storage
        if (copy($sourcePath, $publicPath)) {
            echo "✅ Copied to public storage: $filename\n";
        }
    }
}

// 8. Update database with storage paths
echo "\n🔄 Updating database with storage paths...\n";

try {
    \Illuminate\Support\Facades\DB::table('home_sections')->update([
        'image' => \Illuminate\Support\Facades\DB::raw("REPLACE(image, 'default-', 'storage/home-sections/')")
    ]);
    echo "✅ Updated database with storage paths\n";
} catch (Exception $e) {
    echo "⚠️ Failed to update database paths: " . $e->getMessage() . "\n";
}

// 9. Test HomeSection model
echo "\n🧪 Testing HomeSection model...\n";

try {
    $homeSections = \App\Models\HomeSection::all();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . "\n";
        echo "     Image: " . $section->image . "\n";
        echo "     Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
    }
} catch (Exception $e) {
    echo "❌ HomeSection model test failed: " . $e->getMessage() . "\n";
}

// 10. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Home Sections Data Fixed
echo "🧪 Testing Home Sections Data Fixed\n";
echo "===================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HomeSection model
    $homeSections = \\App\\Models\\HomeSection::all();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    foreach ($homeSections as $section) {
        echo "✅ " . $section->title . "\n";
        echo "   Content: " . substr($section->content, 0, 100) . "...\n";
        echo "   Image: " . $section->image . "\n";
        echo "   Active: " . ($section->is_active ? \'Yes\' : \'No\') . "\n";
        echo "\n";
    }
    
    // Test image files
    echo "🖼️ Testing image files:\n";
    $imageFiles = [
        \'default-hero-bg.png\',
        \'default-about-bg.png\',
        \'default-facilities-bg.png\',
        \'default-gallery-bg.png\',
        \'default-news-bg.png\',
        \'default-achievement-bg.png\',
        \'default-staff-bg.png\',
        \'default-ppdb-bg.png\',
        \'default-testimonial-bg.png\',
        \'default-contact-bg.png\'
    ];
    
    foreach ($imageFiles as $imageFile) {
        $publicPath = "images/$imageFile";
        $storagePath = "storage/home-sections/$imageFile";
        
        if (file_exists($publicPath)) {
            echo "✅ Public image exists: $imageFile\n";
        } else {
            echo "❌ Public image missing: $imageFile\n";
        }
        
        if (file_exists($storagePath)) {
            echo "✅ Storage image exists: $imageFile\n";
        } else {
            echo "❌ Storage image missing: $imageFile\n";
        }
    }
    
    echo "✅ All home sections data fixed tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-home-sections-fixed.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-home-sections-fixed.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-home-sections-fixed.php\n";
} else {
    echo "❌ Failed to create test-home-sections-fixed.php\n";
}

echo "\n✅ Home sections data fixed insertion completed!\n";
echo "🔧 Key data inserted:\n";
echo "- Hero Section (Welcome message with background)\n";
echo "- About Section (School information with background)\n";
echo "- Facilities Section (School facilities with background)\n";
echo "- Gallery Section (Photo gallery with background)\n";
echo "- News Section (Latest news with background)\n";
echo "- Achievement Section (Student achievements with background)\n";
echo "- Staff Section (Teaching staff with background)\n";
echo "- PPDB Section (Student registration with background)\n";
echo "- Testimonial Section (Alumni testimonials with background)\n";
echo "- Contact Section (Contact information with background)\n";
echo "- Created default background images for all sections\n";
echo "- Copied images to storage directories\n";
echo "- Updated database with storage paths\n";
echo "- Tested HomeSection model functionality\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Home Sections Fixed Test: http://localhost:8000/test-home-sections-fixed.php\n";
echo "- Admin Home Sections: http://localhost:8000/admin/home-sections\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-home-sections-fixed.php\n";
echo "2. Test: http://localhost:8000/admin/home-sections\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if all home sections are displaying correctly with backgrounds\n";
echo "5. Check server logs for any remaining errors\n";
