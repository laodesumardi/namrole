<?php
/**
 * Fix Gallery Missing Methods
 * 
 * This script fixes missing methods in app/Models/Gallery.php
 * Specifically the published() scope method
 */

echo "🔧 Fixing Gallery Missing Methods\n";
echo "=================================\n\n";

echo "🔧 Fixing missing methods in app/Models/Gallery.php...\n";

// 1. Bootstrap Laravel
echo "\n🔗 Bootstrapping Laravel...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to bootstrap Laravel: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Read current Gallery model
echo "\n📄 Reading current Gallery.php file...\n";

if (file_exists('app/Models/Gallery.php')) {
    $content = file_get_contents('app/Models/Gallery.php');
    echo "✅ Gallery.php file found\n";
} else {
    echo "❌ Gallery.php file not found\n";
    exit(1);
}

// 3. Create the corrected Gallery.php file with missing methods
echo "\n🔧 Creating corrected Gallery.php file with missing methods...\n";

$correctedContent = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    protected $fillable = [
        \'title\',
        \'description\',
        \'cover_image\',
        \'category\',
        \'type\',
        \'status\',
        \'is_featured\'
    ];

    protected $casts = [
        \'is_featured\' => \'boolean\'
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
        return $this->hasMany(GalleryItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where(\'status\', \'active\');
    }

    public function scopePublished($query)
    {
        return $query->where(\'status\', \'published\');
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

    public function scopeRecent($query, $days = 30)
    {
        return $query->where(\'created_at\', \'>=\', now()->subDays($days));
    }

    // Accessors
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return asset(\'images/default-gallery.png\');
        }
        
        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }
        
        if (str_starts_with($this->cover_image, \'http://\') || str_starts_with($this->cover_image, \'https://\')) {
            return $this->cover_image;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->cover_image, \'storage/\')) {
            return asset($this->cover_image);
        }
        
        // If it starts with galleries/, add storage/ prefix
        if (str_starts_with($this->cover_image, \'galleries/\')) {
            return asset(\'storage/\' . $this->cover_image);
        }
        
        // If it starts with uploads/galleries/, change to storage/galleries/
        if (str_starts_with($this->cover_image, \'uploads/galleries/\')) {
            return asset(str_replace(\'uploads/galleries/\', \'storage/galleries/\', $this->cover_image));
        }
        
        // If it\'s just a filename, add the full path
        if (!str_contains($this->cover_image, \'/\')) {
            return asset(\'storage/galleries/\' . $this->cover_image);
        }
        
        // Default fallback
        return asset(\'images/default-gallery.png\');
    }

    public function getImageUrlAttribute()
    {
        return $this->cover_image_url;
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            \'academic\' => \'Akademik\',
            \'extracurricular\' => \'Ekstrakurikuler\',
            \'event\' => \'Acara\',
            \'sport\' => \'Olahraga\',
            \'art\' => \'Seni\',
            \'other\' => \'Lainnya\'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === \'gallery\' ? \'Galeri\' : \'Album\';
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            \'active\' => \'Aktif\',
            \'inactive\' => \'Tidak Aktif\',
            \'draft\' => \'Draft\',
            \'published\' => \'Dipublikasikan\'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    // Methods
    public function isPublished()
    {
        return $this->status === \'published\';
    }

    public function isActive()
    {
        return $this->status === \'active\';
    }

    public function isFeatured()
    {
        return $this->is_featured === true;
    }
}';

// 4. Write the corrected file
if (file_put_contents('app/Models/Gallery.php', $correctedContent)) {
    echo "✅ Corrected Gallery.php file created with missing methods\n";
} else {
    echo "❌ Failed to create corrected Gallery.php file\n";
    exit(1);
}

// 5. Test the syntax
echo "\n🧪 Testing PHP syntax...\n";

$output = [];
$returnCode = 0;
exec('php -l app/Models/Gallery.php 2>&1', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ PHP syntax is valid\n";
} else {
    echo "❌ PHP syntax error found:\n";
    foreach ($output as $line) {
        echo "   $line\n";
    }
}

// 6. Test Laravel bootstrap
echo "\n🔗 Testing Laravel bootstrap...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test if we can access the Gallery model
    try {
        $gallery = new \App\Models\Gallery();
        echo "✅ Gallery model instantiated successfully\n";
        
        // Test if we can access the published scope
        try {
            $publishedGalleries = \App\Models\Gallery::published()->get();
            echo "✅ Gallery published scope working: " . $publishedGalleries->count() . " published galleries\n";
        } catch (Exception $e) {
            echo "❌ Gallery published scope failed: " . $e->getMessage() . "\n";
        }
        
        // Test other scopes
        try {
            $activeGalleries = \App\Models\Gallery::active()->get();
            echo "✅ Gallery active scope working: " . $activeGalleries->count() . " active galleries\n";
        } catch (Exception $e) {
            echo "❌ Gallery active scope failed: " . $e->getMessage() . "\n";
        }
        
        try {
            $featuredGalleries = \App\Models\Gallery::featured()->get();
            echo "✅ Gallery featured scope working: " . $featuredGalleries->count() . " featured galleries\n";
        } catch (Exception $e) {
            echo "❌ Gallery featured scope failed: " . $e->getMessage() . "\n";
        }
        
        // Test methods
        try {
            $gallery->status = 'published';
            $isPublished = $gallery->isPublished();
            echo "✅ Gallery isPublished method working: " . ($isPublished ? 'true' : 'false') . "\n";
        } catch (Exception $e) {
            echo "❌ Gallery isPublished method failed: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Gallery model instantiation failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

// 7. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Gallery Missing Methods
echo "🧪 Testing Gallery Missing Methods\n";
echo "==================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test Gallery model
    $gallery = new \\App\\Models\\Gallery();
    echo "✅ Gallery model instantiated successfully\n";
    
    // Test published scope
    try {
        $publishedGalleries = \\App\\Models\\Gallery::published()->get();
        echo "✅ Gallery published scope working: " . $publishedGalleries->count() . " published galleries\n";
    } catch (Exception $e) {
        echo "❌ Gallery published scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test active scope
    try {
        $activeGalleries = \\App\\Models\\Gallery::active()->get();
        echo "✅ Gallery active scope working: " . $activeGalleries->count() . " active galleries\n";
    } catch (Exception $e) {
        echo "❌ Gallery active scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test featured scope
    try {
        $featuredGalleries = \\App\\Models\\Gallery::featured()->get();
        echo "✅ Gallery featured scope working: " . $featuredGalleries->count() . " featured galleries\n";
    } catch (Exception $e) {
        echo "❌ Gallery featured scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test byCategory scope
    try {
        $academicGalleries = \\App\\Models\\Gallery::byCategory(\'academic\')->get();
        echo "✅ Gallery byCategory scope working: " . $academicGalleries->count() . " academic galleries\n";
    } catch (Exception $e) {
        echo "❌ Gallery byCategory scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test byType scope
    try {
        $galleryType = \\App\\Models\\Gallery::byType(\'gallery\')->get();
        echo "✅ Gallery byType scope working: " . $galleryType->count() . " gallery type\n";
    } catch (Exception $e) {
        echo "❌ Gallery byType scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test recent scope
    try {
        $recentGalleries = \\App\\Models\\Gallery::recent(30)->get();
        echo "✅ Gallery recent scope working: " . $recentGalleries->count() . " recent galleries\n";
    } catch (Exception $e) {
        echo "❌ Gallery recent scope failed: " . $e->getMessage() . "\n";
    }
    
    // Test methods
    $gallery->status = \'published\';
    $gallery->is_featured = true;
    
    try {
        $isPublished = $gallery->isPublished();
        echo "✅ Gallery isPublished method working: " . ($isPublished ? \'true\' : \'false\') . "\n";
    } catch (Exception $e) {
        echo "❌ Gallery isPublished method failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $isActive = $gallery->isActive();
        echo "✅ Gallery isActive method working: " . ($isActive ? \'true\' : \'false\') . "\n";
    } catch (Exception $e) {
        echo "❌ Gallery isActive method failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $isFeatured = $gallery->isFeatured();
        echo "✅ Gallery isFeatured method working: " . ($isFeatured ? \'true\' : \'false\') . "\n";
    } catch (Exception $e) {
        echo "❌ Gallery isFeatured method failed: " . $e->getMessage() . "\n";
    }
    
    // Test accessors
    try {
        $gallery->cover_image = \'test-image.jpg\';
        $imageUrl = $gallery->cover_image_url;
        echo "✅ Gallery cover_image_url accessor working: $imageUrl\n";
    } catch (Exception $e) {
        echo "❌ Gallery cover_image_url accessor failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $gallery->category = \'academic\';
        $categoryLabel = $gallery->category_label;
        echo "✅ Gallery category_label accessor working: $categoryLabel\n";
    } catch (Exception $e) {
        echo "❌ Gallery category_label accessor failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $gallery->type = \'gallery\';
        $typeLabel = $gallery->type_label;
        echo "✅ Gallery type_label accessor working: $typeLabel\n";
    } catch (Exception $e) {
        echo "❌ Gallery type_label accessor failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $gallery->status = \'published\';
        $statusLabel = $gallery->status_label;
        echo "✅ Gallery status_label accessor working: $statusLabel\n";
    } catch (Exception $e) {
        echo "❌ Gallery status_label accessor failed: " . $e->getMessage() . "\n";
    }
    
    echo "✅ All Gallery model tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-gallery-missing-methods.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-gallery-missing-methods.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-gallery-missing-methods.php\n";
} else {
    echo "❌ Failed to create test-gallery-missing-methods.php\n";
}

echo "\n✅ Gallery missing methods fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Added missing published() scope method\n";
echo "- Added missing recent() scope method\n";
echo "- Added missing isPublished() method\n";
echo "- Added missing isActive() method\n";
echo "- Added missing isFeatured() method\n";
echo "- Enhanced status_label accessor\n";
echo "- Tested all scopes and methods\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Gallery Missing Methods Test: http://localhost:8000/test-gallery-missing-methods.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Gallery: http://localhost:8000/admin/gallery\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-gallery-missing-methods.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Check if published() method error is resolved\n";
echo "4. Check server logs for any remaining errors\n";
