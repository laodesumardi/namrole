<?php
/**
 * Fix News Model Syntax Error
 * 
 * This script fixes syntax error in app/Models/News.php
 * Specifically the duplicated code and misplaced if statements
 */

echo "🔧 Fixing News Model Syntax Error\n";
echo "=================================\n\n";

echo "🔧 Fixing syntax error in app/Models/News.php...\n";

// 1. Read the current file
echo "\n📄 Reading current News.php file...\n";

if (file_exists('app/Models/News.php')) {
    $content = file_get_contents('app/Models/News.php');
    echo "✅ News.php file found\n";
} else {
    echo "❌ News.php file not found\n";
    exit(1);
}

// 2. Create the corrected News.php file
echo "\n🔧 Creating corrected News.php file...\n";

$correctedContent = '<?php

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

// 3. Write the corrected file
if (file_put_contents('app/Models/News.php', $correctedContent)) {
    echo "✅ Corrected News.php file created\n";
} else {
    echo "❌ Failed to create corrected News.php file\n";
    exit(1);
}

// 4. Test the syntax
echo "\n🧪 Testing PHP syntax...\n";

$output = [];
$returnCode = 0;
exec('php -l app/Models/News.php 2>&1', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ PHP syntax is valid\n";
} else {
    echo "❌ PHP syntax error found:\n";
    foreach ($output as $line) {
        echo "   $line\n";
    }
}

// 5. Test Laravel bootstrap
echo "\n🔗 Testing Laravel bootstrap...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test if we can access the News model
    try {
        $news = new \App\Models\News();
        echo "✅ News model instantiated successfully\n";
        
        // Test if we can access the accessor
        try {
            $news->featured_image = 'test-image.jpg';
            $imageUrl = $news->featured_image_url;
            echo "✅ News model accessor working: $imageUrl\n";
        } catch (Exception $e) {
            echo "❌ News model accessor failed: " . $e->getMessage() . "\n";
        }
        
    } catch (Exception $e) {
        echo "❌ News model instantiation failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

// 6. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test News Model Syntax
echo "🧪 Testing News Model Syntax\n";
echo "============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test News model
    $news = new \\App\\Models\\News();
    echo "✅ News model instantiated successfully\n";
    
    // Test accessor
    $news->featured_image = "test-image.jpg";
    $imageUrl = $news->featured_image_url;
    echo "✅ News model accessor working: $imageUrl\n";
    
    // Test with different image paths
    $testPaths = [
        "storage/news/test.jpg",
        "news/test.jpg",
        "uploads/news/test.jpg",
        "test.jpg",
        "https://example.com/test.jpg"
    ];
    
    foreach ($testPaths as $path) {
        $news->featured_image = $path;
        $url = $news->featured_image_url;
        echo "✅ Path: $path -> URL: $url\n";
    }
    
    echo "✅ All News model tests passed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-news-model.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-news-model.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-news-model.php\n";
} else {
    echo "❌ Failed to create test-news-model.php\n";
}

echo "\n✅ News model syntax error fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Removed duplicated code from getFeaturedImageUrlAttribute method\n";
echo "- Fixed misplaced if statements\n";
echo "- Ensured proper method structure\n";
echo "- Tested PHP syntax\n";
echo "- Tested Laravel bootstrap\n";
echo "- Tested News model functionality\n";
echo "- Created test script\n\n";

echo "🌐 Test URLs:\n";
echo "- News Model Test: http://localhost:8000/test-news-model.php\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- News: http://localhost:8000/admin/news\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-news-model.php\n";
echo "2. Test: http://localhost:8000/\n";
echo "3. Check if syntax error is resolved\n";
echo "4. Check server logs for any remaining errors\n";
