<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeadmasterGreeting extends Model
{
    protected $fillable = [
        'headmaster_name',
        'greeting_message',
        'photo',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            return asset('images/default-headmaster.png');
        }
        
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        
        if (str_starts_with($this->photo, 'http://') || str_starts_with($this->photo, 'https://')) {
            return $this->photo;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->photo, 'storage/')) {
            return asset($this->photo);
        }
        
        // If it starts with headmaster-greetings/, add storage/ prefix
        if (str_starts_with($this->photo, 'headmaster-greetings/')) {
            return asset('storage/' . $this->photo);
        }
        
        // If it starts with uploads/headmaster-greetings/, change to storage/headmaster-greetings/
        if (str_starts_with($this->photo, 'uploads/headmaster-greetings/')) {
            return asset(str_replace('uploads/headmaster-greetings/', 'storage/headmaster-greetings/', $this->photo));
        }
        
        // If it's just a filename, add the full path
        if (!str_contains($this->photo, '/')) {
            return asset('storage/headmaster-greetings/' . $this->photo);
        }
        
        // Default fallback
        return asset('images/default-headmaster.png');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}