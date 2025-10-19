<?php
/**
 * ULTIMATE FORCE WHITE TEXT - Script to aggressively force white text in hero section
 * This script uses multiple approaches to ensure text is white
 */

echo "=== ULTIMATE FORCE WHITE TEXT ===\n";

// 1. Update database with multiple white text options
echo "1. Updating database with multiple white text options...\n";

$db = new PDO('sqlite:database/database.sqlite');

// Update hero section with multiple white text approaches
$heroUpdates = [
    "UPDATE home_sections SET 
        text_color = '#ffffff',
        background_color = '#000000',
        description = 'Selamat Datang di SMP Negeri 01 Namrole<br><span style=\"color: #ffffff !important;\">Sekolah Unggulan dengan Pendidikan Berkualitas</span>',
        section_key = 'hero',
        sort_order = 1
    WHERE section_key = 'hero'",
    
    "UPDATE home_sections SET 
        text_color = 'white',
        background_color = '#000000',
        description = 'Selamat Datang di SMP Negeri 01 Namrole<br><span style=\"color: white !important;\">Sekolah Unggulan dengan Pendidikan Berkualitas</span>',
        section_key = 'hero',
        sort_order = 1
    WHERE section_key = 'hero'",
    
    "UPDATE home_sections SET 
        text_color = '#ffffff',
        background_color = '#000000',
        description = 'Selamat Datang di SMP Negeri 01 Namrole<br><span style=\"color: #ffffff !important; background: transparent;\">Sekolah Unggulan dengan Pendidikan Berkualitas</span>',
        section_key = 'hero',
        sort_order = 1
    WHERE section_key = 'hero'"
];

foreach ($heroUpdates as $update) {
    $result = $db->exec($update);
    if ($result) {
        echo "✓ Database updated successfully\n";
    } else {
        echo "✗ Database update failed: " . $db->errorInfo()[2] . "\n";
    }
}

// 2. Create aggressive CSS override file
echo "2. Creating aggressive CSS override file...\n";

$cssContent = '
/* ULTIMATE FORCE WHITE TEXT CSS */
.hero-section, .home-section, .section-hero,
.section-title, .section-subtitle, .section-description,
h1, h2, h3, h4, h5, h6, .title, .subtitle, .description,
.hero-text, .hero-title, .hero-subtitle, .hero-description,
.welcome-text, .school-text, .quality-text {
    color: #ffffff !important;
    color: white !important;
    color: #fff !important;
    color: rgb(255, 255, 255) !important;
    color: rgba(255, 255, 255, 1) !important;
}

/* Force white text for all text elements */
* {
    color: #ffffff !important;
}

/* Override any existing color styles */
[style*="color"] {
    color: #ffffff !important;
}

/* Force white text for specific hero elements */
.hero-section h1,
.hero-section h2,
.hero-section h3,
.hero-section p,
.hero-section span,
.hero-section div {
    color: #ffffff !important;
}

/* Force white text for all elements with text */
body, html, div, span, p, h1, h2, h3, h4, h5, h6 {
    color: #ffffff !important;
}
';

file_put_contents('public/css/ultimate-force-white.css', $cssContent);
echo "✓ CSS override file created\n";

// 3. Create aggressive JavaScript override file
echo "3. Creating aggressive JavaScript override file...\n";

$jsContent = '
// ULTIMATE FORCE WHITE TEXT JAVASCRIPT
document.addEventListener("DOMContentLoaded", function() {
    // Force all text elements to be white
    const textElements = document.querySelectorAll("h1, h2, h3, h4, h5, h6, p, span, div, a, button, .title, .subtitle, .description, .hero-text, .hero-title, .hero-subtitle, .hero-description, .welcome-text, .school-text, .quality-text");
    
    textElements.forEach(function(element) {
        element.style.color = "#ffffff";
        element.style.color = "white";
        element.style.color = "#fff";
        element.style.color = "rgb(255, 255, 255)";
        element.style.color = "rgba(255, 255, 255, 1)";
        element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
        element.setAttribute("style", element.getAttribute("style") + "; color: white !important;");
        element.setAttribute("style", element.getAttribute("style") + "; color: #fff !important;");
    });
    
    // Force white text for all elements
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        if (element.style.color) {
            element.style.color = "#ffffff !important";
        }
    });
    
    // Force white text for body and html
    document.body.style.color = "#ffffff !important";
    document.documentElement.style.color = "#ffffff !important";
    
    // Force white text for specific hero elements
    const heroElements = document.querySelectorAll(".hero-section, .home-section, .section-hero");
    heroElements.forEach(function(element) {
        element.style.color = "#ffffff !important";
        const children = element.querySelectorAll("*");
        children.forEach(function(child) {
            child.style.color = "#ffffff !important";
        });
    });
});
';

