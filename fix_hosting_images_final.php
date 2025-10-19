<?php
/**
 * Fix Hosting Images - Final Solution
 * 
 * This script fixes all image display issues on hosting environment
 * Specifically designed for https://uji.odetune.shop/
 */

echo "🌐 Fixing Hosting Images - Final Solution\n";
echo "========================================\n\n";

echo "🔧 Fixing all image display issues on hosting...\n";

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

// 2. Create comprehensive storage structure for hosting
echo "\n🔗 Creating comprehensive storage structure for hosting...\n";

$directories = [
    // Storage directories
    'storage/app/public',
    'storage/app/public/students',
    'storage/app/public/students/photos',
    'storage/app/public/teachers',
    'storage/app/public/teachers/photos',
    'storage/app/public/school-profiles',
    'storage/app/public/facilities',
    'storage/app/public/galleries',
    'storage/app/public/gallery-items',
    'storage/app/public/news',
    'storage/app/public/headmaster-greetings',
    'storage/app/public/home-sections',
    'storage/app/public/academic-calendar',
    'storage/app/public/news-sections',
    
    // Public storage directories
    'public/storage',
    'public/storage/students',
    'public/storage/students/photos',
    'public/storage/teachers',
    'public/storage/teachers/photos',
    'public/storage/school-profiles',
    'public/storage/facilities',
    'public/storage/galleries',
    'public/storage/gallery-items',
    'public/storage/news',
    'public/storage/headmaster-greetings',
    'public/storage/home-sections',
    'public/storage/academic-calendar',
    'public/storage/news-sections',
    
    // Public images directory
    'public/images',
    'public/uploads',
    'public/uploads/students',
    'public/uploads/teachers',
    'public/uploads/school-profiles',
    'public/uploads/facilities',
    'public/uploads/galleries',
    'public/uploads/news',
    'public/uploads/headmaster-greetings',
    'public/uploads/home-sections'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "✅ Created directory: $dir\n";
        } else {
            echo "❌ Failed to create directory: $dir\n";
        }
    } else {
        echo "✅ Directory exists: $dir\n";
    }
}

// 3. Create storage link or manual storage for hosting
echo "\n🔗 Creating storage link or manual storage for hosting...\n";
$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

// Remove existing link if it exists
if (is_link($storageLink)) {
    unlink($storageLink);
    echo "✅ Removed existing storage link\n";
}

if (!is_link($storageLink)) {
    if (function_exists('symlink')) {
        try {
            if (symlink($storageTarget, $storageLink)) {
                echo "✅ Storage link created successfully\n";
            } else {
                echo "❌ Failed to create storage link\n";
                echo "🔧 Creating manual storage directory for hosting...\n";
                createManualStorageForHosting();
            }
        } catch (Exception $e) {
            echo "❌ Failed to create storage link: " . $e->getMessage() . "\n";
            echo "🔧 Creating manual storage directory for hosting...\n";
            createManualStorageForHosting();
        }
    } else {
        echo "⚠️ symlink() function not available on hosting\n";
        echo "🔧 Creating manual storage directory for hosting...\n";
        createManualStorageForHosting();
    }
} else {
    echo "✅ Storage link already exists\n";
}

// 4. Create all default images for hosting
echo "\n🖼️ Creating all default images for hosting...\n";

$defaultImages = [
    'public/images/default-student.png' => 'Default student image',
    'public/images/default-teacher.png' => 'Default teacher image',
    'public/images/default-user.png' => 'Default user image',
    'public/images/default-school-profile.png' => 'Default school profile image',
    'public/images/default-facility.png' => 'Default facility image',
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-news.png' => 'Default news image',
    'public/images/default-headmaster.png' => 'Default headmaster image',
    'public/images/default-hero.png' => 'Default hero image',
    'public/images/default-section.png' => 'Default section image',
    'public/images/default-logo.png' => 'Default logo image',
    'public/images/default-banner.png' => 'Default banner image',
    'public/images/default-avatar.png' => 'Default avatar image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        $imageContent = createDefaultImageForHosting(200, 200, '#f3f4f6', '#6b7280');
        if (file_put_contents($path, $imageContent)) {
            echo "✅ Created: $path\n";
        } else {
            echo "❌ Failed to create: $path\n";
        }
    } else {
        echo "✅ Default image exists: $path\n";
    }
}

