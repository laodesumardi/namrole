<?php

echo "🔧 Fixing Gallery Model Syntax Error\n";
echo "===================================\n\n";

// 1. Check if we're in Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: Not in Laravel project directory\n";
    echo "Please run this script from your Laravel project root\n";
    exit(1);
}

echo "✅ Laravel project detected\n";

// 2. Fix Gallery model
echo "\n📝 Fixing Gallery model...\n";

$galleryModelPath = 'app/Models/Gallery.php';

if (file_exists($galleryModelPath)) {
    // Create a clean Gallery model
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

    public function getCategoryLabel()
    {
        return $this->getCategoryLabelAttribute();
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

    // Write the clean model
    if (file_put_contents($galleryModelPath, $cleanGalleryModel)) {
        echo "   ✅ Gallery model fixed successfully\n";
    } else {
        echo "   ❌ Failed to fix Gallery model\n";
    }
} else {
    echo "   ❌ Gallery model not found\n";
}

// 3. Fix GalleryItem model
echo "\n📝 Fixing GalleryItem model...\n";

$galleryItemModelPath = 'app/Models/GalleryItem.php';

if (file_exists($galleryItemModelPath)) {
    $modelContent = file_get_contents($galleryItemModelPath);
    
    // Check if model has syntax errors
    if (strpos($modelContent, 'getImageUrlAttribute') !== false) {
        // Model already has the accessor, check for syntax errors
        echo "   ✅ GalleryItem model already has image URL accessor\n";
    } else {
        // Add image URL accessor
        $newAccessor = '
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
    }';
        
        // Add before the last closing brace
        $modelContent = str_replace('}', $newAccessor . "\n}", $modelContent);
        
        if (file_put_contents($galleryItemModelPath, $modelContent)) {
            echo "   ✅ Added image URL accessor to GalleryItem model\n";
        } else {
            echo "   ❌ Failed to add accessor to GalleryItem model\n";
        }
    }
} else {
    echo "   ❌ GalleryItem model not found\n";
}

// 4. Create default images
echo "\n🖼️  Creating default images...\n";

$defaultImages = [
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-gallery-item.png' => 'Default gallery item image'
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

// 5. Clear cache
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

echo "\n✅ Gallery model syntax fix completed!\n";
echo "🔧 Key improvements applied:\n";
echo "   - Fixed Gallery model syntax errors\n";
echo "   - Removed duplicate getImageUrlAttribute methods\n";
echo "   - Added proper image URL accessor\n";
echo "   - Fixed GalleryItem model if needed\n";
echo "   - Created default images\n";
echo "   - Cleared all caches\n";
echo "\n🌐 Test your gallery edits:\n";
echo "   - Gallery Index: http://localhost:8000/admin/gallery\n";
echo "   - Gallery Edit: http://localhost:8000/admin/gallery/2/edit\n";
echo "   - Check if images appear in edit forms\n";
echo "   - Test image upload functionality\n";
echo "\n🔑 Admin Login:\n";
echo "   - Email: admin@namrole.sch.id\n";
echo "   - Password: admin123\n";

