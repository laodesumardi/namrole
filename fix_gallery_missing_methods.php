<?php
/**
 * Fix Gallery Missing Methods
 * 
 * This script adds missing methods to the Gallery model
 * Specifically for getItemCount() method
 */

echo "🔧 Fixing Gallery Missing Methods\n";
echo "=================================\n\n";

echo "🔧 Adding missing methods to Gallery model...\n";

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

// 2. Update Gallery model with missing methods
echo "\n🔧 Updating Gallery model with missing methods...\n";

$galleryModelContent = '<?php

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

    /**
     * Get the count of items in this gallery
     */
    public function getItemCount()
    {
        return $this->items()->count();
    }

    /**
     * Get the count of items in this gallery (alias for getItemCount)
     */
    public function itemCount()
    {
        return $this->getItemCount();
    }

    /**
     * Get the count of items in this gallery (accessor)
     */
    public function getItemCountAttribute()
    {
        return $this->getItemCount();
    }

    /**
     * Get the first item in this gallery
     */
    public function getFirstItem()
    {
        return $this->items()->first();
    }

    /**
     * Get the last item in this gallery
     */
    public function getLastItem()
    {
        return $this->items()->latest()->first();
    }

    /**
     * Get random items from this gallery
     */
    public function getRandomItems($limit = 3)
    {
        return $this->items()->inRandomOrder()->limit($limit)->get();
    }

    /**
     * Get recent items from this gallery
     */
    public function getRecentItems($limit = 5)
    {
        return $this->items()->latest()->limit($limit)->get();
    }

    /**
     * Check if gallery has items
     */
    public function hasItems()
    {
        return $this->getItemCount() > 0;
    }

    /**
     * Get gallery summary
     */
    public function getSummary()
    {
        return [
            \'id\' => $this->id,
            \'title\' => $this->title,
            \'description\' => $this->description,
            \'category\' => $this->category_label,
            \'type\' => $this->type_label,
            \'status\' => $this->status_label,
            \'is_featured\' => $this->is_featured,
            \'item_count\' => $this->getItemCount(),
            \'cover_image_url\' => $this->cover_image_url,
            \'created_at\' => $this->created_at,
            \'updated_at\' => $this->updated_at
        ];
    }
}';

if (file_put_contents('app/Models/Gallery.php', $galleryModelContent)) {
    echo "✅ Gallery model updated with missing methods\n";
} else {
    echo "❌ Failed to update Gallery model\n";
}

// 3. Test Gallery model
echo "\n🧪 Testing Gallery model...\n";

try {
    $galleries = \App\Models\Gallery::all();
    echo "✅ Gallery model working: " . $galleries->count() . " records\n";
    
    foreach ($galleries as $gallery) {
        echo "   - " . $gallery->title . " (ID: " . $gallery->id . ")\n";
        echo "     Item Count: " . $gallery->getItemCount() . "\n";
        echo "     Has Items: " . ($gallery->hasItems() ? 'Yes' : 'No') . "\n";
        echo "     Status: " . $gallery->status_label . "\n";
        echo "     Category: " . $gallery->category_label . "\n";
    }
} catch (Exception $e) {
    echo "❌ Gallery model test failed: " . $e->getMessage() . "\n";
}

// 4. Test specific methods
echo "\n🧪 Testing specific Gallery methods...\n";

try {
    $gallery = \App\Models\Gallery::first();
    if ($gallery) {
        echo "✅ Testing Gallery ID: " . $gallery->id . "\n";
        echo "   - getItemCount(): " . $gallery->getItemCount() . "\n";
        echo "   - itemCount(): " . $gallery->itemCount() . "\n";
        echo "   - item_count: " . $gallery->item_count . "\n";
        echo "   - hasItems(): " . ($gallery->hasItems() ? 'Yes' : 'No') . "\n";
        echo "   - isPublished(): " . ($gallery->isPublished() ? 'Yes' : 'No') . "\n";
        echo "   - isActive(): " . ($gallery->isActive() ? 'Yes' : 'No') . "\n";
        echo "   - isFeatured(): " . ($gallery->isFeatured() ? 'Yes' : 'No') . "\n";
        
        $summary = $gallery->getSummary();
        echo "   - Summary: " . json_encode($summary) . "\n";
    } else {
        echo "⚠️ No galleries found to test\n";
    }
} catch (Exception $e) {
    echo "❌ Gallery methods test failed: " . $e->getMessage() . "\n";
}

// 5. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test Gallery Missing Methods
echo "🧪 Testing Gallery Missing Methods\n";
echo "===================================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test Gallery model
    $galleries = \\App\\Models\\Gallery::all();
    echo "✅ Gallery model working: " . $galleries->count() . " records\n";
    
    foreach ($galleries as $gallery) {
        echo "✅ " . $gallery->title . " (ID: " . $gallery->id . ")\n";
        echo "   - getItemCount(): " . $gallery->getItemCount() . "\n";
        echo "   - itemCount(): " . $gallery->itemCount() . "\n";
        echo "   - item_count: " . $gallery->item_count . "\n";
        echo "   - hasItems(): " . ($gallery->hasItems() ? \'Yes\' : \'No\') . "\n";
        echo "   - isPublished(): " . ($gallery->isPublished() ? \'Yes\' : \'No\') . "\n";
        echo "   - isActive(): " . ($gallery->isActive() ? \'Yes\' : \'No\') . "\n";
        echo "   - isFeatured(): " . ($gallery->isFeatured() ? \'Yes\' : \'No\') . "\n";
        echo "   - Cover Image: " . $gallery->cover_image_url . "\n";
        echo "   - Category: " . $gallery->category_label . "\n";
        echo "   - Type: " . $gallery->type_label . "\n";
        echo "   - Status: " . $gallery->status_label . "\n";
        echo "\n";
    }
    
    echo "✅ All gallery missing methods tests completed!\n";
    
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
echo "🔧 Key methods added:\n";
echo "- getItemCount(): Get count of items in gallery\n";
echo "- itemCount(): Alias for getItemCount()\n";
echo "- getItemCountAttribute(): Accessor for item count\n";
echo "- getFirstItem(): Get first item in gallery\n";
echo "- getLastItem(): Get last item in gallery\n";
echo "- getRandomItems(): Get random items from gallery\n";
echo "- getRecentItems(): Get recent items from gallery\n";
echo "- hasItems(): Check if gallery has items\n";
echo "- getSummary(): Get gallery summary\n";
echo "- Tested Gallery model functionality\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- Gallery Missing Methods Test: http://localhost:8000/test-gallery-missing-methods.php\n";
echo "- Admin Gallery: http://localhost:8000/admin/gallery\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-gallery-missing-methods.php\n";
echo "2. Test: http://localhost:8000/admin/gallery\n";
echo "3. Check if getItemCount() method is working correctly\n";
echo "4. Check server logs for any remaining errors\n";