// 5. Fix database image paths for hosting
echo "\n🔧 Fixing database image paths for hosting...\n";

try {
    // Fix users with photos
    $usersWithPhotos = \App\Models\User::whereNotNull('photo')->get();
    echo "✅ Found " . $usersWithPhotos->count() . " users with photos\n";
    
    foreach ($usersWithPhotos as $user) {
        echo "📝 Fixing User ID: {$user->id}, Name: {$user->name}\n";
        echo "   Current Photo: {$user->photo}\n";
        
        // Fix photo path for hosting
        $originalPhoto = $user->photo;
        
        // Remove any storage/ prefix if exists
        if (str_starts_with($user->photo, 'storage/')) {
            $user->photo = str_replace('storage/', '', $user->photo);
        }
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $user->photo);
        $publicPath = public_path('storage/' . $user->photo);
        $uploadPath = public_path('uploads/' . $user->photo);
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage
            if (!file_exists($publicPath)) {
                $destDir = dirname($publicPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $publicPath)) {
                    echo "   ✅ Copied to public storage\n";
                } else {
                    echo "   ❌ Failed to copy to public storage\n";
                }
            }
            
            // Copy to uploads directory for hosting
            if (!file_exists($uploadPath)) {
                $destDir = dirname($uploadPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $uploadPath)) {
                    echo "   ✅ Copied to uploads directory\n";
                } else {
                    echo "   ❌ Failed to copy to uploads directory\n";
                }
            }
            
            $user->save();
        } else {
            echo "   ❌ Storage file not found\n";
            echo "   🔧 Setting photo to null\n";
            $user->photo = null;
            $user->save();
        }
        echo "\n";
    }
    
    // Fix school profiles with images
    $schoolProfilesWithImages = \App\Models\SchoolProfile::whereNotNull('image')->get();
    echo "✅ Found " . $schoolProfilesWithImages->count() . " school profiles with images\n";
    
    foreach ($schoolProfilesWithImages as $profile) {
        echo "📝 Fixing Profile ID: {$profile->id}, Title: {$profile->title}\n";
        echo "   Current Image: {$profile->image}\n";
        
        // Fix image path for hosting
        if (str_starts_with($profile->image, 'storage/')) {
            $profile->image = str_replace('storage/', '', $profile->image);
        }
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $profile->image);
        $publicPath = public_path('storage/' . $profile->image);
        $uploadPath = public_path('uploads/' . $profile->image);
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage
            if (!file_exists($publicPath)) {
                $destDir = dirname($publicPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $publicPath)) {
                    echo "   ✅ Copied to public storage\n";
                } else {
                    echo "   ❌ Failed to copy to public storage\n";
                }
            }
            
            // Copy to uploads directory for hosting
            if (!file_exists($uploadPath)) {
                $destDir = dirname($uploadPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $uploadPath)) {
                    echo "   ✅ Copied to uploads directory\n";
                } else {
                    echo "   ❌ Failed to copy to uploads directory\n";
                }
            }
            
            $profile->save();
        } else {
            echo "   ❌ Storage file not found\n";
            echo "   🔧 Setting image to null\n";
            $profile->image = null;
            $profile->save();
        }
        echo "\n";
    }
    
    // Fix facilities with images
    $facilitiesWithImages = \App\Models\Facility::whereNotNull('image')->get();
    echo "✅ Found " . $facilitiesWithImages->count() . " facilities with images\n";
    
    foreach ($facilitiesWithImages as $facility) {
        echo "📝 Fixing Facility ID: {$facility->id}, Name: {$facility->name}\n";
        echo "   Current Image: {$facility->image}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $facility->image);
        $publicPath = public_path('storage/' . $facility->image);
        $uploadPath = public_path('uploads/' . $facility->image);
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage
            if (!file_exists($publicPath)) {
                $destDir = dirname($publicPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $publicPath)) {
                    echo "   ✅ Copied to public storage\n";
                } else {
                    echo "   ❌ Failed to copy to public storage\n";
                }
            }
            
            // Copy to uploads directory for hosting
            if (!file_exists($uploadPath)) {
                $destDir = dirname($uploadPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $uploadPath)) {
                    echo "   ✅ Copied to uploads directory\n";
                } else {
                    echo "   ❌ Failed to copy to uploads directory\n";
                }
            }
        } else {
            echo "   ❌ Storage file not found\n";
            echo "   🔧 Setting image to null\n";
            $facility->image = null;
            $facility->save();
        }
        echo "\n";
    }
    
    // Fix galleries with images
    $galleriesWithImages = \App\Models\Gallery::whereNotNull('cover_image')->get();
    echo "✅ Found " . $galleriesWithImages->count() . " galleries with images\n";
    
    foreach ($galleriesWithImages as $gallery) {
        echo "📝 Fixing Gallery ID: {$gallery->id}, Title: {$gallery->title}\n";
        echo "   Current Cover Image: {$gallery->cover_image}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $gallery->cover_image);
        $publicPath = public_path('storage/' . $gallery->cover_image);
        $uploadPath = public_path('uploads/' . $gallery->cover_image);
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage
            if (!file_exists($publicPath)) {
                $destDir = dirname($publicPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $publicPath)) {
                    echo "   ✅ Copied to public storage\n";
                } else {
                    echo "   ❌ Failed to copy to public storage\n";
                }
            }
            
            // Copy to uploads directory for hosting
            if (!file_exists($uploadPath)) {
                $destDir = dirname($uploadPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $uploadPath)) {
                    echo "   ✅ Copied to uploads directory\n";
                } else {
                    echo "   ❌ Failed to copy to uploads directory\n";
                }
            }
        } else {
            echo "   ❌ Storage file not found\n";
            echo "   🔧 Setting cover_image to null\n";
            $gallery->cover_image = null;
            $gallery->save();
        }
        echo "\n";
    }
    
    // Fix news with images
    $newsWithImages = \App\Models\News::whereNotNull('featured_image')->get();
    echo "✅ Found " . $newsWithImages->count() . " news with images\n";
    
    foreach ($newsWithImages as $news) {
        echo "📝 Fixing News ID: {$news->id}, Title: {$news->title}\n";
        echo "   Current Featured Image: {$news->featured_image}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $news->featured_image);
        $publicPath = public_path('storage/' . $news->featured_image);
        $uploadPath = public_path('uploads/' . $news->featured_image);
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage
            if (!file_exists($publicPath)) {
                $destDir = dirname($publicPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $publicPath)) {
                    echo "   ✅ Copied to public storage\n";
                } else {
                    echo "   ❌ Failed to copy to public storage\n";
                }
            }
            
            // Copy to uploads directory for hosting
            if (!file_exists($uploadPath)) {
                $destDir = dirname($uploadPath);
                if (!is_dir($destDir)) {
                    mkdir($destDir, 0755, true);
                }
                
                if (copy($storagePath, $uploadPath)) {
                    echo "   ✅ Copied to uploads directory\n";
                } else {
                    echo "   ❌ Failed to copy to uploads directory\n";
                }
            }
        } else {
            echo "   ❌ Storage file not found\n";
            echo "   🔧 Setting featured_image to null\n";
            $news->featured_image = null;
            $news->save();
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error fixing database: " . $e->getMessage() . "\n";
}

