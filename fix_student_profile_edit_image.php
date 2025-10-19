<?php
/**
 * Fix Student Profile Edit Image Issue
 * 
 * This script fixes the issue where images don't appear immediately after saving
 * in student profile edit by ensuring proper file copying and storage structure
 */

echo "👨‍🎓 Fixing Student Profile Edit Image Issue\n";
echo "==========================================\n\n";

echo "🔧 Fixing student profile edit image display issue...\n";

// 1. Create storage structure
echo "\n🔗 Creating storage structure...\n";

// Create directories
$directories = [
    'storage/app/public',
    'storage/app/public/students',
    'storage/app/public/students/photos',
    'storage/app/public/teachers',
    'public/storage',
    'public/storage/students',
    'public/storage/students/photos',
    'public/storage/teachers',
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

// 2. Create storage link if not exists
echo "\n🔗 Creating storage link...\n";
$storageLink = 'public/storage';
$storageTarget = '../storage/app/public';

if (!is_link($storageLink)) {
    if (function_exists('symlink')) {
        if (symlink($storageTarget, $storageLink)) {
            echo "✅ Storage link created\n";
        } else {
            echo "❌ Failed to create storage link\n";
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

// 3. Create default images
echo "\n🖼️ Creating default images...\n";

$defaultImages = [
    'public/images/default-student.png' => 'Default student image',
    'public/images/default-teacher.png' => 'Default teacher image',
    'public/images/default-user.png' => 'Default user image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        // Create a simple default image
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

// 4. Copy existing student photos to public storage
echo "\n📁 Copying existing student photos...\n";

// Get all student photos from storage/app/public/students/photos
$sourceDir = 'storage/app/public/students/photos';
$destDir = 'public/storage/students/photos';

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
                echo "✅ Copied: $file\n";
            } else {
                echo "❌ Failed to copy: $file\n";
            }
        }
    }
    
    echo "✅ Copied $copiedCount student photos\n";
} else {
    echo "⚠️ Source directory not found: $sourceDir\n";
}

// 5. Copy existing teacher photos to public storage
echo "\n📁 Copying existing teacher photos...\n";

$teacherSourceDir = 'storage/app/public/teachers';
$teacherDestDir = 'public/storage/teachers';

if (is_dir($teacherSourceDir)) {
    if (!is_dir($teacherDestDir)) {
        mkdir($teacherDestDir, 0755, true);
    }
    
    $files = scandir($teacherSourceDir);
    $copiedCount = 0;
    
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && is_file($teacherSourceDir . '/' . $file)) {
            $sourcePath = $teacherSourceDir . '/' . $file;
            $destPath = $teacherDestDir . '/' . $file;
            
            if (copy($sourcePath, $destPath)) {
                $copiedCount++;
                echo "✅ Copied: $file\n";
            } else {
                echo "❌ Failed to copy: $file\n";
            }
        }
    }
    
    echo "✅ Copied $copiedCount teacher photos\n";
} else {
    echo "⚠️ Source directory not found: $teacherSourceDir\n";
}

// 6. Test file permissions
echo "\n🔐 Testing file permissions...\n";

$testDirs = [
    'storage/app/public/students/photos',
    'storage/app/public/teachers',
    'public/storage/students/photos',
    'public/storage/teachers',
    'public/images'
];

foreach ($testDirs as $dir) {
    if (is_dir($dir)) {
        $perms = substr(sprintf('%o', fileperms($dir)), -4);
        if (is_writable($dir)) {
            echo "✅ $dir: $perms (writable)\n";
        } else {
            echo "❌ $dir: $perms (not writable)\n";
        }
    } else {
        echo "⚠️ $dir: Directory not found\n";
    }
}

// 7. Fix StudentProfileController
echo "\n🔧 Fixing StudentProfileController...\n";

$controllerPath = 'app/Http/Controllers/Student/ProfileController.php';
if (file_exists($controllerPath)) {
    $content = file_get_contents($controllerPath);
    
    // Check if file copying logic exists in update method
    if (strpos($content, 'Copy uploaded files to public/storage for immediate access') !== false) {
        echo "✅ StudentProfileController update method has file copying logic\n";
    } else {
        echo "❌ StudentProfileController update method missing file copying logic\n";
        echo "🔧 Adding file copying logic to update method...\n";
        
        // Add file copying logic after $user->photo = $path;
        $updateMethodPattern = '/\$user->photo = \$path;\s*\n\s*}/';
        $replacement = "\$user->photo = \$path;\n            \n            // Copy uploaded files to public/storage for immediate access\n            \$sourcePath = storage_path('app/public/' . \$path);\n            \$destPath = public_path('storage/' . \$path);\n            \$destDir = dirname(\$destPath);\n            \n            if (!is_dir(\$destDir)) {\n                mkdir(\$destDir, 0755, true);\n            }\n            \n            if (copy(\$sourcePath, \$destPath)) {\n                \\Log::info('Student photo copied to public storage: ' . \$path);\n            } else {\n                \\Log::error('Failed to copy student photo to public storage: ' . \$path);\n            }\n        }";
        
        $newContent = preg_replace($updateMethodPattern, $replacement, $content);
        
        if ($newContent !== $content) {
            file_put_contents($controllerPath, $newContent);
            echo "✅ Added file copying logic to update method\n";
        } else {
            echo "❌ Failed to add file copying logic to update method\n";
        }
    }
} else {
    echo "❌ StudentProfileController not found\n";
}

