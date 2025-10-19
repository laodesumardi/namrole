<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset('images/default-facility.png');
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
        
        // If it starts with facilities/, add storage/ prefix
        if (str_starts_with($this->image, 'facilities/')) {
            return asset('storage/' . $this->image);
        }
        
        // If it starts with uploads/facilities/, change to storage/facilities/
        if (str_starts_with($this->image, 'uploads/facilities/')) {
            return asset(str_replace('uploads/facilities/', 'storage/facilities/', $this->image));
        }
        
        // If it's just a filename, add the full path
        if (!str_contains($this->image, '/')) {
            return asset('storage/facilities/' . $this->image);
        }
        
        // Default fallback
        return asset('images/default-facility.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}