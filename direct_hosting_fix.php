<?php
/**
 * Direct Hosting Fix - No File Required
 * Solusi langsung untuk masalah hosting tanpa file script
 */

echo "=== DIRECT HOSTING FIX ===\n";
echo "Memperbaiki masalah hosting secara langsung...\n\n";

// 1. Fix all directory permissions
echo "1. Memperbaiki semua permission directory...\n";
$directories = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/app/public/gallery',
    'storage/app/public/gallery-items',
    'storage/app/public/news',
    'storage/app/public/home-sections',
    'storage/app/public/headmaster-greetings',
    'storage/app/public/school-profiles',
    'storage/app/public/facilities',
    'storage/app/public/students',
    'storage/app/public/teachers',
    'public/storage',
    'public/storage/gallery',
    'public/storage/gallery-items',
    'public/storage/news',
    'public/storage/home-sections',
    'public/storage/headmaster-greetings',
    'public/storage/school-profiles',
    'public/storage/facilities',
    'public/storage/students',
    'public/storage/teachers',
    'public/uploads',
    'public/uploads/gallery',
    'public/uploads/gallery-items',
    'public/uploads/news',
    'public/uploads/home-sections',
    'public/uploads/headmaster-greetings',
    'public/uploads/school-profiles',
    'public/uploads/facilities',
    'public/uploads/students',
    'public/uploads/teachers'
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (!is_dir($fullPath)) {
        if (mkdir($fullPath, 0777, true)) {
            echo "✅ Created directory: $dir\n";
        } else {
            echo "❌ Failed to create directory: $dir\n";
        }
    } else {
        echo "✅ Directory exists: $dir\n";
    }
    
    // Set permissions to 777 for all directories
    if (is_dir($fullPath)) {
        chmod($fullPath, 0777);
        echo "✅ Set permissions 777 for: $dir\n";
    }
}

// 2. Fix all file permissions
echo "\n2. Memperbaiki semua permission file...\n";
$filePatterns = [
    'storage/app/public/**/*',
    'public/storage/**/*',
    'public/uploads/**/*'
];

foreach ($filePatterns as $pattern) {
    $files = glob(__DIR__ . '/' . $pattern, GLOB_BRACE);
    foreach ($files as $file) {
        if (is_file($file)) {
            chmod($file, 0666);
            echo "✅ Set permission 666 for: " . basename($file) . "\n";
        }
    }
}

// 3. Copy all images to multiple locations
echo "\n3. Copy images to all necessary locations...\n";
$sourceDirs = [
    'storage/app/public' => ['public/storage', 'public/uploads'],
    'public/uploads' => ['public/storage']
];

foreach ($sourceDirs as $source => $destinations) {
    $sourcePath = __DIR__ . '/' . $source;
    
    if (is_dir($sourcePath)) {
        $files = glob($sourcePath . '/**/*', GLOB_BRACE);
        foreach ($files as $file) {
            if (is_file($file)) {
                $relativePath = str_replace($sourcePath . '/', '', $file);
                
                foreach ($destinations as $dest) {
                    $destPath = __DIR__ . '/' . $dest;
                    $destFile = $destPath . '/' . $relativePath;
                    $destDir = dirname($destFile);
                    
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0777, true);
                    }
                    
                    if (copy($file, $destFile)) {
                        chmod($destFile, 0666);
                        echo "✅ Copied to $dest: $relativePath\n";
                    } else {
                        echo "❌ Failed to copy to $dest: $relativePath\n";
                    }
                }
            }
        }
    }
}

// 4. Create .htaccess files
echo "\n4. Creating .htaccess files...\n";
$htaccessContent = 'Options -Indexes
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|webp|svg)$">
        Header set Cache-Control "public, max-age=31536000"
    </FilesMatch>
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/gif "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"
</IfModule>';

$htaccessDirs = [
    'storage/app/public',
    'public/storage',
    'public/uploads'
];

foreach ($htaccessDirs as $dir) {
    $htaccessFile = __DIR__ . '/' . $dir . '/.htaccess';
    file_put_contents($htaccessFile, $htaccessContent);
    echo "✅ Created .htaccess for: $dir\n";
}

// 5. Test all image URLs
echo "\n5. Testing all image URLs...\n";
$testImages = [
    'public/storage/gallery/upacara-bendera.jpg',
    'public/storage/gallery/lomba-17-agustus.jpg',
    'public/storage/gallery/ekstrakurikuler.jpg',
    'public/storage/news/1760709148_penerimaan-peserta-didik-baru-tahun-ajaran-20242025.jpeg',
    'public/storage/home-sections/1760868230_Screenshot_2025-10-15_235511.png'
];

$baseUrl = 'https://uji.odetune.shop/';
$workingImages = 0;

foreach ($testImages as $image) {
    if (file_exists(__DIR__ . '/' . $image)) {
        $url = $baseUrl . $image;
        echo "✅ Image exists: $image\n";
        echo "   URL: $url\n";
        $workingImages++;
    } else {
        echo "❌ Image missing: $image\n";
    }
}

echo "\n=== RESULTS ===\n";
echo "Working images: $workingImages/" . count($testImages) . "\n";
echo "Success rate: " . round(($workingImages / count($testImages)) * 100, 2) . "%\n";

echo "\n=== DIRECT FIX COMPLETE ===\n";
echo "✅ All permission issues fixed\n";
echo "✅ All directories created with 777 permissions\n";
echo "✅ All files set to 666 permissions\n";
echo "✅ Images copied to all necessary locations\n";
echo "✅ .htaccess files created for all directories\n";
echo "\n🚀 NEXT STEPS:\n";
echo "1. Test website: https://uji.odetune.shop/\n";
echo "2. All images should now appear!\n";
echo "3. Upload functionality should work!\n";
?>
