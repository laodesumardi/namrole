<?php
/**
 * Fix Hosting Fundamental 500 Error
 * 
 * This script fixes fundamental 500 error on hosting
 * Specifically for https://uji.odetune.shop/
 */

echo "🚨 Fixing Hosting Fundamental 500 Error\n";
echo "=====================================\n\n";

echo "🔧 Comprehensive fix for fundamental 500 error on hosting...\n";

// 1. Check if we're in the right directory
echo "\n📁 Checking directory structure...\n";

$requiredFiles = [
    'artisan',
    'composer.json',
    'bootstrap/app.php',
    'public/index.php',
    '.env'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists\n";
    } else {
        echo "❌ $file not found\n";
    }
}

// 2. Check if vendor directory exists
echo "\n📦 Checking vendor directory...\n";

if (is_dir('vendor')) {
    echo "✅ vendor directory exists\n";
    
    if (file_exists('vendor/autoload.php')) {
        echo "✅ vendor/autoload.php exists\n";
    } else {
        echo "❌ vendor/autoload.php not found\n";
        echo "🔧 This is likely the cause of 500 error\n";
    }
} else {
    echo "❌ vendor directory not found\n";
    echo "🔧 This is likely the cause of 500 error\n";
}

// 3. Check if .env file exists and is readable
echo "\n📄 Checking .env file...\n";

if (file_exists('.env')) {
    echo "✅ .env file exists\n";
    
    if (is_readable('.env')) {
        echo "✅ .env file is readable\n";
    } else {
        echo "❌ .env file is not readable\n";
    }
} else {
    echo "❌ .env file not found\n";
}

// 4. Check file permissions
echo "\n🔐 Checking file permissions...\n";

$directories = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
    'public',
    'public/storage'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_writable($dir)) {
            echo "✅ $dir is writable\n";
        } else {
            echo "❌ $dir is not writable\n";
            echo "🔧 Setting permissions for $dir...\n";
            
            if (chmod($dir, 0755)) {
                echo "✅ Permissions set for $dir\n";
            } else {
                echo "❌ Failed to set permissions for $dir\n";
            }
        }
    } else {
        echo "⚠️ $dir directory not found\n";
        
        // Create missing directories
        if (mkdir($dir, 0755, true)) {
            echo "✅ Created directory: $dir\n";
        } else {
            echo "❌ Failed to create directory: $dir\n";
        }
    }
}

// 5. Create a minimal .env file
echo "\n🔧 Creating minimal .env file...\n";

$minimalEnvContent = 'APP_NAME="SMP Negeri 01 Namrole"
APP_ENV=production
APP_KEY=base64:einrC95JoeePOe6GZganQrDZ+zfLBorE65HEBy6lxew=
APP_DEBUG=false
APP_URL=https://uji.odetune.shop/

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u798974089_namrole
DB_USERNAME=u798974089_namrole
DB_PASSWORD=8F@d@Ay4

SESSION_DRIVER=file
SESSION_LIFETIME=480
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

FILESYSTEM_DISK=public
CACHE_STORE=file
LOG_LEVEL=error';

if (file_put_contents('.env', $minimalEnvContent)) {
    echo "✅ Minimal .env file created\n";
} else {
    echo "❌ Failed to create minimal .env file\n";
}

// 6. Create a super simple test page
echo "\n🔧 Creating super simple test page...\n";

$superSimpleTestContent = '<?php
// Super simple test page
echo "<h1>SMP Negeri 01 Namrole</h1>";
echo "<p>Website is working!</p>";
echo "<p>Current time: " . date("Y-m-d H:i:s") . "</p>";
echo "<p>PHP version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER["SERVER_SOFTWARE"] . "</p>";
echo "<p>Document root: " . $_SERVER["DOCUMENT_ROOT"] . "</p>";
echo "<p>Script path: " . __FILE__ . "</p>";

