<?php
// Hosting deployment script
echo "🚀 Deploying to hosting...\n";

// 1. Clear cache
echo "📝 Clearing cache...\n";
exec("php artisan cache:clear");
exec("php artisan config:clear");
exec("php artisan route:clear");
exec("php artisan view:clear");
echo "✅ Cache cleared\n";

// 2. Create storage directory
echo "📝 Creating storage directory...\n";
$publicStoragePath = __DIR__ . "/storage";
$storageAppPublicPath = __DIR__ . "/../storage/app/public";

if (!is_dir($publicStoragePath)) {
    mkdir($publicStoragePath, 0755, true);
    echo "✅ Storage directory created\n";
} else {
    echo "✅ Storage directory exists\n";
}

// 3. Copy files
echo "📝 Copying files...\n";
$copiedCount = 0;

if (is_dir($storageAppPublicPath)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($storageAppPublicPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $file) {
        $relativePath = str_replace($storageAppPublicPath . DIRECTORY_SEPARATOR, "", $file->getPathname());
        $destPath = $publicStoragePath . DIRECTORY_SEPARATOR . $relativePath;
        
        if ($file->isDir()) {
            if (!is_dir($destPath)) {
                mkdir($destPath, 0755, true);
            }
        } else {
            $destDir = dirname($destPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            if (!file_exists($destPath)) {
                if (copy($file->getPathname(), $destPath)) {
                    $copiedCount++;
                }
            }
        }
    }
}
echo "✅ {$copiedCount} files copied\n";

// 4. Set permissions
echo "📝 Setting permissions...\n";
$permissionCount = 0;

if (is_dir($publicStoragePath)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($publicStoragePath, RecursiveDirectoryIterator::SKIP_DOTS)
    );
    
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            chmod($file->getPathname(), 0644);
            $permissionCount++;
        } elseif ($file->isDir()) {
            chmod($file->getPathname(), 0755);
        }
    }
}
echo "✅ Permissions set for {$permissionCount} files\n";

echo "🎉 Deployment completed!\n";
echo "📋 Summary:\n";
echo "   - Cache: Cleared\n";
echo "   - Storage: Ready\n";
echo "   - Files copied: {$copiedCount}\n";
echo "   - Permissions: Set\n";
echo "   - Website ready for hosting!\n";
?>