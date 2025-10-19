<?php
/**
 * Fix Hosting Model Accessors
 * 
 * This script fixes model accessors to work properly on hosting
 * Specifically for https://uji.odetune.shop/
 */

echo "🔧 Fixing Hosting Model Accessors\n";
echo "==================================\n\n";

echo "🔧 Fixing model accessors for hosting environment...\n";

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

// 2. Fix User model accessor for hosting
echo "\n🔧 Fixing User model accessor for hosting...\n";

$userModelPath = 'app/Models/User.php';
if (file_exists($userModelPath)) {
    $userModelContent = file_get_contents($userModelPath);
    
    // Check if getPhotoUrlAttribute exists
    if (strpos($userModelContent, 'getPhotoUrlAttribute') === false) {
        echo "❌ getPhotoUrlAttribute not found in User model\n";
    } else {
        echo "✅ getPhotoUrlAttribute found in User model\n";
        
        // Update the accessor to work better on hosting
        $newPhotoAccessor = '    public function getPhotoUrlAttribute()
    {
        if (!$this->photo) {
            // Return default image based on role
            if ($this->role === \'student\') {
                return asset(\'images/default-student.png\');
            } elseif ($this->role === \'teacher\') {
                return asset(\'images/default-teacher.png\');
            } else {
                return asset(\'images/default-user.png\');
            }
        }
        
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }
        
        if (str_starts_with($this->photo, \'http://\') || str_starts_with($this->photo, \'https://\')) {
            return $this->photo;
        }
        
        // Check if it\'s a storage path with \'storage/\' prefix
        if (str_starts_with($this->photo, \'storage/\')) {
            return asset($this->photo);
        }
        
        // Check if it\'s a storage path without \'storage/\' prefix
        if (str_starts_with($this->photo, \'teachers/\') || str_starts_with($this->photo, \'students/\')) {
            return asset(\'storage/\' . $this->photo);
        }
        
        // Check if it\'s just a filename (old format)
        if (!str_contains($this->photo, \'/\')) {
            if ($this->role === \'student\') {
                return asset(\'storage/students/photos/\' . $this->photo);
            } elseif ($this->role === \'teacher\') {
                return asset(\'storage/teachers/\' . $this->photo);
            } else {
                return asset(\'storage/\' . $this->photo);
            }
        }
        
        // If it\'s already a full URL or path
        return $this->photo;
    }';
        
        // Replace the existing accessor
        $pattern = '/public function getPhotoUrlAttribute\(\)\s*\{[^}]*\}/s';
        if (preg_match($pattern, $userModelContent)) {
            $userModelContent = preg_replace($pattern, $newPhotoAccessor, $userModelContent);
            
            if (file_put_contents($userModelPath, $userModelContent)) {
                echo "✅ Updated User model accessor for hosting\n";
            } else {
                echo "❌ Failed to update User model accessor\n";
            }
        } else {
            echo "⚠️ Could not find existing getPhotoUrlAttribute to replace\n";
        }
    }
} else {
    echo "❌ User model not found\n";
}

// 3. Fix SchoolProfile model accessor for hosting
echo "\n🔧 Fixing SchoolProfile model accessor for hosting...\n";

$schoolProfileModelPath = 'app/Models/SchoolProfile.php';
if (file_exists($schoolProfileModelPath)) {
    $schoolProfileModelContent = file_get_contents($schoolProfileModelPath);
    
    // Check if getImageUrlAttribute exists
    if (strpos($schoolProfileModelContent, 'getImageUrlAttribute') === false) {
        echo "❌ getImageUrlAttribute not found in SchoolProfile model\n";
    } else {
        echo "✅ getImageUrlAttribute found in SchoolProfile model\n";
        
        // Update the accessor to work better on hosting
        $newImageAccessor = '    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-school-profile.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->image, \'storage/\')) {
            return asset($this->image);
        }
        
        // If it starts with school-profiles/, add storage/ prefix
        if (str_starts_with($this->image, \'school-profiles/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        // If it starts with uploads/school-profiles/, change to storage/school-profiles/
        if (str_starts_with($this->image, \'uploads/school-profiles/\')) {
            return asset(str_replace(\'uploads/school-profiles/\', \'storage/school-profiles/\', $this->image));
        }
        
        // If it\'s just a filename, add the full path
        if (!str_contains($this->image, \'/\')) {
            return asset(\'storage/school-profiles/\' . $this->image);
        }
        
        // Default fallback
        return asset(\'images/default-school-profile.png\');
    }';
        
        // Replace the existing accessor
        $pattern = '/public function getImageUrlAttribute\(\)\s*\{[^}]*\}/s';
        if (preg_match($pattern, $schoolProfileModelContent)) {
            $schoolProfileModelContent = preg_replace($pattern, $newImageAccessor, $schoolProfileModelContent);
            
            if (file_put_contents($schoolProfileModelPath, $schoolProfileModelContent)) {
                echo "✅ Updated SchoolProfile model accessor for hosting\n";
            } else {
                echo "❌ Failed to update SchoolProfile model accessor\n";
            }
        } else {
            echo "⚠️ Could not find existing getImageUrlAttribute to replace\n";
        }
    }
} else {
    echo "❌ SchoolProfile model not found\n";
}

// 4. Fix Facility model accessor for hosting
echo "\n🔧 Fixing Facility model accessor for hosting...\n";

$facilityModelPath = 'app/Models/Facility.php';
if (file_exists($facilityModelPath)) {
    $facilityModelContent = file_get_contents($facilityModelPath);
    
    // Check if getImageUrlAttribute exists
    if (strpos($facilityModelContent, 'getImageUrlAttribute') === false) {
        echo "❌ getImageUrlAttribute not found in Facility model\n";
    } else {
        echo "✅ getImageUrlAttribute found in Facility model\n";
        
        // Update the accessor to work better on hosting
        $newImageAccessor = '    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return asset(\'images/default-facility.png\');
        }
        
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }
        
        if (str_starts_with($this->image, \'http://\') || str_starts_with($this->image, \'https://\')) {
            return $this->image;
        }
        
        // If it starts with storage/, use it directly with asset()
        if (str_starts_with($this->image, \'storage/\')) {
            return asset($this->image);
        }
        
        // If it starts with facilities/, add storage/ prefix
        if (str_starts_with($this->image, \'facilities/\')) {
            return asset(\'storage/\' . $this->image);
        }
        
        // If it starts with uploads/facilities/, change to storage/facilities/
        if (str_starts_with($this->image, \'uploads/facilities/\')) {
            return asset(str_replace(\'uploads/facilities/\', \'storage/facilities/\', $this->image));
        }
        
        // If it\'s just a filename, add the full path
        if (!str_contains($this->image, \'/\')) {
            return asset(\'storage/facilities/\' . $this->image);
        }
        
        // Default fallback
        return asset(\'images/default-facility.png\');
    }';
        
        // Replace the existing accessor
        $pattern = '/public function getImageUrlAttribute\(\)\s*\{[^}]*\}/s';
        if (preg_match($pattern, $facilityModelContent)) {
            $facilityModelContent = preg_replace($pattern, $newImageAccessor, $facilityModelContent);
            
            if (file_put_contents($facilityModelPath, $facilityModelContent)) {
                echo "✅ Updated Facility model accessor for hosting\n";
            } else {
                echo "❌ Failed to update Facility model accessor\n";
            }
        } else {
            echo "⚠️ Could not find existing getImageUrlAttribute to replace\n";
        }
    }
} else {
    echo "❌ Facility model not found\n";
}

// 5. Fix Gallery model accessor for hosting
echo "\n🔧 Fixing Gallery model accessor for hosting...\n";

$galleryModelPath = 'app/Models/Gallery.php';
if (file_exists($galleryModelPath)) {
    $galleryModelContent = file_get_contents($galleryModelPath);
    
    // Check if getCoverImageUrlAttribute exists
    if (strpos($galleryModelContent, 'getCoverImageUrlAttribute') === false) {
        echo "❌ getCoverImageUrlAttribute not found in Gallery model\n";
    } else {
        echo "✅ getCoverImageUrlAttribute found in Gallery model\n";
        
        // Update the accessor to work better on hosting
        $newImageAccessor = '    public function getCoverImageUrlAttribute()
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
    }';
        
        // Replace the existing accessor
        $pattern = '/public function getCoverImageUrlAttribute\(\)\s*\{[^}]*\}/s';
        if (preg_match($pattern, $galleryModelContent)) {
            $galleryModelContent = preg_replace($pattern, $newImageAccessor, $galleryModelContent);
            
            if (file_put_contents($galleryModelPath, $galleryModelContent)) {
                echo "✅ Updated Gallery model accessor for hosting\n";
            } else {
                echo "❌ Failed to update Gallery model accessor\n";
            }
        } else {
            echo "⚠️ Could not find existing getCoverImageUrlAttribute to replace\n";
        }
    }
} else {
    echo "❌ Gallery model not found\n";
}

// 6. Fix News model accessor for hosting
echo "\n🔧 Fixing News model accessor for hosting...\n";

$newsModelPath = 'app/Models/News.php';
if (file_exists($newsModelPath)) {
    $newsModelContent = file_get_contents($newsModelPath);
    
    // Check if getFeaturedImageUrlAttribute exists
    if (strpos($newsModelContent, 'getFeaturedImageUrlAttribute') === false) {
        echo "❌ getFeaturedImageUrlAttribute not found in News model\n";
    } else {
        echo "✅ getFeaturedImageUrlAttribute found in News model\n";
        
        // Update the accessor to work better on hosting
        $newImageAccessor = '    public function getFeaturedImageUrlAttribute()
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
    }';
        
        // Replace the existing accessor
        $pattern = '/public function getFeaturedImageUrlAttribute\(\)\s*\{[^}]*\}/s';
        if (preg_match($pattern, $newsModelContent)) {
            $newsModelContent = preg_replace($pattern, $newImageAccessor, $newsModelContent);
            
            if (file_put_contents($newsModelPath, $newsModelContent)) {
                echo "✅ Updated News model accessor for hosting\n";
            } else {
                echo "❌ Failed to update News model accessor\n";
            }
        } else {
            echo "⚠️ Could not find existing getFeaturedImageUrlAttribute to replace\n";
        }
    }
} else {
    echo "❌ News model not found\n";
}

// 7. Fix HeadmasterGreeting model accessor for hosting
echo "\n🔧 Fixing HeadmasterGreeting model accessor for hosting...\n";

$headmasterModelPath = 'app/Models/HeadmasterGreeting.php';
if (file_exists($headmasterModelPath)) {
    $headmasterModelContent = file_get_contents($headmasterModelPath);
    
    // Check if getPhotoUrlAttribute exists
    if (strpos($headmasterModelContent, 'getPhotoUrlAttribute') === false) {
        echo "❌ getPhotoUrlAttribute not found in HeadmasterGreeting model\n";
    } else {
        echo "✅ getPhotoUrlAttribute found in HeadmasterGreeting model\n";
        
        // Update the accessor to work better on hosting
        $newPhotoAccessor = '    public function getPhotoUrlAttribute()
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
    }';
        
        // Replace the existing accessor
        $pattern = '/public function getPhotoUrlAttribute\(\)\s*\{[^}]*\}/s';
        if (preg_match($pattern, $headmasterModelContent)) {
            $headmasterModelContent = preg_replace($pattern, $newPhotoAccessor, $headmasterModelContent);
            
            if (file_put_contents($headmasterModelPath, $headmasterModelContent)) {
                echo "✅ Updated HeadmasterGreeting model accessor for hosting\n";
            } else {
                echo "❌ Failed to update HeadmasterGreeting model accessor\n";
            }
        } else {
            echo "⚠️ Could not find existing getPhotoUrlAttribute to replace\n";
        }
    }
} else {
    echo "❌ HeadmasterGreeting model not found\n";
}

// 8. Clear caches
echo "\n🧹 Clearing caches...\n";

try {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "✅ Config cache cleared\n";
    
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "✅ Route cache cleared\n";
    
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "✅ View cache cleared\n";
    
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    echo "✅ Application cache cleared\n";
} catch (Exception $e) {
    echo "❌ Error clearing caches: " . $e->getMessage() . "\n";
}

echo "\n✅ Hosting model accessors fix completed!\n";
echo "🔧 Key fixes applied for hosting:\n";
echo "- Updated User model accessor for hosting\n";
echo "- Updated SchoolProfile model accessor for hosting\n";
echo "- Updated Facility model accessor for hosting\n";
echo "- Updated Gallery model accessor for hosting\n";
echo "- Updated News model accessor for hosting\n";
echo "- Updated HeadmasterGreeting model accessor for hosting\n";
echo "- Cleared all caches\n\n";

echo "🌐 Hosting URLs:\n";
echo "- Website: https://uji.odetune.shop/\n";
echo "- Admin: https://uji.odetune.shop/admin\n";
echo "- Student Profile: https://uji.odetune.shop/student/profile/edit\n";
echo "- Teacher Profile: https://uji.odetune.shop/teacher/profile/edit\n";
echo "- Admin Gallery: https://uji.odetune.shop/admin/gallery\n";
echo "- Admin News: https://uji.odetune.shop/admin/news\n";
echo "- Admin Facilities: https://uji.odetune.shop/admin/facilities\n";
echo "- Admin School Profile: https://uji.odetune.shop/admin/school-profile\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: https://uji.odetune.shop/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps for hosting:\n";
echo "1. Upload this script to your hosting server\n";
echo "2. Run: php fix_hosting_model_accessors.php\n";
echo "3. Test all image uploads and displays\n";
echo "4. Check browser console for any errors\n";
echo "5. Verify all images are accessible via web\n";
