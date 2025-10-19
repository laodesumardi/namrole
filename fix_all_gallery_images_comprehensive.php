<?php

echo "🔧 Comprehensive Gallery Images Fix\n";
echo "==================================\n\n";

// 1. Check if we're in Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: Not in Laravel project directory\n";
    echo "Please run this script from your Laravel project root\n";
    exit(1);
}

echo "✅ Laravel project detected\n";

// 2. Bootstrap Laravel
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Error bootstrapping Laravel: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Create storage link manually
echo "\n🔗 Creating storage link manually...\n";

$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

// Remove existing link if it exists
if (is_link($storageLink)) {
    unlink($storageLink);
    echo "   🔧 Removed existing storage link\n";
}

// Create manual storage directory
if (!is_dir($storageLink)) {
    mkdir($storageLink, 0755, true);
    echo "   ✅ Created public/storage directory\n";
}

// Copy all files from storage/app/public to public/storage
if (is_dir('storage/app/public')) {
    copyDirectory('storage/app/public', 'public/storage');
    echo "   ✅ Copied all storage files to public storage\n";
}

// 4. Fix Gallery model completely
echo "\n📝 Fixing Gallery model completely...\n";

$galleryModelPath = 'app/Models/Gallery.php';
$cleanGalleryModel = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $fillable = [
        \'title\',
        \'slug\',
        \'description\',
        \'image\',
        \'cover_image\',
        \'type\',
        \'category\',
        \'status\',
        \'is_featured\',
        \'is_public\',
        \'is_active\',
        \'sort_order\',
        \'metadata\'
    ];

    protected $casts = [
        \'is_featured\' => \'boolean\',
        \'is_public\' => \'boolean\',
        \'is_active\' => \'boolean\',
        \'metadata\' => \'array\'
    ];

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($gallery) {
            if (empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title);
            }
        });
        
        static::updating(function ($gallery) {
            if ($gallery->isDirty(\'title\') && empty($gallery->slug)) {
                $gallery->slug = Str::slug($gallery->title);
            }
        });
    }

    // Relationships
    public function items()
    {
        return $this->hasMany(GalleryItem::class)->orderBy(\'sort_order\');
    }

    public function featuredItems()
    {
        return $this->hasMany(GalleryItem::class)->where(\'is_featured\', true)->orderBy(\'sort_order\');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where(\'status\', \'published\')->where(\'is_public\', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where(\'is_featured\', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where(\'category\', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where(\'type\', $type);
    }

    // Accessors
    public function getCoverImageUrlAttribute()
    {
        if ($this->cover_image) {
            return asset(\'storage/\' . str_replace(\'public/\', \'\', $this->cover_image));
        }
        return asset(\'images/default-gallery.jpg\');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-gallery.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'gallery/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        if (str_starts_with($this->image, \'storage/\')) {
            return asset($this->image);
        }
        
        if (!str_starts_with($this->image, \'gallery/\') && 
            !str_starts_with($this->image, \'storage/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        return asset(\'images/default-gallery.png\');
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            \'photo\' => \'Foto\',
            \'video\' => \'Video\',
            \'mixed\' => \'Campuran\'
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            \'kegiatan\' => \'Kegiatan Siswa\',
            \'event\' => \'Event Besar\',
            \'profil\' => \'Profil Sekolah\',
            \'testimoni\' => \'Testimoni\',
            \'prestasi\' => \'Prestasi\',
            \'fasilitas\' => \'Fasilitas\',
            \'lainnya\' => \'Lainnya\'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            \'draft\' => \'Draft\',
            \'published\' => \'Dipublikasikan\',
            \'archived\' => \'Diarsipkan\'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    // Methods
    public function getItemCount()
    {
        return $this->items()->count();
    }

    public function getFeaturedItemCount()
    {
        return $this->featuredItems()->count();
    }

    public function isPublished()
    {
        return $this->status === \'published\' && $this->is_public;
    }
}';

if (file_put_contents($galleryModelPath, $cleanGalleryModel)) {
    echo "   ✅ Gallery model fixed successfully\n";
} else {
    echo "   ❌ Failed to fix Gallery model\n";
}

// 5. Fix GalleryItem model completely
echo "\n📝 Fixing GalleryItem model completely...\n";

$galleryItemModelPath = 'app/Models/GalleryItem.php';
$cleanGalleryItemModel = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = [
        \'gallery_id\',
        \'title\',
        \'description\',
        \'image\',
        \'video_url\',
        \'type\',
        \'is_featured\',
        \'is_active\',
        \'sort_order\',
        \'metadata\'
    ];

    protected $casts = [
        \'is_featured\' => \'boolean\',
        \'is_active\' => \'boolean\',
        \'metadata\' => \'array\'
    ];

    // Relationships
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where(\'is_active\', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where(\'is_featured\', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where(\'type\', $type);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-gallery-item.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'gallery-items/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        if (str_starts_with($this->image, \'storage/\')) {
            return asset($this->image);
        }
        
        if (!str_starts_with($this->image, \'gallery-items/\') && 
            !str_starts_with($this->image, \'storage/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        return asset(\'images/default-gallery-item.png\');
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            \'image\' => \'Gambar\',
            \'video\' => \'Video\',
            \'document\' => \'Dokumen\'
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    // Methods
    public function isImage()
    {
        return $this->type === \'image\';
    }

    public function isVideo()
    {
        return $this->type === \'video\';
    }

    public function isDocument()
    {
        return $this->type === \'document\';
    }
}';

if (file_put_contents($galleryItemModelPath, $cleanGalleryItemModel)) {
    echo "   ✅ GalleryItem model fixed successfully\n";
} else {
    echo "   ❌ Failed to fix GalleryItem model\n";
}

// 6. Fix all gallery views
echo "\n📝 Fixing all gallery views...\n";

$galleryViews = [
    'resources/views/admin/gallery/index.blade.php',
    'resources/views/admin/gallery/edit.blade.php',
    'resources/views/admin/gallery/create.blade.php',
    'resources/views/admin/gallery/show.blade.php'
];

foreach ($galleryViews as $viewPath) {
    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);
        
        // Replace all image references with proper URL generation
        $viewContent = str_replace('{{ $gallery->image }}', '{{ $gallery->image_url }}', $viewContent);
        $viewContent = str_replace('{{ $gallery->cover_image }}', '{{ $gallery->cover_image_url }}', $viewContent);
        
        // Add onerror fallback to all img tags
        $imageTagPattern = '/<img([^>]*?)src="([^"]*?)"([^>]*?)>/';
        $viewContent = preg_replace_callback($imageTagPattern, function($matches) {
            $fullTag = $matches[0];
            if (strpos($fullTag, 'onerror') === false) {
                return str_replace('>', ' onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'">', $fullTag);
            }
            return $fullTag;
        }, $viewContent);
        
        if (file_put_contents($viewPath, $viewContent)) {
            echo "   ✅ Fixed view: " . basename($viewPath) . "\n";
        } else {
            echo "   ❌ Failed to fix view: " . basename($viewPath) . "\n";
        }
    } else {
        echo "   ⚠️  View not found: " . basename($viewPath) . "\n";
    }
}

// 7. Fix all gallery items views
echo "\n📝 Fixing all gallery items views...\n";

$galleryItemViews = [
    'resources/views/admin/gallery-items/index.blade.php',
    'resources/views/admin/gallery-items/edit.blade.php',
    'resources/views/admin/gallery-items/create.blade.php',
    'resources/views/admin/gallery-items/show.blade.php'
];

foreach ($galleryItemViews as $viewPath) {
    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);
        
        // Replace all image references with proper URL generation
        $viewContent = str_replace('{{ $item->image }}', '{{ $item->image_url }}', $viewContent);
        
        // Add onerror fallback to all img tags
        $imageTagPattern = '/<img([^>]*?)src="([^"]*?)"([^>]*?)>/';
        $viewContent = preg_replace_callback($imageTagPattern, function($matches) {
            $fullTag = $matches[0];
            if (strpos($fullTag, 'onerror') === false) {
                return str_replace('>', ' onerror="this.src=\'{{ asset(\'images/default-gallery-item.png\') }}\'">', $fullTag);
            }
            return $fullTag;
        }, $viewContent);
        
        if (file_put_contents($viewPath, $viewContent)) {
            echo "   ✅ Fixed view: " . basename($viewPath) . "\n";
        } else {
            echo "   ❌ Failed to fix view: " . basename($viewPath) . "\n";
        }
    } else {
        echo "   ⚠️  View not found: " . basename($viewPath) . "\n";
    }
}

// 8. Fix database paths
echo "\n📊 Fixing database paths...\n";

try {
    // Fix gallery image paths
    $galleries = DB::table('galleries')->get();
    echo "   📊 Found " . count($galleries) . " galleries\n";
    
    $fixedGalleries = 0;
    foreach ($galleries as $gallery) {
        if ($gallery->image) {
            $cleanPath = $gallery->image;
            
            // Remove 'storage/' prefix if exists
            if (strpos($cleanPath, 'storage/') === 0) {
                $cleanPath = substr($cleanPath, 8);
                DB::table('galleries')
                    ->where('id', $gallery->id)
                    ->update(['image' => $cleanPath]);
                echo "   🔧 Fixed gallery ID {$gallery->id}: {$cleanPath}\n";
                $fixedGalleries++;
            }
        }
    }
    
    // Fix gallery item image paths
    $galleryItems = DB::table('gallery_items')->get();
    echo "   📊 Found " . count($galleryItems) . " gallery items\n";
    
    $fixedItems = 0;
    foreach ($galleryItems as $item) {
        if ($item->image) {
            $cleanPath = $item->image;
            
            // Remove 'storage/' prefix if exists
            if (strpos($cleanPath, 'storage/') === 0) {
                $cleanPath = substr($cleanPath, 8);
                DB::table('gallery_items')
                    ->where('id', $item->id)
                    ->update(['image' => $cleanPath]);
                echo "   🔧 Fixed item ID {$item->id}: {$cleanPath}\n";
                $fixedItems++;
            }
        }
    }
    
    echo "\n   📊 Database Fix Summary:\n";
    echo "   - Galleries fixed: {$fixedGalleries}\n";
    echo "   - Gallery items fixed: {$fixedItems}\n";
    
} catch (Exception $e) {
    echo "   ❌ Error fixing database paths: " . $e->getMessage() . "\n";
}

// 9. Create default images
echo "\n🖼️  Creating default images...\n";

$defaultImages = [
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-gallery-item.png' => 'Default gallery item image',
    'public/images/default-gallery.jpg' => 'Default gallery cover image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        echo "   🔧 Creating {$description}...\n";
        
        // Create a simple default image (300x200 PNG)
        $image = imagecreate(300, 200);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 100, 100, 100);
        $borderColor = imagecolorallocate($image, 200, 200, 200);
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Add border
        imagerectangle($image, 0, 0, 299, 199, $borderColor);
        
        // Add text
        $text = 'Default Image';
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = (300 - $textWidth) / 2;
        $y = (200 - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $textColor);
        
        if (imagepng($image, $path)) {
            echo "   ✅ Created: {$path}\n";
        } else {
            echo "   ❌ Failed to create: {$path}\n";
        }
        
        imagedestroy($image);
    } else {
        echo "   ✅ Already exists: {$path}\n";
    }
}

// 10. Clear cache
echo "\n🧹 Clearing cache...\n";

try {
    // Clear config cache
    if (file_exists('bootstrap/cache/config.php')) {
        unlink('bootstrap/cache/config.php');
        echo "   ✅ Config cache cleared\n";
    }
    
    // Clear route cache
    if (file_exists('bootstrap/cache/routes.php')) {
        unlink('bootstrap/cache/routes.php');
        echo "   ✅ Route cache cleared\n";
    }
    
    // Clear view cache
    $viewCachePath = 'storage/framework/views';
    if (is_dir($viewCachePath)) {
        $files = glob($viewCachePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "   ✅ View cache cleared\n";
    }
    
    echo "   ✅ All caches cleared\n";
} catch (Exception $e) {
    echo "   ❌ Error clearing cache: " . $e->getMessage() . "\n";
}

echo "\n✅ Comprehensive gallery images fix completed!\n";
echo "🔧 Key improvements applied:\n";
echo "   - Created manual storage link\n";
echo "   - Fixed Gallery model completely\n";
echo "   - Fixed GalleryItem model completely\n";
echo "   - Fixed all gallery views\n";
echo "   - Fixed all gallery items views\n";
echo "   - Fixed database paths\n";
echo "   - Created default images\n";
echo "   - Cleared all caches\n";
echo "\n🌐 Test your gallery edits and updates:\n";
echo "   - Gallery Index: http://localhost:8000/admin/gallery\n";
echo "   - Gallery Edit: http://localhost:8000/admin/gallery/2/edit\n";
echo "   - Gallery Create: http://localhost:8000/admin/gallery/create\n";
echo "   - Check if images appear in all forms\n";
echo "   - Test image upload functionality\n";
echo "\n🔑 Admin Login:\n";
echo "   - Email: admin@namrole.sch.id\n";
echo "   - Password: admin123\n";

// Helper function to copy directory recursively
function copyDirectory($source, $destination) {
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        $target = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        
        if ($item->isDir()) {
            if (!is_dir($target)) {
                mkdir($target, 0755, true);
            }
        } else {
            copy($item, $target);
        }
    }
}