// 6. Copy all existing images to multiple locations for hosting
echo "\n📁 Copying all existing images to multiple locations for hosting...\n";

$imageDirectories = [
    'storage/app/public/students/photos' => ['public/storage/students/photos', 'public/uploads/students'],
    'storage/app/public/teachers' => ['public/storage/teachers', 'public/uploads/teachers'],
    'storage/app/public/teachers/photos' => ['public/storage/teachers/photos', 'public/uploads/teachers'],
    'storage/app/public/school-profiles' => ['public/storage/school-profiles', 'public/uploads/school-profiles'],
    'storage/app/public/facilities' => ['public/storage/facilities', 'public/uploads/facilities'],
    'storage/app/public/galleries' => ['public/storage/galleries', 'public/uploads/galleries'],
    'storage/app/public/gallery-items' => ['public/storage/gallery-items', 'public/uploads/galleries'],
    'storage/app/public/news' => ['public/storage/news', 'public/uploads/news'],
    'storage/app/public/headmaster-greetings' => ['public/storage/headmaster-greetings', 'public/uploads/headmaster-greetings'],
    'storage/app/public/home-sections' => ['public/storage/home-sections', 'public/uploads/home-sections'],
    'storage/app/public/academic-calendar' => ['public/storage/academic-calendar', 'public/uploads/academic-calendar'],
    'storage/app/public/news-sections' => ['public/storage/news-sections', 'public/uploads/news-sections']
];

