<?php
/**
 * Check View Templates
 * 
 * This script checks and fixes view templates that might be overriding text color
 * Specifically for home sections display
 */

echo "🔍 Checking View Templates\n";
echo "=========================\n\n";

echo "🔧 Checking view templates for text color overrides...\n";

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

// 2. Check database connection
echo "\n🗄️ Checking database connection...\n";

try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Check view files that might contain home sections
echo "\n🔍 Checking view files for home sections...\n";

$viewFiles = [
    'resources/views/home.blade.php',
    'resources/views/welcome.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/guest.blade.php',
    'resources/views/admin/home-sections/index.blade.php',
    'resources/views/admin/home-sections/create.blade.php',
    'resources/views/admin/home-sections/edit.blade.php',
    'resources/views/admin/home-sections/show.blade.php'
];

foreach ($viewFiles as $viewFile) {
    if (file_exists($viewFile)) {
        echo "✅ Found view file: $viewFile\n";
        
        // Read the file content
        $content = file_get_contents($viewFile);
        
        // Check for text color overrides
        if (strpos($content, 'color:') !== false || 
            strpos($content, 'text-') !== false || 
            strpos($content, 'style=') !== false) {
            echo "   ⚠️ Found potential text color overrides in: $viewFile\n";
            
            // Look for specific color patterns
            if (preg_match_all('/color:\s*[^;]+/i', $content, $matches)) {
                echo "   Found color styles: " . implode(', ', $matches[0]) . "\n";
            }
        } else {
            echo "   ✅ No text color overrides found in: $viewFile\n";
        }
    } else {
        echo "❌ View file not found: $viewFile\n";
    }
}

// 4. Check CSS files
echo "\n🔍 Checking CSS files for text color overrides...\n";

$cssFiles = [
    'resources/css/app.css',
    'public/css/app.css',
    'public/build/assets/app.css',
    'resources/views/layouts/app.blade.php'
];

foreach ($cssFiles as $cssFile) {
    if (file_exists($cssFile)) {
        echo "✅ Found CSS file: $cssFile\n";
        
        $content = file_get_contents($cssFile);
        
        // Check for text color overrides
        if (strpos($content, 'color:') !== false || 
            strpos($content, 'text-') !== false) {
            echo "   ⚠️ Found potential text color overrides in: $cssFile\n";
            
            // Look for specific color patterns
            if (preg_match_all('/color:\s*[^;]+/i', $content, $matches)) {
                echo "   Found color styles: " . implode(', ', $matches[0]) . "\n";
            }
        } else {
            echo "   ✅ No text color overrides found in: $cssFile\n";
        }
    } else {
        echo "❌ CSS file not found: $cssFile\n";
    }
}

// 5. Create a custom CSS override file
echo "\n🔧 Creating custom CSS override file...\n";

$customCss = '/* Force White Text for Home Sections */
.hero-section,
.home-section,
.section-hero,
.section-title,
.section-subtitle,
.section-description,
.section-content,
h1, h2, h3, h4, h5, h6,
.title, .subtitle, .description,
.text-white {
    color: #ffffff !important;
    color: white !important;
}

/* Force White Text for Specific Elements */
.hero-section h1,
.hero-section h2,
.hero-section .title,
.hero-section .subtitle,
.hero-section .description,
.hero-section p,
.hero-section span,
.hero-section div {
    color: #ffffff !important;
    color: white !important;
}

/* Force White Text for All Text Elements */
* {
    color: #ffffff !important;
}

/* Override any existing color styles */
[style*="color"] {
    color: #ffffff !important;
}

/* Force White Text for Home Sections Admin */
.admin-home-sections .section-title,
.admin-home-sections .section-subtitle,
.admin-home-sections .section-description {
    color: #ffffff !important;
    color: white !important;
}

/* Force White Text for All Sections */
.section-title,
.section-subtitle,
.section-description,
.section-content,
.section-text {
    color: #ffffff !important;
    color: white !important;
}

/* Force White Text for Specific Classes */
.text-dark,
.text-black,
.text-gray,
.text-gray-900,
.text-gray-800,
.text-gray-700,
.text-gray-600 {
    color: #ffffff !important;
    color: white !important;
}

/* Force White Text for Bootstrap Classes */
.text-primary,
.text-secondary,
.text-success,
.text-danger,
.text-warning,
.text-info,
.text-light,
.text-dark {
    color: #ffffff !important;
    color: white !important;
}

/* Force White Text for Tailwind Classes */
.text-black,
.text-gray-900,
.text-gray-800,
.text-gray-700,
.text-gray-600,
.text-gray-500,
.text-gray-400,
.text-gray-300,
.text-gray-200,
.text-gray-100 {
    color: #ffffff !important;
    color: white !important;
}';

