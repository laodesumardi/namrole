<?php
/**
 * Final Hosting Deployment - Run this on hosting server
 */

echo "=== FINAL HOSTING DEPLOYMENT ===\n";

// Set all directory permissions
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
    "public/storage/teachers",
    "public/uploads",
    "public/uploads/gallery",
    "public/uploads/news",
    "public/uploads/home-sections",
    "public/uploads/headmaster-greetings",
    "public/uploads/school-profiles",
    "public/uploads/facilities",
    "public/uploads/students",
    "public/uploads/teachers"
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

// Copy all files to multiple locations
$sourceDirs = [
    "storage/app/public" => ["public/storage", "public/uploads"],
    "public/uploads" => ["public/storage"]
];

foreach ($sourceDirs as $source => $destinations) {
    $sourcePath = __DIR__ . "/" . $source;
    
    if (is_dir($sourcePath)) {
        $files = glob($sourcePath . "/**/*", GLOB_BRACE);
        foreach ($files as $file) {
            if (is_file($file)) {
                $relativePath = str_replace($sourcePath . "/", "", $file);
                
                foreach ($destinations as $dest) {
                    $destPath = __DIR__ . "/" . $dest;
                    $destFile = $destPath . "/" . $relativePath;
                    $destDir = dirname($destFile);
                    
                    if (!is_dir($destDir)) {
                        mkdir($destDir, 0777, true);
                    }
                    
                    copy($file, $destFile);
                    chmod($destFile, 0666);
                    echo "Copied to $dest: $relativePath\n";
                }
            }
        }
    }
}

// Create .htaccess files
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
    "storage/app/public",
    "public/storage",
    "public/uploads"
];

foreach ($htaccessDirs as $dir) {
    $htaccessFile = __DIR__ . "/" . $dir . "/.htaccess";
    file_put_contents($htaccessFile, $htaccessContent);
    echo "Created .htaccess for: $dir\n";
}

echo "=== FINAL DEPLOYMENT COMPLETE ===\n";
echo "All images should now be accessible!\n";
echo "Upload functionality should work without errors!\n";
echo "Website: https://uji.odetune.shop/\n";
?>