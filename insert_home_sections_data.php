<?php
/**
 * Insert Home Sections Data
 * 
 * This script inserts comprehensive home sections data with background images
 * Specifically for http://localhost:8000/admin/home-sections
 */

echo "🏠 Inserting Home Sections Data\n";
echo "==============================\n\n";

echo "🔧 Inserting comprehensive home sections data with background images...\n";

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

// 3. Clear existing home sections data
echo "\n🧹 Clearing existing home sections data...\n";

try {
    \Illuminate\Support\Facades\DB::table('home_sections')->truncate();
    echo "✅ Cleared existing home sections data\n";
} catch (Exception $e) {
    echo "⚠️ Table home_sections not found or already empty: " . $e->getMessage() . "\n";
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

// 6. Insert Home Sections Data
echo "\n🏠 Inserting Home Sections Data...\n";

$homeSections = [
    [
        'section' => 'hero',
        'title' => 'Selamat Datang di SMP Negeri 01 Namrole',
        'subtitle' => 'Sekolah Unggulan dengan Pendidikan Berkualitas',
        'content' => 'SMP Negeri 01 Namrole adalah sekolah menengah pertama yang berkomitmen untuk memberikan pendidikan berkualitas tinggi kepada siswa-siswi di wilayah Namrole dan sekitarnya. Dengan fasilitas yang memadai dan tenaga pendidik yang profesional, kami siap membimbing putra-putri Anda menuju masa depan yang cerah.',
        'image' => 'default-hero-bg.png',
        'button_text' => 'Pelajari Lebih Lanjut',
        'button_url' => '/profil',
        'is_active' => 1,
        'order_index' => 1,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'about',
        'title' => 'Tentang SMP Negeri 01 Namrole',
        'subtitle' => 'Membangun Generasi Berkarakter dan Berprestasi',
        'content' => 'SMP Negeri 01 Namrole didirikan pada tahun 1985 dan telah meluluskan ribuan siswa yang berprestasi di berbagai bidang. Kami berkomitmen untuk memberikan pendidikan yang holistik, mengembangkan potensi siswa secara optimal, dan membentuk karakter yang kuat.',
        'image' => 'default-about-bg.png',
        'button_text' => 'Baca Selengkapnya',
        'button_url' => '/profil',
        'is_active' => 1,
        'order_index' => 2,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'facilities',
        'title' => 'Fasilitas Sekolah',
        'subtitle' => 'Fasilitas Lengkap untuk Mendukung Pembelajaran',
        'content' => 'SMP Negeri 01 Namrole dilengkapi dengan berbagai fasilitas modern yang mendukung proses pembelajaran, termasuk laboratorium komputer, laboratorium IPA, perpustakaan, lapangan olahraga, dan ruang kelas yang nyaman.',
        'image' => 'default-facilities-bg.png',
        'button_text' => 'Lihat Fasilitas',
        'button_url' => '/fasilitas',
        'is_active' => 1,
        'order_index' => 3,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'gallery',
        'title' => 'Galeri Sekolah',
        'subtitle' => 'Momen Berharga dalam Perjalanan Pendidikan',
        'content' => 'Lihat berbagai momen berharga dalam perjalanan pendidikan di SMP Negeri 01 Namrole. Dari kegiatan pembelajaran, ekstrakurikuler, hingga prestasi siswa yang membanggakan.',
        'image' => 'default-gallery-bg.png',
        'button_text' => 'Lihat Galeri',
        'button_url' => '/galeri',
        'is_active' => 1,
        'order_index' => 4,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'news',
        'title' => 'Berita Terkini',
        'subtitle' => 'Informasi Terbaru dari SMP Negeri 01 Namrole',
        'content' => 'Dapatkan informasi terbaru tentang kegiatan sekolah, prestasi siswa, pengumuman penting, dan berbagai berita menarik lainnya dari SMP Negeri 01 Namrole.',
        'image' => 'default-news-bg.png',
        'button_text' => 'Baca Berita',
        'button_url' => '/berita',
        'is_active' => 1,
        'order_index' => 5,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'achievement',
        'title' => 'Prestasi Siswa',
        'subtitle' => 'Kebanggaan dan Prestasi yang Membanggakan',
        'content' => 'Siswa-siswi SMP Negeri 01 Namrole telah meraih berbagai prestasi membanggakan di tingkat lokal, regional, dan nasional. Prestasi ini menjadi bukti kualitas pendidikan yang kami berikan.',
        'image' => 'default-achievement-bg.png',
        'button_text' => 'Lihat Prestasi',
        'button_url' => '/prestasi',
        'is_active' => 1,
        'order_index' => 6,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'staff',
        'title' => 'Tenaga Pendidik',
        'subtitle' => 'Guru Profesional dan Berpengalaman',
        'content' => 'SMP Negeri 01 Namrole didukung oleh tenaga pendidik yang profesional, berpengalaman, dan berkomitmen tinggi dalam memberikan pendidikan terbaik bagi siswa. Guru-guru kami terus mengembangkan diri dan mengikuti perkembangan pendidikan terkini.',
        'image' => 'default-staff-bg.png',
        'button_text' => 'Lihat Guru',
        'button_url' => '/guru',
        'is_active' => 1,
        'order_index' => 7,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'ppdb',
        'title' => 'Penerimaan Peserta Didik Baru',
        'subtitle' => 'Bergabunglah dengan Keluarga Besar SMP Negeri 01 Namrole',
        'content' => 'Pendaftaran PPDB 2024 sudah dibuka! Segera daftarkan putra-putri Anda untuk bergabung dengan keluarga besar SMP Negeri 01 Namrole. Dapatkan informasi lengkap tentang persyaratan, jadwal, dan prosedur pendaftaran.',
        'image' => 'default-ppdb-bg.png',
        'button_text' => 'Daftar Sekarang',
        'button_url' => '/ppdb',
        'is_active' => 1,
        'order_index' => 8,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'testimonial',
        'title' => 'Testimoni Alumni',
        'subtitle' => 'Kisah Sukses dari Alumni SMP Negeri 01 Namrole',
        'content' => 'Dengarkan pengalaman dan kesan para alumni SMP Negeri 01 Namrole yang telah berhasil meraih prestasi di berbagai bidang. Mereka membuktikan bahwa pendidikan di SMP Negeri 01 Namrole menjadi fondasi yang kuat untuk masa depan yang cerah.',
        'image' => 'default-testimonial-bg.png',
        'button_text' => 'Baca Testimoni',
        'button_url' => '/testimoni',
        'is_active' => 1,
        'order_index' => 9,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section' => 'contact',
        'title' => 'Hubungi Kami',
        'subtitle' => 'Kami Siap Melayani dan Membantu Anda',
        'content' => 'Untuk informasi lebih lanjut tentang SMP Negeri 01 Namrole, silakan hubungi kami. Tim kami siap melayani dan menjawab pertanyaan Anda. Kami berkomitmen untuk memberikan pelayanan terbaik kepada seluruh stakeholder sekolah.',
        'image' => 'default-contact-bg.png',
        'button_text' => 'Kontak Kami',
        'button_url' => '/kontak',
        'is_active' => 1,
        'order_index' => 10,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($homeSections as $section) {
    try {
        \Illuminate\Support\Facades\DB::table('home_sections')->insert($section);
        echo "✅ Created home section: " . $section['title'] . " (" . $section['section'] . ")\n";
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
    $homeSections = \App\Models\HomeSection::orderBy('order_index')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section . ")\n";
        echo "     Image: " . $section->image . "\n";
        echo "     Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
        echo "     Order: " . $section->order_index . "\n";
    }
} catch (Exception $e) {
    echo "❌ HomeSection model test failed: " . $e->getMessage() . "\n";
}

// 10. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Home Sections Data
echo "🧪 Testing Home Sections Data\n";
echo "==============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HomeSection model
    $homeSections = \\App\\Models\\HomeSection::orderBy(\'order_index\')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    foreach ($homeSections as $section) {
        echo "✅ " . $section->title . " (" . $section->section . ")\n";
        echo "   Subtitle: " . $section->subtitle . "\n";
        echo "   Content: " . substr($section->content, 0, 100) . "...\n";
        echo "   Image: " . $section->image . "\n";
        echo "   Button: " . $section->button_text . " -> " . $section->button_url . "\n";
        echo "   Active: " . ($section->is_active ? \'Yes\' : \'No\') . "\n";
        echo "   Order: " . $section->order_index . "\n";
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
    
    echo "✅ All home sections data tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-home-sections-data.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-home-sections-data.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-home-sections-data.php\n";
} else {
    echo "❌ Failed to create test-home-sections-data.php\n";
}

echo "\n✅ Home sections data insertion completed!\n";
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
echo "- Home Sections Data Test: http://localhost:8000/test-home-sections-data.php\n";
echo "- Admin Home Sections: http://localhost:8000/admin/home-sections\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-home-sections-data.php\n";
echo "2. Test: http://localhost:8000/admin/home-sections\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if all home sections are displaying correctly with backgrounds\n";
echo "5. Check server logs for any remaining errors\n";