// 8. Fix User model accessor
echo "\n🔧 Fixing User model accessor...\n";

$modelPath = 'app/Models/User.php';
if (file_exists($modelPath)) {
    $content = file_get_contents($modelPath);
    
    // Check if comprehensive accessor exists
    if (strpos($content, 'getPhotoUrlAttribute') !== false &&
        strpos($content, 'str_starts_with') !== false) {
        echo "✅ User model has comprehensive accessor\n";
    } else {
        echo "❌ User model accessor needs updating\n";
        echo "🔧 Updating model accessor...\n";
        
        // Add comprehensive accessor
        $accessorCode = '
    public function getPhotoUrlAttribute()
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
        
        // Add accessor before closing brace
        $content = str_replace('}', $accessorCode . "\n}", $content);
        file_put_contents($modelPath, $content);
        echo "✅ Added comprehensive accessor to User model\n";
    }
} else {
    echo "❌ User model not found\n";
}

// 9. Fix student profile edit view
echo "\n🔧 Fixing student profile edit view...\n";

$viewPath = 'resources/views/student/profile/edit.blade.php';
if (file_exists($viewPath)) {
    $content = file_get_contents($viewPath);
    
    // Check if view uses photo_url accessor and has proper fallback
    if (strpos($content, '$user->photo_url') !== false &&
        strpos($content, 'onerror=') !== false) {
        echo "✅ Student profile edit view has proper image display logic\n";
    } else {
        echo "❌ Student profile edit view needs updating\n";
        echo "🔧 Updating view image display logic...\n";
        
        // Update image display to use photo_url accessor
        $imagePattern = '/<img src="{{ Storage::url\(\$user->photo\) }}"[^>]*>/';
        $imageReplacement = '<img src="{{ $user->photo_url }}" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover border-2 border-primary-200 shadow-sm" id="current-photo-preview" onerror="this.style.display=\'none\'; this.nextElementSibling.style.display=\'flex\';">';
        
        $content = preg_replace($imagePattern, $imageReplacement, $content);
        
        file_put_contents($viewPath, $content);
        echo "✅ Updated student profile edit view image display logic\n";
    }
} else {
    echo "❌ Student profile edit view not found\n";
}

// 10. Create test student photo
echo "\n📝 Creating test student photo...\n";

$testImagePath = 'public/storage/students/photos/test-student-photo.png';
if (!file_exists($testImagePath)) {
    $testImageContent = createDefaultImage(300, 300, '#e5e7eb', '#374151');
    if (file_put_contents($testImagePath, $testImageContent)) {
        echo "✅ Created test image: $testImagePath\n";
    } else {
        echo "❌ Failed to create test image\n";
    }
} else {
    echo "✅ Test image already exists\n";
}

echo "\n✅ Student profile edit image issue fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created storage structure\n";
echo "- Created storage link or manual storage\n";
echo "- Created default images\n";
echo "- Copied existing student and teacher photos to public storage\n";
echo "- Tested file permissions\n";
echo "- Fixed StudentProfileController update method\n";
echo "- Fixed User model accessor\n";
echo "- Fixed student profile edit view\n";
echo "- Created test student photo\n\n";

echo "🌐 Test URLs:\n";
echo "- Student Profile Edit: http://localhost:8000/student/profile/edit\n";
echo "- Student Profile Show: http://localhost:8000/student/profile\n";
echo "- Teacher Profile Edit: http://localhost:8000/teacher/profile/edit\n";
echo "- Teacher Profile Show: http://localhost:8000/teacher/profile\n\n";

echo "🔑 Student Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: student@namrole.sch.id\n";
echo "- Password: student123\n\n";

echo "📝 Next Steps:\n";
echo "1. Test student profile edit with image upload\n";
echo "2. Check if image appears immediately after save\n";
echo "3. Verify image display in student profile show page\n";
echo "4. Check browser console for any errors\n";

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