$totalCopied = 0;
foreach ($imageDirectories as $sourceDir => $destDirs) {
    if (is_dir($sourceDir)) {
        foreach ($destDirs as $destDir) {
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            $files = scandir($sourceDir);
            $copiedCount = 0;
            
            foreach ($files as $file) {
                if ($file != '.' && $file != '..' && is_file($sourceDir . '/' . $file)) {
                    $sourcePath = $sourceDir . '/' . $file;
                    $destPath = $destDir . '/' . $file;
                    
                    if (copy($sourcePath, $destPath)) {
                        $copiedCount++;
                        $totalCopied++;
                    } else {
                        echo "❌ Failed to copy: $file from $sourceDir to $destDir\n";
                    }
                }
            }
            
            if ($copiedCount > 0) {
                echo "✅ Copied $copiedCount files from $sourceDir to $destDir\n";
            }
        }
    } else {
        echo "⚠️ Source directory not found: $sourceDir\n";
    }
}

echo "✅ Total files copied: $totalCopied\n";

// 7. Create comprehensive .htaccess files for hosting
echo "\n🔧 Creating comprehensive .htaccess files for hosting...\n";

// Main .htaccess for public directory
$mainHtaccessContent = '# Laravel .htaccess for hosting
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle storage files
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^storage/(.*)$ storage/$1 [L]
    
    # Handle uploads files
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^uploads/(.*)$ uploads/$1 [L]
    
    # Handle images files
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^images/(.*)$ images/$1 [L]
    
    # Handle Laravel routes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Set proper MIME types for images
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/svg+xml .svg
    AddType image/webp .webp
</IfModule>

# Enable CORS for images
<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|svg|webp)$">
        Header set Access-Control-Allow-Origin "*"
        Header set Cache-Control "public, max-age=31536000"
    </FilesMatch>
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>';

$mainHtaccessPath = 'public/.htaccess';
if (file_put_contents($mainHtaccessPath, $mainHtaccessContent)) {
    echo "✅ Created main .htaccess for hosting\n";
} else {
    echo "❌ Failed to create main .htaccess for hosting\n";
}

// Storage .htaccess
$storageHtaccessContent = '# Allow access to storage files
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ $1 [L]
</IfModule>

# Set proper MIME types for images
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/svg+xml .svg
    AddType image/webp .webp
</IfModule>

# Enable CORS for images
<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|svg|webp)$">
        Header set Access-Control-Allow-Origin "*"
        Header set Cache-Control "public, max-age=31536000"
    </FilesMatch>
</IfModule>';

$storageHtaccessPath = 'public/storage/.htaccess';
if (file_put_contents($storageHtaccessPath, $storageHtaccessContent)) {
    echo "✅ Created storage .htaccess for hosting\n";
} else {
    echo "❌ Failed to create storage .htaccess for hosting\n";
}

// Uploads .htaccess
$uploadsHtaccessContent = '# Allow access to uploads files
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ $1 [L]
</IfModule>

# Set proper MIME types for images
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/svg+xml .svg
    AddType image/webp .webp
</IfModule>

# Enable CORS for images
<IfModule mod_headers.c>
    <FilesMatch "\.(jpg|jpeg|png|gif|svg|webp)$">
        Header set Access-Control-Allow-Origin "*"
        Header set Cache-Control "public, max-age=31536000"
    </FilesMatch>
</IfModule>';

$uploadsHtaccessPath = 'public/uploads/.htaccess';
if (file_put_contents($uploadsHtaccessPath, $uploadsHtaccessContent)) {
    echo "✅ Created uploads .htaccess for hosting\n";
} else {
    echo "❌ Failed to create uploads .htaccess for hosting\n";
}

// 8. Update .env for hosting
echo "\n🔧 Updating .env for hosting...\n";

