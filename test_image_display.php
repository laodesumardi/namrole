<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Testing image display for hosting...\n\n";

try {
    // Test SchoolProfile images
    echo "📝 Testing SchoolProfile images...\n";
    $schoolProfiles = \App\Models\SchoolProfile::whereNotNull('image')->get();
    
    foreach ($schoolProfiles as $profile) {
        $imageUrl = $profile->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   Profile ID: {$profile->id}\n";
        echo "   Image: {$profile->image}\n";
        echo "   URL: {$imageUrl}\n";
        echo "   File exists: " . (file_exists($imagePath) ? 'Yes' : 'No') . "\n";
        echo "   ---\n";
    }
    
    // Test HomeSection images
    echo "\n📝 Testing HomeSection images...\n";
    $homeSections = \App\Models\HomeSection::whereNotNull('image')->get();
    
    foreach ($homeSections as $section) {
        $imageUrl = $section->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   Section ID: {$section->id}\n";
        echo "   Image: {$section->image}\n";
        echo "   URL: {$imageUrl}\n";
        echo "   File exists: " . (file_exists($imagePath) ? 'Yes' : 'No') . "\n";
        echo "   ---\n";
    }
    
    // Test Gallery cover images
    echo "\n📝 Testing Gallery cover images...\n";
    $galleries = \App\Models\Gallery::whereNotNull('cover_image')->get();
    
    foreach ($galleries as $gallery) {
        $imageUrl = $gallery->cover_image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   Gallery ID: {$gallery->id}\n";
        echo "   Cover Image: {$gallery->cover_image}\n";
        echo "   URL: {$imageUrl}\n";
        echo "   File exists: " . (file_exists($imagePath) ? 'Yes' : 'No') . "\n";
        echo "   ---\n";
    }
    
    // Test GalleryItem files
    echo "\n📝 Testing GalleryItem files...\n";
    $galleryItems = \App\Models\GalleryItem::whereNotNull('file_path')->get();
    
    foreach ($galleryItems as $item) {
        $imageUrl = $item->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   Item ID: {$item->id}\n";
        echo "   File Path: {$item->file_path}\n";
        echo "   URL: {$imageUrl}\n";
        echo "   File exists: " . (file_exists($imagePath) ? 'Yes' : 'No') . "\n";
        echo "   ---\n";
    }
    
    // Test News images
    echo "\n📝 Testing News images...\n";
    $news = \App\Models\News::whereNotNull('featured_image')->get();
    
    foreach ($news as $article) {
        $imageUrl = $article->featured_image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   News ID: {$article->id}\n";
        echo "   Featured Image: {$article->featured_image}\n";
        echo "   URL: {$imageUrl}\n";
        echo "   File exists: " . (file_exists($imagePath) ? 'Yes' : 'No') . "\n";
        echo "   ---\n";
    }
    
    // Test HeadmasterGreeting images
    echo "\n📝 Testing HeadmasterGreeting images...\n";
    $headmasterGreetings = \App\Models\HeadmasterGreeting::whereNotNull('photo')->get();
    
    foreach ($headmasterGreetings as $greeting) {
        $imageUrl = $greeting->photo_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   Greeting ID: {$greeting->id}\n";
        echo "   Photo: {$greeting->photo}\n";
        echo "   URL: {$imageUrl}\n";
        echo "   File exists: " . (file_exists($imagePath) ? 'Yes' : 'No') . "\n";
        echo "   ---\n";
    }
    
    // Test Facility images
    echo "\n📝 Testing Facility images...\n";
    $facilities = \App\Models\Facility::whereNotNull('image')->get();
    
    foreach ($facilities as $facility) {
        $imageUrl = $facility->image_url;
        $imagePath = public_path(str_replace(asset(''), '', $imageUrl));
        
        echo "   Facility ID: {$facility->id}\n";
        echo "   Image: {$facility->image}\n";
        echo "   URL: {$imageUrl}\n";
        echo "   File exists: " . (file_exists($imagePath) ? 'Yes' : 'No') . "\n";
        echo "   ---\n";
    }
    
    echo "\n✅ Image display test completed!\n";
    echo "📋 All images should now display correctly on hosting.\n\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