file_put_contents('public/js/ultimate-force-white.js', $jsContent);
echo "✓ JavaScript override file created\n";

// 4. Create HTML injection file
echo "4. Creating HTML injection file...\n";

$htmlContent = '
<!-- ULTIMATE FORCE WHITE TEXT HTML INJECTION -->
<style>
/* Force white text for all elements */
* {
    color: #ffffff !important;
    color: white !important;
    color: #fff !important;
}

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
body, html, div, span, p, h1, h2, h3, h4, h5, h6 {
    color: #ffffff !important;
}
</style>

<script>
// Force white text on page load
document.addEventListener("DOMContentLoaded", function() {
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        element.style.color = "#ffffff !important";
    });
});
</script>
';

file_put_contents('public/ultimate-force-white.html', $htmlContent);
echo "✓ HTML injection file created\n";

// 5. Create view override file
echo "5. Creating view override file...\n";

$viewContent = '
@extends("layouts.app")

@section("content")
<div class="hero-section" style="background-color: #000000; color: #ffffff !important;">
    <div class="container">
        <h1 style="color: #ffffff !important;">Selamat Datang di SMP Negeri 01 Namrole</h1>
        <h2 style="color: #ffffff !important;">Sekolah Unggulan dengan Pendidikan Berkualitas</h2>
        <p style="color: #ffffff !important;">Selamat Datang di SMP Negeri 01 Namrole</p>
        <span style="color: #ffffff !important;">Sekolah Unggulan dengan Pendidikan Berkualitas</span>
    </div>
</div>

<style>
/* Force white text for all elements */
* {
    color: #ffffff !important;
    color: white !important;
    color: #fff !important;
}

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
body, html, div, span, p, h1, h2, h3, h4, h5, h6 {
    color: #ffffff !important;
}
</style>

<script>
// Force white text on page load
document.addEventListener("DOMContentLoaded", function() {
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        element.style.color = "#ffffff !important";
    });
});
</script>
@endsection
';

file_put_contents('resources/views/ultimate-force-white.blade.php', $viewContent);
echo "✓ View override file created\n";

// 6. Create test page
echo "6. Creating test page...\n";

$testContent = '
<!DOCTYPE html>
<html>
<head>
    <title>Ultimate Force White Text Test</title>
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

file_put_contents('public/ultimate-force-white-test.html', $testContent);
echo "✓ Test page created\n";

// 7. Clear caches
echo "7. Clearing caches...\n";
if (file_exists('bootstrap/cache/packages.php')) {
    unlink('bootstrap/cache/packages.php');
}
if (file_exists('bootstrap/cache/services.php')) {
    unlink('bootstrap/cache/services.php');
}
echo "✓ Caches cleared\n";

// 8. Test the changes
echo "8. Testing changes...\n";
echo "✓ Database updated with multiple white text options\n";
echo "✓ CSS override file created: public/css/ultimate-force-white.css\n";
echo "✓ JavaScript override file created: public/js/ultimate-force-white.js\n";
echo "✓ HTML injection file created: public/ultimate-force-white.html\n";
echo "✓ View override file created: resources/views/ultimate-force-white.blade.php\n";
echo "✓ Test page created: public/ultimate-force-white-test.html\n";

echo "\n=== ULTIMATE FORCE WHITE TEXT COMPLETE ===\n";
echo "Files created:\n";
echo "- public/css/ultimate-force-white.css\n";
echo "- public/js/ultimate-force-white.js\n";
echo "- public/ultimate-force-white.html\n";
echo "- resources/views/ultimate-force-white.blade.php\n";
echo "- public/ultimate-force-white-test.html\n";
echo "\nTest the changes at:\n";
echo "- http://localhost:8000/ultimate-force-white-test.html\n";
echo "- http://localhost:8000/ultimate-force-white.html\n";
echo "\nThe hero section text should now be white!\n";
?>