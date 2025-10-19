<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryItem extends Model
{
    protected $fillable = [
        'gallery_id',
        'title',
        'description',
        'image',
        'video_url',
        'type',
        'is_featured',
        'is_active',
        'sort_order',
        'metadata'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'array'
    ];

    // Relationships
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if (!$this->file_path) {
            return asset('images/default-gallery-item.png');
        }
        
        if (filter_var($this->file_path, FILTER_VALIDATE_URL)) {
            return $this->file_path;
        }
        
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }
        
        // Fallback uploads -> storage
        if (str_starts_with($this->file_path, 'uploads/gallery-items/')) {
            return asset(str_replace('uploads/gallery-items/', 'storage/gallery-items/', $this->file_path));
        }
        
        if (str_starts_with($this->file_path, 'gallery-items/')) {
            return asset('storage/' . $this->file_path);
        }
        
        if (str_starts_with($this->file_path, 'storage/')) {
            return asset($this->file_path);
        }
        
        if (!str_starts_with($this->file_path, 'gallery-items/') && 
            !str_starts_with($this->file_path, 'storage/')) {
            return asset('storage/' . $this->file_path);
        }
        
        return asset('images/default-gallery-item.png');
    }
    
    public function getThumbnailUrlAttribute()
    {
        if (!$this->thumbnail_path) {
            return $this->image_url; // Fallback to main image
        }
        
        if (filter_var($this->thumbnail_path, FILTER_VALIDATE_URL)) {
            return $this->thumbnail_path;
        }
        
        if (str_starts_with($this->thumbnail_path, 'http://') || str_starts_with($this->thumbnail_path, 'https://')) {
            return $this->thumbnail_path;
        }
        
        // Fallback uploads -> storage
        if (str_starts_with($this->thumbnail_path, 'uploads/gallery-items/')) {
            return asset(str_replace('uploads/gallery-items/', 'storage/gallery-items/', $this->thumbnail_path));
        }
        
        if (str_starts_with($this->thumbnail_path, 'gallery-items/')) {
            return asset('storage/' . $this->thumbnail_path);
        }
        
        if (str_starts_with($this->thumbnail_path, 'storage/')) {
            return asset($this->thumbnail_path);
        }
        
        if (!str_starts_with($this->thumbnail_path, 'gallery-items/') && 
            !str_starts_with($this->thumbnail_path, 'storage/')) {
            return asset('storage/' . $this->thumbnail_path);
        }
        
        return $this->image_url; // Fallback to main image
    }

    public function getTypeLabelAttribute()
    {
        $types = [
            'image' => 'Gambar',
            'video' => 'Video',
            'document' => 'Dokumen'
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    // Methods
    public function isImage()
    {
        return $this->type === 'image';
    }

    public function isVideo()
    {
        return $this->type === 'video';
    }

    public function isDocument()
    {
        return $this->type === 'document';
    }
}