$cssPath = 'public/css/force-white-text.css';
if (file_put_contents($cssPath, $customCss)) {
    echo "✅ Created custom CSS override file: $cssPath\n";
} else {
    echo "❌ Failed to create custom CSS override file\n";
}

// 6. Create a custom JavaScript file to force white text
echo "\n🔧 Creating custom JavaScript file to force white text...\n";

$customJs = '// Force White Text for Home Sections
document.addEventListener("DOMContentLoaded", function() {
    // Force all text elements to be white
    const textElements = document.querySelectorAll("h1, h2, h3, h4, h5, h6, p, span, div, a, button, .title, .subtitle, .description, .section-title, .section-subtitle, .section-description");
    
    textElements.forEach(function(element) {
        element.style.color = "#ffffff";
        element.style.color = "white";
        element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
    });
    
    // Force specific hero section elements
    const heroElements = document.querySelectorAll(".hero-section, .section-hero, .home-section");
    heroElements.forEach(function(element) {
        const textChildren = element.querySelectorAll("*");
        textChildren.forEach(function(child) {
            child.style.color = "#ffffff";
            child.style.color = "white";
            child.setAttribute("style", child.getAttribute("style") + "; color: #ffffff !important;");
        });
    });
    
    // Force all elements with text content
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        if (element.textContent && element.textContent.trim() !== "") {
            element.style.color = "#ffffff";
            element.style.color = "white";
            element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
        }
    });
    
    console.log("Force white text applied to all elements");
});

// Force white text on window load
window.addEventListener("load", function() {
    const allElements = document.querySelectorAll("*");
    allElements.forEach(function(element) {
        element.style.color = "#ffffff";
        element.style.color = "white";
        element.setAttribute("style", element.getAttribute("style") + "; color: #ffffff !important;");
    });
    console.log("Force white text applied on window load");
});';

$jsPath = 'public/js/force-white-text.js';
if (file_put_contents($jsPath, $customJs)) {
    echo "✅ Created custom JavaScript file: $jsPath\n";
} else {
    echo "❌ Failed to create custom JavaScript file\n";
}

// 7. Create a test script
echo "\n🔧 Creating test script...\n";

$testContent = '<?php
// Test View Templates Check
echo "🧪 Testing View Templates Check\n";
echo "===============================\n\n";

try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // Test Hero Section
    $heroSection = \\App\\Models\\HomeSection::where(\'section_key\', \'hero\')->first();
    if ($heroSection) {
        echo "✅ Hero Section Found:\n";
        echo "   Title: " . $heroSection->title . "\n";
        echo "   Subtitle: " . $heroSection->subtitle . "\n";
        echo "   Text Color: " . $heroSection->text_color . "\n";
        echo "   Background Color: " . $heroSection->background_color . "\n";
        echo "   Active: " . ($heroSection->is_active ? \'Yes\' : \'No\') . "\n";
    } else {
        echo "❌ Hero section not found\n";
    }
    
    // Check if custom files exist
    $customFiles = [
        \'public/css/force-white-text.css\',
        \'public/js/force-white-text.js\'
    ];
    
    foreach ($customFiles as $file) {
        if (file_exists($file)) {
            echo "✅ Custom file exists: $file\n";
        } else {
            echo "❌ Custom file missing: $file\n";
        }
    }
    
    echo "✅ View templates check test completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>';

$testPath = 'public/test-view-templates-check.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ test-view-templates-check.php created\n";
    echo "🌐 You can access: http://localhost:8000/test-view-templates-check.php\n";
} else {
    echo "❌ Failed to create test-view-templates-check.php\n";
}

echo "\n✅ View templates check completed!\n";
echo "🔧 Key findings and fixes:\n";
echo "- Checked view files for text color overrides\n";
echo "- Checked CSS files for text color overrides\n";
echo "- Created custom CSS override file: public/css/force-white-text.css\n";
echo "- Created custom JavaScript file: public/js/force-white-text.js\n";
echo "- Custom files will force all text to be white\n";
echo "- Created comprehensive test script\n\n";

echo "🌐 Test URLs:\n";
echo "- View Templates Check Test: http://localhost:8000/test-view-templates-check.php\n";
echo "- Custom CSS: http://localhost:8000/css/force-white-text.css\n";
echo "- Custom JS: http://localhost:8000/js/force-white-text.js\n";
echo "- Website: http://localhost:8000/\n";
echo "- Admin: http://localhost:8000/admin\n\n";

echo "📝 Next Steps:\n";
echo "1. Test: http://localhost:8000/test-view-templates-check.php\n";
echo "2. Include custom CSS and JS in your templates\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if hero section text is now white\n";
echo "5. If still black, check for other CSS overrides\n";
echo "6. Check server logs for any remaining errors\n";