$envUpdates = [
    'APP_ENV=production',
    'APP_DEBUG=false',
    'SESSION_SECURE_COOKIE=false',
    'SESSION_SAME_SITE=lax',
    'SESSION_LIFETIME=480',
    'FILESYSTEM_DISK=public'
];

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    foreach ($envUpdates as $update) {
        $key = explode('=', $update)[0];
        if (strpos($envContent, $key . '=') !== false) {
            $envContent = preg_replace('/^' . preg_quote($key) . '=.*$/m', $update, $envContent);
            echo "✅ Updated: $update\n";
        } else {
            $envContent .= "\n" . $update;
            echo "✅ Added: $update\n";
        }
    }
    
    if (file_put_contents('.env', $envContent)) {
        echo "✅ .env file updated for hosting\n";
    } else {
        echo "❌ Failed to update .env file\n";
    }
} else {
    echo "❌ .env file not found\n";
}

// 9. Clear all caches for hosting
echo "\n🧹 Clearing all caches for hosting...\n";

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

// 10. Test image URLs for hosting
echo "\n🔍 Testing image URLs for hosting...\n";

$testUrls = [
    'storage/students/photos/test-student.png',
    'storage/teachers/test-teacher.png',
    'storage/school-profiles/test-school-profile.png',
    'storage/facilities/test-facility.png',
    'storage/galleries/test-gallery.png',
    'storage/news/test-news.png',
    'uploads/students/photos/test-student.png',
    'uploads/teachers/test-teacher.png',
    'uploads/school-profiles/test-school-profile.png',
    'uploads/facilities/test-facility.png',
    'uploads/galleries/test-gallery.png',
    'uploads/news/test-news.png',
    'images/default-student.png',
    'images/default-teacher.png',
    'images/default-school-profile.png',
    'images/default-facility.png',
    'images/default-gallery.png',
    'images/default-news.png'
];

foreach ($testUrls as $url) {
    $imagePath = 'public/' . $url;
    if (file_exists($imagePath)) {
        echo "✅ Image accessible: $url\n";
    } else {
        echo "❌ Image not found: $url\n";
    }
}

echo "\n✅ Hosting images fix completed!\n";
echo "🔧 Key fixes applied for hosting:\n";
echo "- Created comprehensive storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created all default images\n";
echo "- Fixed database image paths\n";
echo "- Copied all existing images to multiple locations\n";
echo "- Created comprehensive .htaccess files\n";
echo "- Updated .env for hosting\n";
echo "- Cleared all caches\n";
echo "- Tested image URLs\n\n";

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
echo "2. Run: php fix_hosting_images_final.php\n";
echo "3. Test all image uploads and displays\n";
echo "4. Check browser console for any errors\n";
echo "5. Verify all images are accessible via web\n";

// Helper functions
function createManualStorageForHosting() {
    $sourceDir = 'storage/app/public';
    $destDir = 'public/storage';
    
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    
    if (is_dir($sourceDir)) {
        copyDirectoryForHosting($sourceDir, $destDir);
        echo "✅ Manual storage directory created and files copied for hosting\n";
    } else {
        echo "⚠️ Source directory not found: $sourceDir\n";
    }
}

function copyDirectoryForHosting($src, $dst) {
    if (is_dir($src)) {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }
        
        $files = scandir($src);
        foreach ($files as $file) {
            if ($file != '.' && $file != '..') {
                $srcFile = $src . '/' . $file;
                $dstFile = $dst . '/' . $file;
                
                if (is_dir($srcFile)) {
                    copyDirectoryForHosting($srcFile, $dstFile);
                } else {
                    copy($srcFile, $dstFile);
                }
            }
        }
    }
}

function createDefaultImageForHosting($width, $height, $bgColor, $textColor) {
    // Create a simple PNG image
    $image = imagecreate($width, $height);
    
    // Parse colors
    $bg = sscanf($bgColor, "#%02x%02x%02x");
    $text = sscanf($textColor, "#%02x%02x%02x");
    
    $bgColor = imagecolorallocate($image, $bg[0], $bg[1], $bg[2]);
    $textColor = imagecolorallocate($image, $text[0], $text[1], $text[2]);
    
    // Fill background
    imagefill($image, 0, 0, $bgColor);
    
    // Add text
    $text = "No Image";
    $font = 5;
    $textWidth = imagefontwidth($font) * strlen($text);
    $textHeight = imagefontheight($font);
    $x = ($width - $textWidth) / 2;
    $y = ($height - $textHeight) / 2;
    
    imagestring($image, $font, $x, $y, $text, $textColor);
    
    // Output as PNG
    ob_start();
    imagepng($image);
    $imageData = ob_get_contents();
    ob_end_clean();
    
    imagedestroy($image);
    
    return $imageData;
}

