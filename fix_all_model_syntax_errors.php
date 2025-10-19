<?php
/**
 * Fix All Model Syntax Errors
 * 
 * This script fixes syntax errors in all models
 * Specifically for Gallery, News, HeadmasterGreeting, and other models
 */

echo "🔧 Fixing All Model Syntax Errors\n";
echo "=================================\n\n";

echo "🔧 Fixing syntax errors in all models...\n";

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

// 2. Fix Gallery model
echo "\n🔧 Fixing Gallery model...\n";

$galleryContent = '<?php

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
            \'draft\' => \'Draft\'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }
}';

if (file_put_contents('app/Models/Gallery.php', $galleryContent)) {
    echo "✅ Gallery model fixed\n";
} else {
    echo "❌ Failed to fix Gallery model\n";
}

// 3. Fix News model
echo "\n🔧 Fixing News model...\n";

$newsContent = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class News extends Model
{
    protected $fillable = [
        \'title\',
        \'slug\',
        \'excerpt\',
        \'content\',
        \'featured_image\',
        \'category\',
        \'type\',
        \'status\',
        \'is_featured\',
        \'is_pinned\',
        \'views\',
        \'published_at\',
        \'author_name\',
        \'author_email\',
        \'tags\',
        \'meta_data\'
    ];

    protected $casts = [
        \'is_featured\' => \'boolean\',
        \'is_pinned\' => \'boolean\',
        \'published_at\' => \'datetime\',
        \'tags\' => \'array\',
        \'meta_data\' => \'array\'
    ];

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });

        static::updating(function ($news) {
            if ($news->isDirty(\'title\') && empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where(\'status\', \'published\')
                    ->where(function($q) {
                        $q->whereNull(\'published_at\')
                          ->orWhere(\'published_at\', \'<=\', now());
                    });
    }

    public function scopeFeatured($query)
    {
        return $query->where(\'is_featured\', true);
    }

    public function scopePinned($query)
    {
        return $query->where(\'is_pinned\', true);
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
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return asset(\'images/default-news.png\');
        }
        
        if (filter_var($this->featured_image, FILTER_VALIDATE_URL)) {
            return $this->featured_image;
        }
        
        if (str_starts_with($this->featured_image, \'http://\') || str_starts_with($this->featured_image, \'https://\')) {
            return $this->featured_image;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->featured_image, \'storage/\')) {
            return asset($this->featured_image);
        }
        
        // If it starts with news/, add storage/ prefix
        if (str_starts_with($this->featured_image, \'news/\')) {
            return asset(\'storage/\' . $this->featured_image);
        }
        
        // If it starts with uploads/news/, change to storage/news/
        if (str_starts_with($this->featured_image, \'uploads/news/\')) {
            return asset(str_replace(\'uploads/news/\', \'storage/news/\', $this->featured_image));
        }
        
        // If it\'s just a filename, add the full path
        if (!str_contains($this->featured_image, \'/\')) {
            return asset(\'storage/news/\' . $this->featured_image);
        }
        
        // Default fallback
        return asset(\'images/default-news.png\');
    }

    public function getExcerptAttribute($value)
    {
        if (empty($value) && $this->content) {
            return Str::limit(strip_tags($this->content), 150);
        }
        return $value;
    }

    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $minutes = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return $minutes . \' menit\';
    }

    // Methods
    public function incrementViews()
    {
        $this->increment(\'views\');
    }

    public function isPublished()
    {
        return $this->status === \'published\' && 
               ($this->published_at === null || $this->published_at <= now());
    }

    public function getCategoryLabel()
    {
        $categories = [
            \'akademik\' => \'Akademik\',
            \'ekstrakurikuler\' => \'Ekstrakurikuler\',
            \'libur\' => \'Libur Nasional\',
            \'jadwal\' => \'Perubahan Jadwal\',
            \'osis\' => \'Kegiatan OSIS\',
            \'lomba\' => \'Lomba & Kompetisi\'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    public function getTypeLabel()
    {
        return $this->type === \'news\' ? \'Berita\' : \'Pengumuman\';
    }

    public function getStatusLabel()
    {
        $statuses = [
            \'draft\' => \'Draft\',
            \'published\' => \'Dipublikasikan\',
            \'archived\' => \'Diarsipkan\'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }
}';

if (file_put_contents('app/Models/News.php', $newsContent)) {
    echo "✅ News model fixed\n";
} else {
    echo "❌ Failed to fix News model\n";
}

// 4. Fix HeadmasterGreeting model
echo "\n🔧 Fixing HeadmasterGreeting model...\n";

$headmasterGreetingContent = '<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadmasterGreeting extends Model
{
    protected $fillable = [
        \'headmaster_name\',
        \'greeting_message\',
        \'photo\',
        \'is_active\'
    ];

    protected $casts = [
        \'is_active\' => \'boolean\'
    ];

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return asset(\'images/default-headmaster.png\');
        }
        
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        
        if (str_starts_with($this->photo, \'http://\') || str_starts_with($this->photo, \'https://\')) {
            return $this->photo;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->photo, \'storage/\')) {
            return asset($this->photo);
        }
        
        // If it starts with headmaster-greetings/, add storage/ prefix
        if (str_starts_with($this->photo, \'headmaster-greetings/\')) {
            return asset(\'storage/\' . $this->photo);
        }
        
        // If it starts with uploads/headmaster-greetings/, change to storage/headmaster-greetings/
        if (str_starts_with($this->photo, \'uploads/headmaster-greetings/\')) {
            return asset(str_replace(\'uploads/headmaster-greetings/\', \'storage/headmaster-greetings/\', $this->photo));
        }
        
        // If it\'s just a filename, add the full path
        if (!str_contains($this->photo, \'/\')) {
            return asset(\'storage/headmaster-greetings/\' . $this->photo);
        }
        
        // Default fallback
        return asset(\'images/default-headmaster.png\');
    }

    public function scopeActive($query)
    {
        return $query->where(\'is_active\', true);
    }
}';

if (file_put_contents('app/Models/HeadmasterGreeting.php', $headmasterGreetingContent)) {
    echo "✅ HeadmasterGreeting model fixed\n";
} else {
    echo "❌ Failed to fix HeadmasterGreeting model\n";
}

// 5. Test all models
echo "\n🧪 Testing all models...\n";

$models = [
    'User' => \App\Models\User::class,
    'News' => \App\Models\News::class,
    'Gallery' => \App\Models\Gallery::class,
    'GalleryItem' => \App\Models\GalleryItem::class,
    'Facility' => \App\Models\Facility::class,
    'SchoolProfile' => \App\Models\SchoolProfile::class,
    'HeadmasterGreeting' => \App\Models\HeadmasterGreeting::class
];

foreach ($models as $modelName => $modelClass) {
    try {
        $count = $modelClass::count();
        echo "✅ $modelName model working: $count records\n";
    } catch (Exception $e) {
        echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
    }
}

// 6. Test HeadmasterGreeting specifically
echo "\n🧪 Testing HeadmasterGreeting model specifically...\n";

try {
    $headmasterGreeting = \App\Models\HeadmasterGreeting::active()->first();
    if ($headmasterGreeting) {
        echo "✅ HeadmasterGreeting model working: " . $headmasterGreeting->headmaster_name . "\n";
        echo "✅ Photo URL: " . $headmasterGreeting->photo_url . "\n";
    } else {
        echo "⚠️ No active headmaster greeting found\n";
    }
} catch (Exception $e) {
    echo "❌ HeadmasterGreeting model test failed: " . $e->getMessage() . "\n";
}

// 7. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test All Models Syntax
echo "🧪 Testing All Models Syntax\n";
echo "============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test all models
    $models = [
        \'User\' => \\App\\Models\\User::class,
        \'News\' => \\App\\Models\\News::class,
        \'Gallery\' => \\App\\Models\\Gallery::class,
        \'GalleryItem\' => \\App\\Models\\GalleryItem::class,
        \'Facility\' => \\App\\Models\\Facility::class,
        \'SchoolProfile\' => \\App\\Models\\SchoolProfile::class,
        \'HeadmasterGreeting\' => \\App\\Models\\HeadmasterGreeting::class
    ];
    
    foreach ($models as $modelName => $modelClass) {
        try {
            $count = $modelClass::count();
            echo "✅ $modelName model working: $count records\n";
        } catch (Exception $e) {
            echo "❌ $modelName model failed: " . $e->getMessage() . "\n";
        }
    }
    
    // Test HeadmasterGreeting specifically
    try {
        $headmasterGreeting = \\App\\Models\\HeadmasterGreeting::active()->first();
        if ($headmasterGreeting) {
            echo "✅ HeadmasterGreeting model working: " . $headmasterGreeting->headmaster_name . "\n";
            echo "✅ Photo URL: " . $headmasterGreeting->photo_url . "\n";
        } else {
            echo "⚠️ No active headmaster greeting found\n";
        }
    } catch (Exception $e) {
        echo "❌ HeadmasterGreeting model test failed: " . $e->getMessage() . "\n";
    }
    
    echo "✅ All models syntax tests completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-all-models-syntax.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-all-models-syntax.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-all-models-syntax.php\n";
} else {
    echo "❌ Failed to create test-all-models-syntax.php\n";
}

echo "\n✅ All model syntax errors fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Fixed Gallery model syntax errors\n";
echo "- Fixed News model syntax errors\n";
echo "- Fixed HeadmasterGreeting model syntax errors\n";
echo "- Tested all models\n";
echo "- Tested HeadmasterGreeting specifically\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- All Models Test: http://localhost:8000/test-all-models-syntax.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Headmaster Greetings: http://localhost:8000/admin/headmaster-greetings\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-all-models-syntax.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Check if all syntax errors are resolved\n";
echo "4. Check server logs for any remaining errors\n";