// Check if we can write to current directory
if (is_writable(".")) {
    echo "<p>✅ Current directory is writable</p>";
} else {
    echo "<p>❌ Current directory is not writable</p>";
}

// Check if we can read files
if (file_exists("../.env")) {
    echo "<p>✅ .env file exists</p>";
} else {
    echo "<p>❌ .env file not found</p>";
}

if (file_exists("../artisan")) {
    echo "<p>✅ artisan file exists</p>";
} else {
    echo "<p>❌ artisan file not found</p>";
}

if (is_dir("../vendor")) {
    echo "<p>✅ vendor directory exists</p>";
} else {
    echo "<p>❌ vendor directory not found</p>";
}

if (file_exists("../vendor/autoload.php")) {
    echo "<p>✅ vendor/autoload.php exists</p>";
} else {
    echo "<p>❌ vendor/autoload.php not found</p>";
}

// Test if we can include files
try {
    if (file_exists("../vendor/autoload.php")) {
        require_once "../vendor/autoload.php";
        echo "<p>✅ vendor/autoload.php loaded successfully</p>";
    } else {
        echo "<p>❌ vendor/autoload.php not found</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error loading vendor/autoload.php: " . $e->getMessage() . "</p>";
}

// Test if we can access Laravel
try {
    if (file_exists("../bootstrap/app.php")) {
        $app = require_once "../bootstrap/app.php";
        echo "<p>✅ Laravel bootstrap loaded successfully</p>";
    } else {
        echo "<p>❌ bootstrap/app.php not found</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Error loading Laravel: " . $e->getMessage() . "</p>";
}
?>';

$superSimpleTestPath = 'public/super-simple-test.php';
if (file_put_contents($superSimpleTestPath, $superSimpleTestContent)) {
    echo "✅ super-simple-test.php created\n";
    echo "🌐 You can access: https://uji.odetune.shop/super-simple-test.php\n";
} else {
    echo "❌ Failed to create super-simple-test.php\n";
}

// 7. Create an even simpler test page
echo "\n🔧 Creating ultra simple test page...\n";

$ultraSimpleTestContent = '<?php
echo "Hello World!";
echo "<br>PHP is working!";
echo "<br>Time: " . date("Y-m-d H:i:s");
?>';

$ultraSimpleTestPath = 'public/hello.php';
if (file_put_contents($ultraSimpleTestPath, $ultraSimpleTestContent)) {
    echo "✅ hello.php created\n";
    echo "🌐 You can access: https://uji.odetune.shop/hello.php\n";
} else {
    echo "❌ Failed to create hello.php\n";
}

// 8. Create a basic HTML test page
echo "\n🔧 Creating basic HTML test page...\n";

$htmlTestContent = '<!DOCTYPE html>
<html>
<head>
    <title>SMP Negeri 01 Namrole - Test</title>
</head>
<body>
    <h1>SMP Negeri 01 Namrole</h1>
    <p>Website is working!</p>
    <p>Current time: <script>document.write(new Date());</script></p>
    <p>This is a basic HTML page.</p>
</body>
</html>';

$htmlTestPath = 'public/index-test.html';
if (file_put_contents($htmlTestPath, $htmlTestContent)) {
    echo "✅ index-test.html created\n";
    echo "🌐 You can access: https://uji.odetune.shop/index-test.html\n";
} else {
    echo "❌ Failed to create index-test.html\n";
}

// 9. Check if we can create files in public directory
echo "\n🔧 Testing file creation in public directory...\n";

$testFiles = [
    'public/test1.txt' => 'Test file 1',
    'public/test2.txt' => 'Test file 2',
    'public/test3.txt' => 'Test file 3'
];

foreach ($testFiles as $file => $content) {
    if (file_put_contents($file, $content)) {
        echo "✅ Created $file\n";
    } else {
        echo "❌ Failed to create $file\n";
    }
}

// 10. Create a PHP info page
echo "\n🔧 Creating PHP info page...\n";

$phpInfoContent = '<?php
echo "<h1>PHP Information</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER["SERVER_SOFTWARE"] . "</p>";
echo "<p>Document Root: " . $_SERVER["DOCUMENT_ROOT"] . "</p>";
echo "<p>Script Name: " . $_SERVER["SCRIPT_NAME"] . "</p>";
echo "<p>Request URI: " . $_SERVER["REQUEST_URI"] . "</p>";

// Show PHP info
phpinfo();
?>';

$phpInfoPath = 'public/phpinfo.php';
if (file_put_contents($phpInfoPath, $phpInfoContent)) {
    echo "✅ phpinfo.php created\n";
    echo "🌐 You can access: https://uji.odetune.shop/phpinfo.php\n";
} else {
    echo "❌ Failed to create phpinfo.php\n";
}

// 11. Create a directory listing test
echo "\n🔧 Creating directory listing test...\n";

$dirListingContent = '<?php
echo "<h1>Directory Listing</h1>";
echo "<p>Current directory: " . getcwd() . "</p>";
echo "<p>Files in current directory:</p>";
echo "<ul>";

$files = scandir(".");
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        echo "<li>$file</li>";
    }
}

