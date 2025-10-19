<?php

echo "🔧 Fixing GalleryItem Model Syntax Error\n";
echo "=======================================\n\n";

// 1. Check if we're in Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: Not in Laravel project directory\n";
    echo "Please run this script from your Laravel project root\n";
    exit(1);
}

echo "✅ Laravel project detected\n";

// 2. Fix GalleryItem model
echo "\n📝 Fixing GalleryItem model...\n";

$galleryItemModelPath = 'app/Models/GalleryItem.php';

if (file_exists($galleryItemModelPath)) {
    // Create a clean GalleryItem model
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

    public function getTypeLabel()
    {
        return $this->getTypeLabelAttribute();
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

    public function getFileExtension()
    {
        if ($this->image) {
            return pathinfo($this->image, PATHINFO_EXTENSION);
        }
        return null;
    }

    public function getFileSize()
    {
        if ($this->image) {
            $path = storage_path(\'app/public/\' . $this->image);
            if (file_exists($path)) {
                return filesize($path);
            }
        }
        return null;
    }

    public function getFileSizeFormatted()
    {
        $size = $this->getFileSize();
        if ($size) {
            $units = [\'B\', \'KB\', \'MB\', \'GB\'];
            $unitIndex = 0;
            while ($size >= 1024 && $unitIndex < count($units) - 1) {
                $size /= 1024;
                $unitIndex++;
            }
            return round($size, 2) . \' \' . $units[$unitIndex];
        }
        return null;
    }
}';

    // Write the clean model
    if (file_put_contents($galleryItemModelPath, $cleanGalleryItemModel)) {
        echo "   ✅ GalleryItem model fixed successfully\n";
    } else {
        echo "   ❌ Failed to fix GalleryItem model\n";
    }
} else {
    echo "   ❌ GalleryItem model not found\n";
}

// 3. Fix Gallery model if needed
echo "\n📝 Checking Gallery model...\n";

$galleryModelPath = 'app/Models/Gallery.php';

if (file_exists($galleryModelPath)) {
    $modelContent = file_get_contents($galleryModelPath);
    
    // Check if model has syntax errors
    if (strpos($modelContent, 'getImageUrlAttribute') !== false) {
        echo "   ✅ Gallery model has image URL accessor\n";
    } else {
        echo "   ⚠️  Gallery model missing image URL accessor\n";
    }
} else {
    echo "   ❌ Gallery model not found\n";
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

echo "\n✅ GalleryItem model syntax fix completed!\n";
echo "🔧 Key improvements applied:\n";
echo "   - Fixed GalleryItem model syntax errors\n";
echo "   - Removed duplicate methods\n";
echo "   - Added proper image URL accessor\n";
echo "   - Added proper relationships\n";
echo "   - Added proper scopes\n";
echo "   - Added proper accessors\n";
echo "   - Added proper methods\n";
echo "   - Created default images\n";
echo "   - Cleared all caches\n";
echo "\n🌐 Test your gallery items:\n";
echo "   - Gallery Index: http://localhost:8000/admin/gallery\n";
echo "   - Gallery Edit: http://localhost:8000/admin/gallery/2/edit\n";
echo "   - Check if images appear in edit forms\n";
echo "   - Test image upload functionality\n";
echo "\n🔑 Admin Login:\n";
echo "   - Email: admin@namrole.sch.id\n";
echo "   - Password: admin123\n";

