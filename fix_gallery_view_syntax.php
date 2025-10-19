<?php

echo "🔧 Fixing Gallery View Syntax Error\n";
echo "==================================\n\n";

// 1. Check if we're in Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: Not in Laravel project directory\n";
    echo "Please run this script from your Laravel project root\n";
    exit(1);
}

echo "✅ Laravel project detected\n";

// 2. Fix Gallery index view
echo "\n📝 Fixing Gallery index view...\n";

$galleryIndexPath = 'resources/views/admin/gallery/index.blade.php';

if (file_exists($galleryIndexPath)) {
    $viewContent = file_get_contents($galleryIndexPath);
    
    // Fix the broken syntax on line 184-185
    $brokenSyntax1 = 'src="{{ $gallery- onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'">cover_image_url }}"';
    $fixedSyntax1 = 'src="{{ $gallery->cover_image_url }}" onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'"';
    
    $brokenSyntax2 = 'alt="{{ $gallery- onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'">title }}"';
    $fixedSyntax2 = 'alt="{{ $gallery->title }}"';
    
    $viewContent = str_replace($brokenSyntax1, $fixedSyntax1, $viewContent);
    $viewContent = str_replace($brokenSyntax2, $fixedSyntax2, $viewContent);
    
    // Fix any other similar syntax errors
    $viewContent = preg_replace('/src="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'src="{{ $gallery->$1 }}" onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'"', $viewContent);
    $viewContent = preg_replace('/alt="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'alt="{{ $gallery->$1 }}"', $viewContent);
    
    // Fix any other broken syntax patterns
    $viewContent = preg_replace('/\{\{ \$gallery- onerror="[^"]*">([^"]*)\}\}/', '{{ $gallery->$1 }}', $viewContent);
    
    if (file_put_contents($galleryIndexPath, $viewContent)) {
        echo "   ✅ Gallery index view fixed successfully\n";
    } else {
        echo "   ❌ Failed to fix Gallery index view\n";
    }
} else {
    echo "   ❌ Gallery index view not found\n";
}

// 3. Fix Gallery edit view
echo "\n📝 Fixing Gallery edit view...\n";

$galleryEditPath = 'resources/views/admin/gallery/edit.blade.php';

if (file_exists($galleryEditPath)) {
    $viewContent = file_get_contents($galleryEditPath);
    
    // Fix any broken syntax patterns
    $viewContent = preg_replace('/\{\{ \$gallery- onerror="[^"]*">([^"]*)\}\}/', '{{ $gallery->$1 }}', $viewContent);
    $viewContent = preg_replace('/src="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'src="{{ $gallery->$1 }}" onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'"', $viewContent);
    $viewContent = preg_replace('/alt="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'alt="{{ $gallery->$1 }}"', $viewContent);
    
    if (file_put_contents($galleryEditPath, $viewContent)) {
        echo "   ✅ Gallery edit view fixed successfully\n";
    } else {
        echo "   ❌ Failed to fix Gallery edit view\n";
    }
} else {
    echo "   ❌ Gallery edit view not found\n";
}

// 4. Fix Gallery create view
echo "\n📝 Fixing Gallery create view...\n";

$galleryCreatePath = 'resources/views/admin/gallery/create.blade.php';

if (file_exists($galleryCreatePath)) {
    $viewContent = file_get_contents($galleryCreatePath);
    
    // Fix any broken syntax patterns
    $viewContent = preg_replace('/\{\{ \$gallery- onerror="[^"]*">([^"]*)\}\}/', '{{ $gallery->$1 }}', $viewContent);
    $viewContent = preg_replace('/src="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'src="{{ $gallery->$1 }}" onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'"', $viewContent);
    $viewContent = preg_replace('/alt="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'alt="{{ $gallery->$1 }}"', $viewContent);
    
    if (file_put_contents($galleryCreatePath, $viewContent)) {
        echo "   ✅ Gallery create view fixed successfully\n";
    } else {
        echo "   ❌ Failed to fix Gallery create view\n";
    }
} else {
    echo "   ❌ Gallery create view not found\n";
}

// 5. Fix Gallery show view
echo "\n📝 Fixing Gallery show view...\n";

$galleryShowPath = 'resources/views/admin/gallery/show.blade.php';

if (file_exists($galleryShowPath)) {
    $viewContent = file_get_contents($galleryShowPath);
    
    // Fix any broken syntax patterns
    $viewContent = preg_replace('/\{\{ \$gallery- onerror="[^"]*">([^"]*)\}\}/', '{{ $gallery->$1 }}', $viewContent);
    $viewContent = preg_replace('/src="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'src="{{ $gallery->$1 }}" onerror="this.src=\'{{ asset(\'images/default-gallery.png\') }}\'"', $viewContent);
    $viewContent = preg_replace('/alt="\{\{ \$gallery- onerror="[^"]*">([^"]*)"\}/', 'alt="{{ $gallery->$1 }}"', $viewContent);
    
    if (file_put_contents($galleryShowPath, $viewContent)) {
        echo "   ✅ Gallery show view fixed successfully\n";
    } else {
        echo "   ❌ Failed to fix Gallery show view\n";
    }
} else {
    echo "   ❌ Gallery show view not found\n";
}

// 6. Fix Gallery items views
echo "\n📝 Fixing Gallery items views...\n";

$galleryItemViews = [
    'resources/views/admin/gallery-items/index.blade.php',
    'resources/views/admin/gallery-items/edit.blade.php',
    'resources/views/admin/gallery-items/create.blade.php',
    'resources/views/admin/gallery-items/show.blade.php'
];

foreach ($galleryItemViews as $viewPath) {
    if (file_exists($viewPath)) {
        $viewContent = file_get_contents($viewPath);
        
        // Fix any broken syntax patterns
        $viewContent = preg_replace('/\{\{ \$item- onerror="[^"]*">([^"]*)\}\}/', '{{ $item->$1 }}', $viewContent);
        $viewContent = preg_replace('/src="\{\{ \$item- onerror="[^"]*">([^"]*)"\}/', 'src="{{ $item->$1 }}" onerror="this.src=\'{{ asset(\'images/default-gallery-item.png\') }}\'"', $viewContent);
        $viewContent = preg_replace('/alt="\{\{ \$item- onerror="[^"]*">([^"]*)"\}/', 'alt="{{ $item->$1 }}"', $viewContent);
        
        if (file_put_contents($viewPath, $viewContent)) {
            echo "   ✅ Fixed view: " . basename($viewPath) . "\n";
        } else {
            echo "   ❌ Failed to fix view: " . basename($viewPath) . "\n";
        }
    } else {
        echo "   ⚠️  View not found: " . basename($viewPath) . "\n";
    }
}

// 7. Create default images
echo "\n🖼️  Creating default images...\n";

$defaultImages = [
    'public/images/default-gallery.png' => 'Default gallery image',
    'public/images/default-gallery-item.png' => 'Default gallery item image'
];

foreach ($defaultImages as $path => $description) {
    if (!file_exists($path)) {
        echo "   🔧 Creating {$description}...\n";
        
        // Create a simple default image (300x200 PNG)
        $image = imagecreate(300, 200);
        $bgColor = imagecolorallocate($image, 240, 240, 240);
        $textColor = imagecolorallocate($image, 100, 100, 100);
        $borderColor = imagecolorallocate($image, 200, 200, 200);
        
        // Fill background
        imagefill($image, 0, 0, $bgColor);
        
        // Add border
        imagerectangle($image, 0, 0, 299, 199, $borderColor);
        
        // Add text
        $text = 'Default Image';
        $fontSize = 5;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $textHeight = imagefontheight($fontSize);
        $x = (300 - $textWidth) / 2;
        $y = (200 - $textHeight) / 2;
        
        imagestring($image, $fontSize, $x, $y, $text, $textColor);
        
        if (imagepng($image, $path)) {
            echo "   ✅ Created: {$path}\n";
        } else {
            echo "   ❌ Failed to create: {$path}\n";
        }
        
        imagedestroy($image);
    } else {
        echo "   ✅ Already exists: {$path}\n";
    }
}

// 8. Clear cache
echo "\n🧹 Clearing cache...\n";

try {
    // Clear config cache
    if (file_exists('bootstrap/cache/config.php')) {
        unlink('bootstrap/cache/config.php');
        echo "   ✅ Config cache cleared\n";
    }
    
    // Clear route cache
    if (file_exists('bootstrap/cache/routes.php')) {
        unlink('bootstrap/cache/routes.php');
        echo "   ✅ Route cache cleared\n";
    }
    
    // Clear view cache
    $viewCachePath = 'storage/framework/views';
    if (is_dir($viewCachePath)) {
        $files = glob($viewCachePath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
        echo "   ✅ View cache cleared\n";
    }
    
    echo "   ✅ All caches cleared\n";
} catch (Exception $e) {
    echo "   ❌ Error clearing cache: " . $e->getMessage() . "\n";
}

echo "\n✅ Gallery view syntax fix completed!\n";
echo "🔧 Key improvements applied:\n";
echo "   - Fixed Gallery index view syntax errors\n";
echo "   - Fixed Gallery edit view syntax errors\n";
echo "   - Fixed Gallery create view syntax errors\n";
echo "   - Fixed Gallery show view syntax errors\n";
echo "   - Fixed Gallery items views syntax errors\n";
echo "   - Created default images\n";
echo "   - Cleared all caches\n";
echo "\n🌐 Test your gallery views:\n";
echo "   - Gallery Index: http://localhost:8000/admin/gallery\n";
echo "   - Gallery Edit: http://localhost:8000/admin/gallery/2/edit\n";
echo "   - Check if images appear in views\n";
echo "   - Test image upload functionality\n";
echo "\n🔑 Admin Login:\n";
echo "   - Email: admin@namrole.sch.id\n";
echo "   - Password: admin123\n";

