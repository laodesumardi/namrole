<?php
// Hosting deployment script
echo "🚀 Deploying to hosting...\n";

// 1. Create public storage directory
$publicStoragePath = __DIR__ . "/storage";
$storageAppPublicPath = __DIR__ . "/../storage/app/public";

if (is_link($publicStoragePath)) {
    unlink($publicStoragePath);
}

if (!is_dir($publicStoragePath)) {
    mkdir($publicStoragePath, 0755, true);
    echo "✅ Storage directory created\n";
}

// 2. Copy files
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
            if (copy($file->getPathname(), $destPath)) {
                $copiedCount++;
            }
        }
    }
}
echo "✅ {$copiedCount} files copied\n";

// 3. Set permissions
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
?>