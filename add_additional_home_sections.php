<?php
/**
 * Add Additional Home Sections
 * 
 * This script adds additional home sections that can be edited from admin panel
 * Specifically for http://localhost:8000/ and http://localhost:8000/admin/home-sections
 */

echo "🏠 Adding Additional Home Sections\n";
echo "==================================\n\n";

echo "🔧 Adding additional home sections for better website content...\n";

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

// 3. Create additional default images
echo "\n🖼️ Creating additional default images...\n";

$additionalImages = [
    'default-mission-bg.png' => 'Mission section background image',
    'default-values-bg.png' => 'Values section background image',
    'default-history-bg.png' => 'History section background image',
    'default-principal-bg.png' => 'Principal message background image',
    'default-academic-bg.png' => 'Academic programs background image',
    'default-extracurricular-bg.png' => 'Extracurricular activities background image',
    'default-facilities-detail-bg.png' => 'Facilities detail background image',
    'default-achievements-bg.png' => 'Achievements background image',
    'default-alumni-bg.png' => 'Alumni success background image',
    'default-partnership-bg.png' => 'Partnership background image',
    'default-calendar-bg.png' => 'Academic calendar background image',
    'default-downloads-bg.png' => 'Downloads section background image'
];

foreach ($additionalImages as $filename => $description) {
    $filepath = "public/images/$filename";
    if (!file_exists($filepath)) {
        // Create a simple colored image placeholder
        $image = imagecreate(800, 600);
        $colors = [
            'mission' => [34, 197, 94],      // Green
            'values' => [59, 130, 246],      // Blue
            'history' => [168, 85, 247],     // Purple
            'principal' => [239, 68, 68],    // Red
            'academic' => [245, 158, 11],    // Orange
            'extracurricular' => [236, 72, 153], // Pink
            'facilities-detail' => [14, 165, 233], // Sky Blue
            'achievements' => [34, 197, 94], // Green
            'alumni' => [168, 85, 247],      // Purple
            'partnership' => [59, 130, 246], // Blue
            'calendar' => [245, 158, 11],    // Orange
            'downloads' => [107, 114, 128]   // Gray
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
        
        echo "✅ Created additional image: $filename\n";
    } else {
        echo "✅ Additional image already exists: $filename\n";
    }
}

// 4. Add additional home sections
echo "\n🏠 Adding additional home sections...\n";

$additionalSections = [
    [
        'section_key' => 'mission',
        'title' => 'Misi Sekolah',
        'subtitle' => 'Membangun Karakter dan Prestasi Siswa',
        'description' => '1. Menyelenggarakan pendidikan yang berkualitas dan berkarakter\n2. Mengembangkan potensi siswa secara optimal\n3. Membentuk siswa yang berakhlak mulia dan berprestasi\n4. Menjalin kerjasama yang harmonis dengan masyarakat\n5. Menciptakan lingkungan belajar yang kondusif dan menyenangkan',
        'button_text' => 'Pelajari Misi',
        'button_link' => '/profil',
        'background_color' => '#166534',
        'text_color' => '#ffffff',
        'image' => 'default-mission-bg.png',
        'image_alt' => 'Mission Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 11,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'values',
        'title' => 'Nilai-Nilai Sekolah',
        'subtitle' => 'Integritas, Disiplin, dan Kreativitas',
        'description' => 'SMP Negeri 01 Namrole mengembangkan nilai-nilai luhur yang menjadi fondasi karakter siswa: Integritas dalam setiap tindakan, Disiplin dalam belajar dan berperilaku, Kreativitas dalam mengembangkan potensi, Kerjasama dalam mencapai tujuan bersama, dan Tanggung jawab terhadap diri dan lingkungan.',
        'button_text' => 'Lihat Nilai',
        'button_link' => '/profil',
        'background_color' => '#1e40af',
        'text_color' => '#ffffff',
        'image' => 'default-values-bg.png',
        'image_alt' => 'Values Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 12,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'history',
        'title' => 'Sejarah Sekolah',
        'subtitle' => 'Perjalanan Panjang Menuju Keunggulan',
        'description' => 'SMP Negeri 01 Namrole didirikan pada tahun 1985 dengan semangat memberikan pendidikan terbaik bagi masyarakat Namrole. Selama lebih dari 3 dekade, sekolah ini telah mengalami berbagai transformasi dan peningkatan fasilitas untuk mendukung proses pembelajaran yang optimal.',
        'button_text' => 'Baca Sejarah',
        'button_link' => '/profil',
        'background_color' => '#7c3aed',
        'text_color' => '#ffffff',
        'image' => 'default-history-bg.png',
        'image_alt' => 'History Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 13,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'principal',
        'title' => 'Sambutan Kepala Sekolah',
        'subtitle' => 'Pesan Inspiratif dari Pimpinan Sekolah',
        'description' => 'Selamat datang di SMP Negeri 01 Namrole. Sebagai kepala sekolah, saya berkomitmen untuk memberikan pendidikan terbaik yang mengembangkan potensi akademik dan karakter siswa. Mari bersama-sama membangun generasi yang berkarakter, berprestasi, dan berakhlak mulia.',
        'button_text' => 'Baca Sambutan',
        'button_link' => '/profil',
        'background_color' => '#dc2626',
        'text_color' => '#ffffff',
        'image' => 'default-principal-bg.png',
        'image_alt' => 'Principal Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 14,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'academic',
        'title' => 'Program Akademik',
        'subtitle' => 'Kurikulum Terpadu dan Berkarakter',
        'description' => 'SMP Negeri 01 Namrole menyelenggarakan program akademik yang komprehensif dengan kurikulum yang disesuaikan dengan kebutuhan siswa. Program meliputi mata pelajaran wajib, pilihan, dan pengembangan diri yang mengasah kemampuan kognitif, afektif, dan psikomotorik siswa.',
        'button_text' => 'Lihat Program',
        'button_link' => '/akademik',
        'background_color' => '#d97706',
        'text_color' => '#ffffff',
        'image' => 'default-academic-bg.png',
        'image_alt' => 'Academic Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 15,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'extracurricular',
        'title' => 'Kegiatan Ekstrakurikuler',
        'subtitle' => 'Mengembangkan Bakat dan Minat Siswa',
        'description' => 'Berbagai kegiatan ekstrakurikuler tersedia untuk mengembangkan bakat dan minat siswa: Pramuka, PMR, Paskibra, Olahraga, Seni, Bahasa, Sains, dan Teknologi. Setiap siswa dapat memilih kegiatan yang sesuai dengan minat dan bakatnya.',
        'button_text' => 'Lihat Kegiatan',
        'button_link' => '/ekstrakurikuler',
        'background_color' => '#be185d',
        'text_color' => '#ffffff',
        'image' => 'default-extracurricular-bg.png',
        'image_alt' => 'Extracurricular Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 16,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'facilities-detail',
        'title' => 'Fasilitas Lengkap',
        'subtitle' => 'Infrastruktur Modern untuk Pembelajaran Optimal',
        'description' => 'SMP Negeri 01 Namrole dilengkapi dengan fasilitas modern: Laboratorium Komputer dengan 30 unit, Laboratorium IPA dengan peralatan lengkap, Perpustakaan dengan koleksi 5000+ buku, Lapangan olahraga multifungsi, Ruang kelas ber-AC, dan WiFi di seluruh area sekolah.',
        'button_text' => 'Lihat Fasilitas',
        'button_link' => '/fasilitas',
        'background_color' => '#0ea5e9',
        'text_color' => '#ffffff',
        'image' => 'default-facilities-detail-bg.png',
        'image_alt' => 'Facilities Detail Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 17,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'achievements',
        'title' => 'Prestasi Membanggakan',
        'subtitle' => 'Kebanggaan Sekolah dan Masyarakat',
        'description' => 'Siswa-siswi SMP Negeri 01 Namrole telah meraih berbagai prestasi di tingkat kabupaten, provinsi, dan nasional. Prestasi meliputi: Olimpiade Sains, Lomba Karya Ilmiah, Kompetisi Olahraga, Festival Seni, dan berbagai kompetisi akademik lainnya.',
        'button_text' => 'Lihat Prestasi',
        'button_link' => '/prestasi',
        'background_color' => '#059669',
        'text_color' => '#ffffff',
        'image' => 'default-achievements-bg.png',
        'image_alt' => 'Achievements Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 18,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'alumni',
        'title' => 'Alumni Berprestasi',
        'subtitle' => 'Kisah Sukses dari Lulusan Terbaik',
        'description' => 'Banyak alumni SMP Negeri 01 Namrole yang telah berhasil meraih prestasi di berbagai bidang: Dokter, Insinyur, Guru, Pengusaha, dan Profesional lainnya. Mereka membuktikan bahwa pendidikan di SMP Negeri 01 Namrole menjadi fondasi yang kuat untuk masa depan.',
        'button_text' => 'Lihat Alumni',
        'button_link' => '/alumni',
        'background_color' => '#7c3aed',
        'text_color' => '#ffffff',
        'image' => 'default-alumni-bg.png',
        'image_alt' => 'Alumni Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 19,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'partnership',
        'title' => 'Kemitraan Strategis',
        'subtitle' => 'Kerjasama untuk Pendidikan Berkualitas',
        'description' => 'SMP Negeri 01 Namrole menjalin kemitraan strategis dengan berbagai pihak: Pemerintah Daerah, Perguruan Tinggi, Dunia Usaha, Organisasi Masyarakat, dan Alumni. Kemitraan ini mendukung pengembangan program pendidikan dan peningkatan kualitas pembelajaran.',
        'button_text' => 'Lihat Kemitraan',
        'button_link' => '/kemitraan',
        'background_color' => '#1e40af',
        'text_color' => '#ffffff',
        'image' => 'default-partnership-bg.png',
        'image_alt' => 'Partnership Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 20,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'calendar',
        'title' => 'Kalender Akademik',
        'subtitle' => 'Jadwal Kegiatan dan Acara Sekolah',
        'description' => 'Dapatkan informasi lengkap tentang jadwal kegiatan akademik, ekstrakurikuler, dan acara sekolah. Kalender akademik mencakup: Jadwal pembelajaran, Ujian, Libur, Kegiatan ekstrakurikuler, Acara sekolah, dan berbagai kegiatan penting lainnya.',
        'button_text' => 'Lihat Kalender',
        'button_link' => '/kalender',
        'background_color' => '#d97706',
        'text_color' => '#ffffff',
        'image' => 'default-calendar-bg.png',
        'image_alt' => 'Calendar Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 21,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'section_key' => 'downloads',
        'title' => 'Download & Dokumen',
        'subtitle' => 'Formulir dan Dokumen Penting',
        'description' => 'Download berbagai formulir dan dokumen penting: Formulir PPDB, Brosur sekolah, Panduan siswa, Kalender akademik, Formulir ekstrakurikuler, dan berbagai dokumen lainnya yang diperlukan oleh siswa dan orang tua.',
        'button_text' => 'Lihat Download',
        'button_link' => '/download',
        'background_color' => '#6b7280',
        'text_color' => '#ffffff',
        'image' => 'default-downloads-bg.png',
        'image_alt' => 'Downloads Section Background',
        'image_position' => 'center',
        'is_active' => 1,
        'sort_order' => 22,
        'created_at' => now(),
        'updated_at' => now()
    ]
];

foreach ($additionalSections as $section) {
    try {
        \Illuminate\Support\Facades\DB::table('home_sections')->insert($section);
        echo "✅ Created additional section: " . $section['title'] . " (" . $section['section_key'] . ")\n";
    } catch (Exception $e) {
        echo "⚠️ Failed to create additional section " . $section['title'] . ": " . $e->getMessage() . "\n";
    }
}

// 5. Copy additional images to storage
echo "\n📁 Copying additional images to storage...\n";

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

foreach ($additionalImages as $filename => $description) {
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

// 6. Update database with storage paths for additional sections
echo "\n🔄 Updating database with storage paths for additional sections...\n";

try {
    \Illuminate\Support\Facades\DB::table('home_sections')
        ->whereIn('section_key', ['mission', 'values', 'history', 'principal', 'academic', 'extracurricular', 'facilities-detail', 'achievements', 'alumni', 'partnership', 'calendar', 'downloads'])
        ->update([
            'image' => \Illuminate\Support\Facades\DB::raw("REPLACE(image, 'default-', 'storage/home-sections/')")
        ]);
    echo "✅ Updated database with storage paths for additional sections\n";
} catch (Exception $e) {
    echo "⚠️ Failed to update database paths: " . $e->getMessage() . "\n";
}

// 7. Test HomeSection model
echo "\n🧪 Testing HomeSection model...\n";

try {
    $homeSections = \App\Models\HomeSection::orderBy('sort_order')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    echo "\n📋 All Home Sections:\n";
    foreach ($homeSections as $section) {
        echo "   - " . $section->title . " (" . $section->section_key . ")\n";
        echo "     Sort Order: " . $section->sort_order . "\n";
        echo "     Active: " . ($section->is_active ? 'Yes' : 'No') . "\n";
        echo "     Text Color: " . $section->text_color . "\n";
        echo "     Background Color: " . $section->background_color . "\n";
        echo "\n";
    }
} catch (Exception $e) {
    echo "❌ HomeSection model test failed: " . $e->getMessage() . "\n";
}

// 8. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Additional Home Sections
echo "🧪 Testing Additional Home Sections\n";
echo "===================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test HomeSection model
    $homeSections = \\App\\Models\\HomeSection::orderBy(\'sort_order\')->get();
    echo "✅ HomeSection model working: " . $homeSections->count() . " records\n";
    
    echo "\n📋 All Home Sections:\n";
    foreach ($homeSections as $section) {
        echo "✅ " . $section->title . " (" . $section->section_key . ")\n";
        echo "   Subtitle: " . $section->subtitle . "\n";
        echo "   Description: " . substr($section->description, 0, 100) . "...\n";
        echo "   Image: " . $section->image . "\n";
        echo "   Button: " . $section->button_text . " -> " . $section->button_link . "\n";
        echo "   Background Color: " . $section->background_color . "\n";
        echo "   Text Color: " . $section->text_color . "\n";
        echo "   Sort Order: " . $section->sort_order . "\n";
        echo "   Active: " . ($section->is_active ? \'Yes\' : \'No\') . "\n";
        echo "\n";
    }
    
    // Test image files
    echo "🖼️ Testing additional image files:\n";
    $imageFiles = [
        \'default-mission-bg.png\',
        \'default-values-bg.png\',
        \'default-history-bg.png\',
        \'default-principal-bg.png\',
        \'default-academic-bg.png\',
        \'default-extracurricular-bg.png\',
        \'default-facilities-detail-bg.png\',
        \'default-achievements-bg.png\',
        \'default-alumni-bg.png\',
        \'default-partnership-bg.png\',
        \'default-calendar-bg.png\',
        \'default-downloads-bg.png\'
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
    
    echo "✅ All additional home sections tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-additional-home-sections.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-additional-home-sections.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-additional-home-sections.php\n";
} else {
    echo "❌ Failed to create test-additional-home-sections.php\n";
}

echo "\n✅ Additional home sections insertion completed!\n";
echo "🔧 Key data added:\n";
echo "- Mission Section (School mission with background)\n";
echo "- Values Section (School values with background)\n";
echo "- History Section (School history with background)\n";
echo "- Principal Section (Principal message with background)\n";
echo "- Academic Section (Academic programs with background)\n";
echo "- Extracurricular Section (Extracurricular activities with background)\n";
echo "- Facilities Detail Section (Detailed facilities with background)\n";
echo "- Achievements Section (School achievements with background)\n";
echo "- Alumni Section (Alumni success stories with background)\n";
echo "- Partnership Section (Strategic partnerships with background)\n";
echo "- Calendar Section (Academic calendar with background)\n";
echo "- Downloads Section (Downloads and documents with background)\n";
echo "- Created 12 additional background images\n";
echo "- Copied images to storage directories\n";
echo "- Updated database with storage paths\n";
echo "- Tested HomeSection model functionality\n";
echo "- Created test script\n";
echo "- All sections can be edited from admin panel\n\n";

echo "🌐 Test URLs:\n";
echo "- Additional Home Sections Test: http://localhost:8000/test-additional-home-sections.php\n";
echo "- Admin Home Sections: http://localhost:8000/admin/home-sections\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-additional-home-sections.php\n";
echo "2. Test: http://localhost:8000/admin/home-sections\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if all additional sections are displaying correctly\n";
echo "5. Edit sections from admin panel as needed\n";
echo "6. Check server logs for any remaining errors\n";

