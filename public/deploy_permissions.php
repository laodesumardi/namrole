<?php
/**
 * Deploy Images to Hosting - Run this on hosting server
 */

echo "=== DEPLOYING IMAGES TO HOSTING ===\n";

// Set proper permissions
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
        mkdir($fullPath, 0755, true);
        echo "Created: $dir\n";
    }
    chmod($fullPath, 0755);
    echo "Set permission 755: $dir\n";
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
                    mkdir($destDir, 0755, true);
                }
                
                copy($file, $destFile);
                chmod($destFile, 0644);
                echo "Copied: $relativePath\n";
            }
        }
    }
}

// Create .htaccess
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

file_put_contents(__DIR__ . "/public/storage/.htaccess", $htaccessContent);
echo "Created .htaccess for public storage\n";

echo "=== DEPLOYMENT COMPLETE ===\n";
echo "Images should now be accessible at: https://uji.odetune.shop/\n";
?>