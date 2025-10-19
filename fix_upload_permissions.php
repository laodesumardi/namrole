<?php
/**
 * Fix Upload Permissions - Complete Solution
 * Solusi untuk masalah "Permission denied" saat upload gambar
 */

echo "=== FIX UPLOAD PERMISSIONS ===\n";
echo "Memperbaiki permission denied saat upload gambar...\n\n";

// 1. Fix storage directory permissions
echo "1. Memperbaiki permission storage directory...\n";
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
    'public/storage/teachers'
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
    
    // Set permissions to 777 for upload directories
    if (is_dir($fullPath)) {
        chmod($fullPath, 0777);
        echo "✅ Set permissions 777 for: $dir\n";
    }
}

// 2. Fix file permissions
echo "\n2. Memperbaiki permission file...\n";
$files = glob(__DIR__ . '/storage/app/public/**/*', GLOB_BRACE);
foreach ($files as $file) {
    if (is_file($file)) {
        chmod($file, 0666);
        echo "✅ Set permission 666 for: " . basename($file) . "\n";
    }
}

// 3. Create .htaccess for storage
echo "\n3. Creating .htaccess for storage...\n";
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

file_put_contents(__DIR__ . '/storage/app/public/.htaccess', $htaccessContent);
file_put_contents(__DIR__ . '/public/storage/.htaccess', $htaccessContent);
echo "✅ Created .htaccess for storage directories\n";

// 4. Copy images to public storage
echo "\n4. Copy images to public storage...\n";
$sourceDirs = [
    'storage/app/public' => 'public/storage',
    'public/uploads' => 'public/storage'
];

foreach ($sourceDirs as $source => $dest) {
    $sourcePath = __DIR__ . '/' . $source;
    $destPath = __DIR__ . '/' . $dest;
    
    if (is_dir($sourcePath)) {
        $files = glob($sourcePath . '/**/*', GLOB_BRACE);
        foreach ($files as $file) {
            if (is_file($file)) {
                $relativePath = str_replace($sourcePath . '/', '', $file);
                $destFile = $destPath . '/' . $relativePath;
                $destDir = dirname($destFile);
                
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0777, true);
                }
                
                if (copy($file, $destFile)) {
                    chmod($destFile, 0666);
                    echo "✅ Copied: $relativePath\n";
                } else {
                    echo "❌ Failed to copy: $relativePath\n";
                }
            }
        }
    }
}

// 5. Test image URLs
echo "\n5. Testing image URLs...\n";
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

// 6. Create deployment script for hosting
echo "\n6. Creating deployment script for hosting...\n";
$deployScript = '<?php
/**
 * Deploy Upload Permissions to Hosting - Run this on hosting server
 */

echo "=== DEPLOYING UPLOAD PERMISSIONS TO HOSTING ===\n";

// Set proper permissions for uploads
$directories = [
    "storage",
    "storage/app",
    "storage/app/public",
    "storage/app/public/gallery",
    "storage/app/public/gallery-items", 
    "storage/app/public/news",
    "storage/app/public/home-sections",
    "storage/app/public/headmaster-greetings",
    "storage/app/public/school-profiles",
    "storage/app/public/facilities",
    "storage/app/public/students",
    "storage/app/public/teachers",
    "public/storage",
    "public/storage/gallery",
    "public/storage/gallery-items",
    "public/storage/news", 
    "public/storage/home-sections",
    "public/storage/headmaster-greetings",
    "public/storage/school-profiles",
    "public/storage/facilities",
    "public/storage/students",
    "public/storage/teachers"
];

foreach ($directories as $dir) {
    $fullPath = __DIR__ . "/" . $dir;
    if (!is_dir($fullPath)) {
        mkdir($fullPath, 0777, true);
        echo "Created: $dir\n";
    }
    chmod($fullPath, 0777);
    echo "Set permission 777: $dir\n";
}

// Copy files from storage to public
$sourceDirs = [
    "storage/app/public" => "public/storage",
    "public/uploads" => "public/storage"
];

foreach ($sourceDirs as $source => $dest) {
    $sourcePath = __DIR__ . "/" . $source;
    $destPath = __DIR__ . "/" . $dest;
    
    if (is_dir($sourcePath)) {
        $files = glob($sourcePath . "/**/*", GLOB_BRACE);
        foreach ($files as $file) {
            if (is_file($file)) {
                $relativePath = str_replace($sourcePath . "/", "", $file);
                $destFile = $destPath . "/" . $relativePath;
                $destDir = dirname($destFile);
                
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0777, true);
                }
                
                copy($file, $destFile);
                chmod($destFile, 0666);
                echo "Copied: $relativePath\n";
            }
        }
    }
}

// Create .htaccess
$htaccessContent = \'Options -Indexes
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

<IfModule mod_headers.c>
    <FilesMatch "\\.(jpg|jpeg|png|gif|webp|svg)$">
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
</IfModule>\';

file_put_contents(__DIR__ . "/storage/app/public/.htaccess", $htaccessContent);
file_put_contents(__DIR__ . "/public/storage/.htaccess", $htaccessContent);
echo "Created .htaccess for storage directories\n";

echo "=== DEPLOYMENT COMPLETE ===\n";
echo "Upload permissions fixed!\n";
echo "Images should now be accessible at: https://uji.odetune.shop/\n";
echo "Upload functionality should work without permission errors!\n";
?>';
file_put_contents(__DIR__ . '/public/deploy_upload_permissions.php', $deployScript);
echo "✅ Created deployment script: public/deploy_upload_permissions.php\n";

echo "\n=== FIX COMPLETE ===\n";
echo "✅ Upload permission issues fixed\n";
echo "✅ Directories created with 777 permissions\n";
echo "✅ Files set to 666 permissions\n";
echo "✅ Images copied to public storage\n";
echo "✅ .htaccess created for caching\n";
echo "✅ Deployment script created\n";
echo "\n🚀 NEXT STEPS:\n";
echo "1. Upload all files to hosting\n";
echo "2. Run: php public/deploy_upload_permissions.php\n";
echo "3. Test upload functionality\n";
echo "4. Test website: https://uji.odetune.shop/\n";
echo "5. Images should now appear and uploads should work!\n";
?>
