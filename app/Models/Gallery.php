<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'cover_image',
        'type',
        'category',
        'status',
        'is_featured',
        'is_public',
        'is_active',
        'sort_order',
        'metadata'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array'
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
        return $this->hasMany(GalleryItem::class)->orderBy('sort_order');
    }

    public function featuredItems()
    {
        return $this->hasMany(GalleryItem::class)->where('is_featured', true)->orderBy('sort_order');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_public', true);
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
            return asset('images/default-gallery.jpg');
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
        
        // If it starts with gallery/, add storage/ prefix
        if (str_starts_with($this->cover_image, 'gallery/')) {
            return asset('storage/' . $this->cover_image);
        }
        
        // If it starts with public/, remove it and add storage/
        if (str_starts_with($this->cover_image, 'public/')) {
            return asset('storage/' . str_replace('public/', '', $this->cover_image));
        }
        
        // If it's just a filename, add the full path
        if (!str_contains($this->cover_image, '/')) {
            return asset('storage/gallery/' . $this->cover_image);
        }
        
        // Default fallback
        return asset('images/default-gallery.jpg');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-gallery.png');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, 'gallery/')) {
            return asset('storage/' . $this->image);
        }
        
        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }
        
        if (!str_starts_with($this->image, 'gallery/') && 
            !str_starts_with($this->image, 'storage/')) {
            return asset('storage/' . $this->image);
        }
        
        return asset('images/default-gallery.png');
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'photo' => 'Foto',
            'video' => 'Video',
            'mixed' => 'Campuran'
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    public function getCategoryLabelAttribute()
    {
        $categories = [
            'kegiatan' => 'Kegiatan Siswa',
            'event' => 'Event Besar',
            'profil' => 'Profil Sekolah',
            'testimoni' => 'Testimoni',
            'prestasi' => 'Prestasi',
            'fasilitas' => 'Fasilitas',
            'lainnya' => 'Lainnya'
        ];

        return $categories[$this->category] ?? ucfirst($this->category);
    }

    public function getStatusLabelAttribute()
    {
        $statuses = [
            'draft' => 'Draft',
            'published' => 'Dipublikasikan',
            'archived' => 'Diarsipkan'
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
        return $this->status === 'published' && $this->is_public;
    }
}