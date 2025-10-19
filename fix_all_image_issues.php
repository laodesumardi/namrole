<?php
/**
 * Fix All Image Issues
 * 
 * This script fixes all image display issues comprehensively
 */

echo "🔧 Fixing All Image Issues\n";
echo "==========================\n\n";

echo "🔧 Fixing all image display issues...\n";

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

// 2. Create storage structure
echo "\n🔗 Creating storage structure...\n";

$directories = [
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
    'public/images'
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

// 3. Create storage link or manual storage
echo "\n🔗 Creating storage link or manual storage...\n";
$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

if (!is_link($storageLink)) {
    if (function_exists('symlink')) {
        try {
            if (symlink($storageTarget, $storageLink)) {
                echo "✅ Storage link created\n";
            } else {
                echo "❌ Failed to create storage link\n";
                echo "🔧 Creating manual storage directory...\n";
                createManualStorage();
            }
        } catch (Exception $e) {
            echo "❌ Failed to create storage link: " . $e->getMessage() . "\n";
            echo "🔧 Creating manual storage directory...\n";
            createManualStorage();
        }
    } else {
        echo "⚠️ symlink() function not available\n";
        echo "🔧 Creating manual storage directory...\n";
        createManualStorage();
    }
} else {
    echo "✅ Storage link already exists\n";
}

// 4. Create all default images
echo "\n🖼️ Creating all default images...\n";

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
    'public/images/default-logo.png' => 'Default logo image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        $imageContent = createDefaultImage(200, 200, '#f3f4f6', '#6b7280');
        if (file_put_contents($path, $imageContent)) {
            echo "✅ Created: $path\n";
        } else {
            echo "❌ Failed to create: $path\n";
        }
    } else {
        echo "✅ Default image exists: $path\n";
    }
}

// 5. Fix database image paths
echo "\n🔧 Fixing database image paths...\n";

try {
    // Fix users with photos
    $usersWithPhotos = \App\Models\User::whereNotNull('photo')->get();
    echo "✅ Found " . $usersWithPhotos->count() . " users with photos\n";
    
    foreach ($usersWithPhotos as $user) {
        echo "📝 Fixing User ID: {$user->id}, Name: {$user->name}\n";
        echo "   Current Photo: {$user->photo}\n";
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $user->photo);
        $publicPath = public_path('storage/' . $user->photo);
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage if not exists
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
            } else {
                echo "   ✅ Public file already exists\n";
            }
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
        
        // Fix image path if it has storage/ prefix
        if (str_starts_with($profile->image, 'storage/')) {
            $newImagePath = str_replace('storage/', '', $profile->image);
            $profile->image = $newImagePath;
            $profile->save();
            echo "   🔧 Fixed image path: {$newImagePath}\n";
        }
        
        // Check if file exists in storage
        $storagePath = storage_path('app/public/' . $profile->image);
        $publicPath = public_path('storage/' . $profile->image);
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage if not exists
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
            } else {
                echo "   ✅ Public file already exists\n";
            }
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
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage if not exists
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
            } else {
                echo "   ✅ Public file already exists\n";
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
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage if not exists
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
            } else {
                echo "   ✅ Public file already exists\n";
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
        
        if (file_exists($storagePath)) {
            echo "   ✅ Storage file exists\n";
            
            // Copy to public storage if not exists
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
            } else {
                echo "   ✅ Public file already exists\n";
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

// 6. Copy all existing images to public storage
echo "\n📁 Copying all existing images to public storage...\n";

$imageDirectories = [
    'storage/app/public/students/photos' => 'public/storage/students/photos',
    'storage/app/public/teachers' => 'public/storage/teachers',
    'storage/app/public/teachers/photos' => 'public/storage/teachers/photos',
    'storage/app/public/school-profiles' => 'public/storage/school-profiles',
    'storage/app/public/facilities' => 'public/storage/facilities',
    'storage/app/public/galleries' => 'public/storage/galleries',
    'storage/app/public/gallery-items' => 'public/storage/gallery-items',
    'storage/app/public/news' => 'public/storage/news',
    'storage/app/public/headmaster-greetings' => 'public/storage/headmaster-greetings',
    'storage/app/public/home-sections' => 'public/storage/home-sections',
    'storage/app/public/academic-calendar' => 'public/storage/academic-calendar',
    'storage/app/public/news-sections' => 'public/storage/news-sections'
];

$totalCopied = 0;
foreach ($imageDirectories as $sourceDir => $destDir) {
    if (is_dir($sourceDir)) {
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
                    echo "❌ Failed to copy: $file from $sourceDir\n";
                }
            }
        }
        
        if ($copiedCount > 0) {
            echo "✅ Copied $copiedCount files from $sourceDir\n";
        }
    } else {
        echo "⚠️ Source directory not found: $sourceDir\n";
    }
}

echo "✅ Total files copied: $totalCopied\n";

// 7. Create .htaccess for public storage
echo "\n🔧 Creating .htaccess for public storage...\n";

$htaccessContent = '# Allow access to storage files
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^storage/(.*)$ storage/$1 [L]
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

$htaccessPath = 'public/storage/.htaccess';
if (file_put_contents($htaccessPath, $htaccessContent)) {
    echo "✅ Created .htaccess for public storage\n";
} else {
    echo "❌ Failed to create .htaccess for public storage\n";
}

// 8. Test image URLs
echo "\n🔍 Testing image URLs...\n";

$testUrls = [
    'storage/students/photos/test-student.png',
    'storage/teachers/test-teacher.png',
    'storage/school-profiles/test-school-profile.png',
    'storage/facilities/test-facility.png',
    'storage/galleries/test-gallery.png',
    'storage/news/test-news.png',
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

echo "\n✅ All image issues fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created comprehensive storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created all default images\n";
echo "- Fixed database image paths\n";
echo "- Copied all existing images to public storage\n";
echo "- Created .htaccess for public storage\n";
echo "- Tested image URLs\n\n";

echo "🌐 Test URLs:\n";
echo "- Student Profile: http://localhost:8000/student/profile/edit\n";
echo "- Teacher Profile: http://localhost:8000/teacher/profile/edit\n";
echo "- Admin Gallery: http://localhost:8000/admin/gallery\n";
echo "- Admin News: http://localhost:8000/admin/news\n";
echo "- Admin Facilities: http://localhost:8000/admin/facilities\n";
echo "- Admin School Profile: http://localhost:8000/admin/school-profile\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test all image uploads and displays\n";
echo "2. Check browser console for any errors\n";
echo "3. Verify all images are accessible via web\n";

// Helper functions
function createManualStorage() {
    $sourceDir = 'storage/app/public';
    $destDir = 'public/storage';
    
    if (!is_dir($destDir)) {
        mkdir($destDir, 0755, true);
    }
    
    if (is_dir($sourceDir)) {
        copyDirectory($sourceDir, $destDir);
        echo "✅ Manual storage directory created and files copied\n";
    } else {
        echo "⚠️ Source directory not found: $sourceDir\n";
    }
}

function copyDirectory($src, $dst) {
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
                    copyDirectory($srcFile, $dstFile);
                } else {
                    copy($srcFile, $dstFile);
                }
            }
        }
    }
}

function createDefaultImage($width, $height, $bgColor, $textColor) {
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