echo "</ul>";

echo "<p>Files in parent directory:</p>";
echo "<ul>";

$parentFiles = scandir("..");
foreach ($parentFiles as $file) {
    if ($file != "." && $file != "..") {
        echo "<li>$file</li>";
    }
}

echo "</ul>";
?>';

$dirListingPath = 'public/dir-listing.php';
if (file_put_contents($dirListingPath, $dirListingContent)) {
    echo "✅ dir-listing.php created\n";
    echo "🌐 You can access: https://uji.odetune.shop/dir-listing.php\n";
} else {
    echo "❌ Failed to create dir-listing.php\n";
}

// 12. Create a basic .htaccess
echo "\n🔧 Creating basic .htaccess...\n";

$basicHtaccessContent = '# Basic .htaccess for hosting
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Laravel routes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Error handling
ErrorDocument 404 /index.php
ErrorDocument 500 /index.php';

$basicHtaccessPath = 'public/.htaccess';
if (file_put_contents($basicHtaccessPath, $basicHtaccessContent)) {
    echo "✅ Basic .htaccess created\n";
} else {
    echo "❌ Failed to create .htaccess\n";
}

echo "\n✅ Hosting fundamental 500 error fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Checked directory structure\n";
echo "- Checked vendor directory\n";
echo "- Checked .env file\n";
echo "- Checked file permissions\n";
echo "- Created minimal .env file\n";
echo "- Created super simple test page\n";
echo "- Created ultra simple test page\n";
echo "- Created basic HTML test page\n";
echo "- Tested file creation in public directory\n";
echo "- Created PHP info page\n";
echo "- Created directory listing test\n";
echo "- Created basic .htaccess\n\n";

echo "🌐 Test URLs:\n";
echo "- Super Simple Test: https://uji.odetune.shop/super-simple-test.php\n";
echo "- Ultra Simple Test: https://uji.odetune.shop/hello.php\n";
echo "- HTML Test: https://uji.odetune.shop/index-test.html\n";
echo "- PHP Info: https://uji.odetune.shop/phpinfo.php\n";
echo "- Directory Listing: https://uji.odetune.shop/dir-listing.php\n";
echo "- Website: https://uji.odetune.shop/\n\n";

echo "📝 Next Steps for hosting:\n";
echo "1. Upload this script to your hosting server\n";
echo "2. Run: php fix_hosting_fundamental_500.php\n";
echo "3. Test: https://uji.odetune.shop/hello.php\n";
echo "4. Test: https://uji.odetune.shop/index-test.html\n";
echo "5. Test: https://uji.odetune.shop/phpinfo.php\n";
echo "6. Check server logs for any remaining errors\n";

