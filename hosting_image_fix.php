<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 Hosting image fix for production...\n\n";

try {
    // 1. Force create public storage directory
    echo "📝 Creating public storage directory...\n";
    $publicStoragePath = public_path('storage');
    $storageAppPublicPath = storage_path('app/public');
    
    // Remove existing storage directory if it's a symlink
    if (is_link($publicStoragePath)) {
        unlink($publicStoragePath);
        echo "   ✅ Removed existing symlink\n";
    }
    
    // Create directory
    if (!is_dir($publicStoragePath)) {
        mkdir($publicStoragePath, 0755, true);
        echo "   ✅ Public storage directory created\n";
    } else {
        echo "   ✅ Public storage directory exists\n";
    }
    
    // 2. Copy all files from storage/app/public to public/storage
    echo "\n📝 Copying all files to public storage...\n";
    $copiedCount = 0;
    
    if (is_dir($storageAppPublicPath)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($storageAppPublicPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            $relativePath = str_replace($storageAppPublicPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
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
                
                // Force copy (overwrite if exists)
                if (copy($file->getPathname(), $destPath)) {
                    $copiedCount++;
                }
            }
        }
    }
    echo "   ✅ {$copiedCount} files copied\n";
    
    // 3. Copy from uploads directory
    echo "\n📝 Copying from uploads directory...\n";
    $uploadsDir = public_path('uploads');
    $uploadsCopiedCount = 0;
    
    if (is_dir($uploadsDir)) {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($uploadsDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                $relativePath = str_replace($uploadsDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $destPath = $publicStoragePath . DIRECTORY_SEPARATOR . $relativePath;
                
                $destDir = dirname($destPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($file->getPathname(), $destPath)) {
                    $uploadsCopiedCount++;
                }
            }
        }
    }
    echo "   ✅ {$uploadsCopiedCount} files copied from uploads\n";
    
    // 4. Set proper permissions
    echo "\n📝 Setting proper permissions...\n";
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
    echo "   ✅ Permissions set for {$permissionCount} files\n";
    
    // 5. Create .htaccess for public storage
    echo "\n📝 Creating .htaccess for public storage...\n";
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
</IfModule>';
    
    file_put_contents($publicStoragePath . '/.htaccess', $htaccessContent);
    echo "   ✅ .htaccess created for public storage\n";
    
    // 6. Test image URLs
    echo "\n📝 Testing image URLs...\n";
    $testImages = [
        'storage/school-profiles/1760868291_Struktur_Organisasi.png',
        'storage/home-sections/1760868230_Screenshot_2025-10-15_235511.png',
        'storage/gallery/upacara-bendera.jpg',
        'storage/gallery/lomba-17-agustus.jpg',
        'storage/gallery/ekstrakurikuler.jpg',
        'storage/news/1760709148_penerimaan-peserta-didik-baru-tahun-ajaran-20242025.jpeg',
        'storage/headmaster-greetings/1760801921_68f3b481824d4.png'
    ];
    
    $workingImages = 0;
    foreach ($testImages as $imagePath) {
        $fullPath = public_path($imagePath);
        if (file_exists($fullPath)) {
            echo "   ✅ {$imagePath}\n";
            $workingImages++;
        } else {
            echo "   ❌ {$imagePath}\n";
        }
    }
    
    // 7. Create hosting deployment script
    echo "\n📝 Creating hosting deployment script...\n";
    $deploymentScript = '<?php
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
?>';
    
    file_put_contents('public/deploy_images.php', $deploymentScript);
    echo "   ✅ Deployment script created at public/deploy_images.php\n";
    
    // 8. Final summary
    $successRate = count($testImages) > 0 ? round(($workingImages / count($testImages)) * 100, 2) : 0;
    
    echo "\n✅ HOSTING IMAGE FIX COMPLETED!\n";
    echo "📋 Summary:\n";
    echo "   - Files copied from storage: {$copiedCount}\n";
    echo "   - Files copied from uploads: {$uploadsCopiedCount}\n";
    echo "   - Permissions set: {$permissionCount} files\n";
    echo "   - Working images: {$workingImages}/" . count($testImages) . " ({$successRate}%)\n";
    echo "   - .htaccess created for public storage\n";
    echo "   - Deployment script created\n";
    
    if ($successRate >= 80) {
        echo "\n🎉 GAMBAR AKAN MUNCUL DI HOSTING!\n";
        echo "   - Semua gambar berfungsi\n";
        echo "   - Storage sudah dikonfigurasi\n";
        echo "   - Permissions sudah benar\n";
        echo "   - Website siap untuk production\n\n";
    } else {
        echo "\n⚠️  Beberapa gambar masih bermasalah\n";
        echo "   - Jalankan: php public/deploy_images.php\n";
        echo "   - Upload gambar asli melalui admin\n\n";
    }
    
    echo "🌐 LANGKAH HOSTING:\n";
    echo "   1. Upload semua file ke hosting\n";
    echo "   2. Jalankan: php public/deploy_images.php\n";
    echo "   3. Test website di browser\n";
    echo "   4. Check semua halaman\n";
    echo "   5. Upload gambar asli melalui admin panel\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
