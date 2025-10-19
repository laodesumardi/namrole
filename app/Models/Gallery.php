<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'description',
        'cover_image',
        'category',
        'type',
        'status',
        'is_featured'
    ];

    protected $casts = [
        'is_featured' => 'boolean'
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
            if ($gallery->isDirty('title') && empty($gallery->slug)) {
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
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getCoverImageUrlAttribute()
    {
        if (!$this->cover_image) {
            return asset('images/default-gallery.png');
        }
        
        if (filter_var($this->cover_image, FILTER_VALIDATE_URL)) {
            return $this->cover_image;
        }
        
        if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
            return $this->cover_image;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->cover_image, 'storage/')) {
            return asset($this->cover_image);
        }
        
        // If it starts with galleries/, add storage/ prefix
        if (str_starts_with($this->cover_image, 'galleries/')) {
            return asset('storage/' . $this->cover_image);
        }
        
        // If it starts with uploads/galleries/, change to storage/galleries/
        if (str_starts_with($this->cover_image, 'uploads/galleries/')) {
            return asset(str_replace('uploads/galleries/', 'storage/galleries/', $this->cover_image));
        }
        
        // If it's just a filename, add the full path
        if (!str_contains($this->cover_image, '/')) {
            return asset('storage/galleries/' . $this->cover_image);
        }
        
        // Default fallback
        return asset('images/default-gallery.png');
    }

    public function getImageUrlAttribute()
    {
        return $this->cover_image_url;
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            'academic' => 'Akademik',
            'extracurricular' => 'Ekstrakurikuler',
            'event' => 'Acara',
            'sport' => 'Olahraga',
            'art' => 'Seni',
            'other' => 'Lainnya'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    public function getTypeLabelAttribute()
    {
        return $this->type === 'gallery' ? 'Galeri' : 'Album';
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'active' => 'Aktif',
            'inactive' => 'Tidak Aktif',
            'draft' => 'Draft'
        ];

        return $statuses[$this->status] ?? ucfirst($this->status);
    }
}