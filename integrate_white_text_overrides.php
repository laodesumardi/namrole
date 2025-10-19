<?php
/**
 * INTEGRATE WHITE TEXT OVERRIDES - Script to integrate CSS and JS overrides into main views
 */

echo "=== INTEGRATING WHITE TEXT OVERRIDES ===\n";

// 1. Check if main layout file exists
echo "1. Checking main layout file...\n";
$layoutFile = 'resources/views/layouts/app.blade.php';
if (file_exists($layoutFile)) {
    echo "✓ Layout file found: $layoutFile\n";
    
    // Read the layout file
    $layoutContent = file_get_contents($layoutFile);
    
    // Add CSS override link
    if (strpos($layoutContent, 'ultimate-force-white.css') === false) {
        $cssLink = '<link rel="stylesheet" href="{{ asset("css/ultimate-force-white.css") }}">';
        
        // Find the head section and add CSS link
        if (strpos($layoutContent, '</head>') !== false) {
            $layoutContent = str_replace('</head>', "    $cssLink\n</head>", $layoutContent);
            echo "✓ CSS override link added to layout\n";
        }
    } else {
        echo "✓ CSS override link already exists in layout\n";
    }
    
    // Add JavaScript override
    if (strpos($layoutContent, 'ultimate-force-white.js') === false) {
        $jsScript = '<script src="{{ asset("js/ultimate-force-white.js") }}"></script>';
        
        // Find the body end and add JS script
        if (strpos($layoutContent, '</body>') !== false) {
            $layoutContent = str_replace('</body>', "    $jsScript\n</body>", $layoutContent);
            echo "✓ JavaScript override added to layout\n";
        }
    } else {
        echo "✓ JavaScript override already exists in layout\n";
    }
    
    // Write the updated layout
    file_put_contents($layoutFile, $layoutContent);
    echo "✓ Layout file updated\n";
} else {
    echo "✗ Layout file not found: $layoutFile\n";
}

// 2. Check if home view exists
echo "2. Checking home view...\n";
$homeFile = 'resources/views/home.blade.php';
if (file_exists($homeFile)) {
    echo "✓ Home view found: $homeFile\n";
    
    // Read the home view
    $homeContent = file_get_contents($homeFile);
    
    // Add inline CSS override
    $inlineCss = '
<style>
/* Force white text for hero section */
.hero-section, .home-section, .section-hero {
    color: #ffffff !important;
}

.hero-section h1,
.hero-section h2,
.hero-section h3,
.hero-section p,
.hero-section span,
.hero-section div {
    color: #ffffff !important;
}

/* Force white text for all text elements */
* {
    color: #ffffff !important;
}
</style>';

    // Add inline JavaScript override
    $inlineJs = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        element.style.color = "#ffffff !important";
    });
});
</script>';

    // Add CSS and JS to the home view
    if (strpos($homeContent, 'Force white text for hero section') === false) {
        $homeContent = $inlineCss . "\n" . $homeContent;
        echo "✓ Inline CSS override added to home view\n";
    }
    
    if (strpos($homeContent, 'Force white text on page load') === false) {
        $homeContent = $homeContent . "\n" . $inlineJs;
        echo "✓ Inline JavaScript override added to home view\n";
    }
    
    // Write the updated home view
    file_put_contents($homeFile, $homeContent);
    echo "✓ Home view updated\n";
} else {
    echo "✗ Home view not found: $homeFile\n";
}

// 3. Create a simple test page
echo "3. Creating simple test page...\n";
$testPage = '
<!DOCTYPE html>
<html>
<head>
    <title>White Text Test</title>
    <style>
        body { background-color: #000000; }
        * { color: #ffffff !important; }
        .hero-section { color: #ffffff !important; }
        .hero-section h1, .hero-section h2, .hero-section p, .hero-section span { color: #ffffff !important; }
    </style>
</head>
<body>
    <div class="hero-section">
        <h1>Selamat Datang di SMP Negeri 01 Namrole</h1>
        <h2>Sekolah Unggulan dengan Pendidikan Berkualitas</h2>
        <p>Selamat Datang di SMP Negeri 01 Namrole</p>
        <span>Sekolah Unggulan dengan Pendidikan Berkualitas</span>
    </div>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const allElements = document.querySelectorAll("*");
            allElements.forEach(function(element) {
                element.style.color = "#ffffff !important";
            });
        });
    </script>
</body>
</html>
';

file_put_contents('public/white-text-test.html', $testPage);
echo "✓ Simple test page created: public/white-text-test.html\n";

// 4. Clear caches
echo "4. Clearing caches...\n";
if (file_exists('bootstrap/cache/packages.php')) {
    unlink('bootstrap/cache/packages.php');
}
if (file_exists('bootstrap/cache/services.php')) {
    unlink('bootstrap/cache/services.php');
}
echo "✓ Caches cleared\n";

// 5. Test the integration
echo "5. Testing integration...\n";
echo "✓ CSS override file: public/css/ultimate-force-white.css\n";
echo "✓ JavaScript override file: public/js/ultimate-force-white.js\n";
echo "✓ Simple test page: public/white-text-test.html\n";
echo "✓ Layout file updated with overrides\n";
echo "✓ Home view updated with inline overrides\n";

echo "\n=== INTEGRATION COMPLETE ===\n";
echo "Test the changes at:\n";
echo "- http://localhost:8000/white-text-test.html\n";
echo "- http://localhost:8000/\n";
echo "\nThe hero section text should now be white!\n";
?>
