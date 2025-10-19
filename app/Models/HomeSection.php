<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'description',
        'image',
        'image_alt',
        'image_position',
        'button_text',
        'button_link',
        'background_color',
        'text_color',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-section.png');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->image, 'storage/')) {
            return asset($this->image);
        }
        
        // If it starts with home-sections/, add storage/ prefix
        if (str_starts_with($this->image, 'home-sections/')) {
            return asset('storage/' . $this->image);
        }
        
        // If it starts with public/, remove it and add storage/
        if (str_starts_with($this->image, 'public/')) {
            return asset('storage/' . str_replace('public/', '', $this->image));
        }
        
        // If it's just a filename, add the full path
        if (!str_contains($this->image, '/')) {
            return asset('storage/home-sections/' . $this->image);
        }
        
        // Default fallback
        return asset('images/default-section.png');
    }
}