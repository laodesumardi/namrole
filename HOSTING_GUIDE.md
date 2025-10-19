# 🚀 Panduan Hosting Website Sekolah dengan Gambar

## 📋 Persiapan Sebelum Hosting

### 1. Fix Masalah Gambar yang Tersisa
```bash
# Jalankan script untuk memperbaiki semua masalah gambar
php fix_remaining_image_issues.php
php copy_all_uploads_to_storage.php
php final_hosting_check.php
```

### 2. Pastikan Storage Symlink Berfungsi
```bash
# Hapus symlink lama jika ada
rm -rf public/storage

# Buat symlink baru
php artisan storage:link
```

### 3. Copy Semua Gambar ke Public Storage
```bash
# Copy semua gambar dari storage ke public
cp -r storage/app/public/* public/storage/
```

## 🌐 Langkah-langkah Hosting

### A. Persiapan File Project

1. **Buat folder untuk hosting:**
```bash
mkdir website-sekolah-hosting
cd website-sekolah-hosting
```

2. **Copy semua file project:**
```bash
# Copy semua file kecuali node_modules, vendor, storage/logs
rsync -av --exclude='node_modules' --exclude='vendor' --exclude='storage/logs' --exclude='.git' /path/to/project/ ./
```

3. **Install dependencies:**
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### B. Konfigurasi Database

1. **Buat database baru di hosting**
2. **Update file `.env`:**
```env
APP_NAME="Website Sekolah"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### C. Upload ke Hosting

1. **Upload semua file ke public_html**
2. **Set permissions:**
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 644 public/storage/
```

3. **Jalankan migrasi:**
```bash
php artisan migrate --force
php artisan db:seed --force
```

### D. Konfigurasi Web Server

#### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

#### Nginx
```nginx
location / {
    try_files $uri $uri/ /public/index.php?$query_string;
}
```

## 🔧 Script untuk Memastikan Gambar Muncul

### 1. Script Final Check
```php
<?php
// final_hosting_setup.php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Final hosting setup...\n";

// 1. Ensure storage symlink
$symlinkPath = public_path('storage');
$targetPath = storage_path('app/public');

if (!is_link($symlinkPath) && !is_dir($symlinkPath)) {
    symlink($targetPath, $symlinkPath);
    echo "✅ Storage symlink created\n";
}

// 2. Copy all images
$storageDir = storage_path('app/public');
$publicStorageDir = public_path('storage');

if (is_dir($storageDir)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($storageDir));
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $relativePath = str_replace($storageDir . DIRECTORY_SEPARATOR, '', $file->getPathname());
            $destPath = $publicStorageDir . DIRECTORY_SEPARATOR . $relativePath;
            
            if (!file_exists($destPath)) {
                $destDir = dirname($destPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                copy($file->getPathname(), $destPath);
            }
        }
    }
}

echo "✅ Setup completed!\n";
?>
```

### 2. Script Test Gambar
```php
<?php
// test_images.php
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing images...\n";

$models = [
    'SchoolProfile' => \App\Models\SchoolProfile::class,
    'HomeSection' => \App\Models\HomeSection::class,
    'Gallery' => \App\Models\Gallery::class,
    'News' => \App\Models\News::class,
];

foreach ($models as $name => $model) {
    $items = $model::whereNotNull('image')->orWhereNotNull('cover_image')->orWhereNotNull('featured_image')->get();
    
    foreach ($items as $item) {
        $imageUrl = $item->image_url ?? $item->cover_image_url ?? $item->featured_image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "{$name} {$item->id}: " . (file_exists($imagePath) ? '✅' : '❌') . " {$imageUrl}\n";
    }
}
?>
```

## 📝 Checklist Hosting

### ✅ Sebelum Upload
- [ ] Storage symlink berfungsi
- [ ] Semua gambar ada di public/storage/
- [ ] Default images tersedia
- [ ] Database siap
- [ ] .env dikonfigurasi

### ✅ Setelah Upload
- [ ] Jalankan `php artisan migrate --force`
- [ ] Jalankan `php artisan db:seed --force`
- [ ] Jalankan `php artisan storage:link`
- [ ] Copy gambar ke public/storage/
- [ ] Set permissions yang benar
- [ ] Test semua halaman

### ✅ Test Gambar
- [ ] Homepage menampilkan gambar
- [ ] Gallery menampilkan gambar
- [ ] News menampilkan gambar
- [ ] Admin panel menampilkan gambar
- [ ] Tidak ada error 404 untuk gambar

## 🚨 Troubleshooting

### Gambar Tidak Muncul
1. **Check storage symlink:**
```bash
ls -la public/storage
```

2. **Check permissions:**
```bash
chmod -R 755 public/storage/
```

3. **Check file exists:**
```bash
ls -la public/storage/school-profiles/
```

### Error 500
1. **Check .env file**
2. **Check database connection**
3. **Check file permissions**

### Error 404 untuk Gambar
1. **Check storage symlink**
2. **Check file path di database**
3. **Check file exists di public/storage/**

## 🎯 Hasil Akhir

Setelah mengikuti panduan ini:
- ✅ Website akan online
- ✅ Semua gambar akan muncul
- ✅ Admin panel berfungsi
- ✅ Database terisi dengan data
- ✅ Siap untuk production

## 📞 Support

Jika ada masalah:
1. Check log error di `storage/logs/`
2. Check permissions file
3. Check database connection
4. Check storage symlink
