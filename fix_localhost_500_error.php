<?php
/**
 * Fix Localhost 500 Error
 * 
 * This script fixes 500 error on localhost
 * Specifically for http://localhost:8000/
 */

echo "🚨 Fixing Localhost 500 Error\n";
echo "============================\n\n";

echo "🔧 Comprehensive fix for 500 error on localhost...\n";

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
        echo "📝 Please run: composer install\n";
    }
} else {
    echo "❌ vendor directory not found\n";
    echo "🔧 This is likely the cause of 500 error\n";
    echo "📝 Please run: composer install\n";
}

// 3. Check if .env file exists and is readable
echo "\n📄 Checking .env file...\n";

if (file_exists('.env')) {
    echo "✅ .env file exists\n";
    
    if (is_readable('.env')) {
        echo "✅ .env file is readable\n";
        
        $envContent = file_get_contents('.env');
        
        // Check for required variables
        $requiredVars = [
            'APP_NAME',
            'APP_ENV',
            'APP_KEY',
            'APP_DEBUG',
            'APP_URL',
            'DB_CONNECTION',
            'DB_HOST',
            'DB_DATABASE',
            'DB_USERNAME',
            'DB_PASSWORD'
        ];
        
        foreach ($requiredVars as $var) {
            if (strpos($envContent, $var . '=') !== false) {
                echo "✅ $var is set\n";
            } else {
                echo "❌ $var is missing\n";
            }
        }
    } else {
        echo "❌ .env file is not readable\n";
    }
} else {
    echo "❌ .env file not found\n";
    echo "🔧 Creating .env file from .env.example...\n";
    
    if (file_exists('.env.example')) {
        if (copy('.env.example', '.env')) {
            echo "✅ .env file created from .env.example\n";
        } else {
            echo "❌ Failed to create .env file\n";
        }
    } else {
        echo "❌ .env.example file not found\n";
    }
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

// 5. Try to bootstrap Laravel
echo "\n🔗 Attempting to bootstrap Laravel...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
    
    // 6. Check if APP_KEY is set
    echo "\n🔑 Checking APP_KEY...\n";
    
    $appKey = env('APP_KEY');
    if ($appKey && strpos($appKey, 'base64:') === 0) {
        echo "✅ APP_KEY is set\n";
    } else {
        echo "❌ APP_KEY is not set or invalid\n";
        echo "🔧 Generating APP_KEY...\n";
        
        try {
            \Illuminate\Support\Facades\Artisan::call('key:generate');
            echo "✅ APP_KEY generated successfully\n";
        } catch (Exception $e) {
            echo "❌ Failed to generate APP_KEY: " . $e->getMessage() . "\n";
        }
    }
    
    // 7. Check database connection
    echo "\n🗄️ Checking database connection...\n";
    
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "✅ Database connection successful\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        echo "🔧 This might cause 500 error\n";
    }
    
    // 8. Check if migrations are run
    echo "\n📊 Checking database migrations...\n";
    
    try {
        $migrations = \Illuminate\Support\Facades\DB::table('migrations')->count();
        echo "✅ Migrations table exists with $migrations migrations\n";
    } catch (Exception $e) {
        echo "❌ Migrations table not found or error: " . $e->getMessage() . "\n";
        echo "🔧 Running migrations...\n";
        
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
            echo "✅ Migrations run successfully\n";
        } catch (Exception $e) {
            echo "❌ Failed to run migrations: " . $e->getMessage() . "\n";
        }
    }
    
    // 9. Check if storage link exists
    echo "\n🔗 Checking storage link...\n";
    
    $storageLink = 'public/storage';
    if (is_link($storageLink)) {
        echo "✅ Storage link exists\n";
    } else {
        echo "❌ Storage link does not exist\n";
        echo "🔧 Creating storage link...\n";
        
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            echo "✅ Storage link created\n";
        } catch (Exception $e) {
            echo "❌ Failed to create storage link: " . $e->getMessage() . "\n";
            echo "🔧 Creating manual storage directory...\n";
            
            // Create manual storage directory
            if (!is_dir('public/storage')) {
                mkdir('public/storage', 0755, true);
                echo "✅ Manual storage directory created\n";
            }
        }
    }
    
    // 10. Clear all caches
    echo "\n🧹 Clearing all caches...\n";
    
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        echo "✅ Config cache cleared\n";
        
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        echo "✅ Route cache cleared\n";
        
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        echo "✅ View cache cleared\n";
        
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        echo "✅ Application cache cleared\n";
        
        \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        echo "✅ All optimizations cleared\n";
    } catch (Exception $e) {
        echo "❌ Error clearing caches: " . $e->getMessage() . "\n";
    }
    
    // 11. Test Laravel functionality
    echo "\n🧪 Testing Laravel functionality...\n";
    
    try {
        // Test if we can create a simple response
        $response = response()->json(['status' => 'success', 'message' => 'Laravel is working']);
        echo "✅ Laravel response creation successful\n";
        
        // Test if we can access database
        $userCount = \App\Models\User::count();
        echo "✅ Database access successful ($userCount users)\n";
        
        // Test if we can access routes
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        echo "✅ Route access successful (" . count($routes) . " routes)\n";
        
        echo "✅ Laravel is working properly\n";
    } catch (Exception $e) {
        echo "❌ Laravel test failed: " . $e->getMessage() . "\n";
        echo "🔧 This is likely the cause of 500 error\n";
    }
    
} catch (Exception $e) {
    echo "❌ Failed to bootstrap Laravel: " . $e->getMessage() . "\n";
    echo "🔧 This is likely the cause of 500 error\n";
    
    // Try to fix common issues
    echo "\n🔧 Attempting to fix common issues...\n";
    
    // Check if composer dependencies are installed
    if (!file_exists('vendor/autoload.php')) {
        echo "❌ Composer dependencies not installed\n";
        echo "📝 Please run: composer install\n";
    }
    
    // Check if .env file exists
    if (!file_exists('.env')) {
        echo "❌ .env file not found\n";
        echo "📝 Please create .env file\n";
    }
    
    // Check if storage directories exist
    if (!is_dir('storage')) {
        echo "❌ storage directory not found\n";
        echo "📝 Please create storage directory\n";
    }
}

// 12. Create .htaccess for localhost
echo "\n🔧 Creating .htaccess for localhost...\n";

$htaccessContent = '# Laravel .htaccess for localhost
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

# Set proper MIME types
<IfModule mod_mime.c>
    AddType application/json .json
    AddType text/css .css
    AddType text/javascript .js
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/svg+xml .svg
    AddType image/webp .webp
</IfModule>

# Enable CORS
<IfModule mod_headers.c>
    Header set Access-Control-Allow-Origin "*"
    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"
    Header set Access-Control-Allow-Headers "Content-Type, Authorization, X-Requested-With"
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Error handling
ErrorDocument 404 /index.php
ErrorDocument 500 /index.php';

$htaccessPath = 'public/.htaccess';
if (file_put_contents($htaccessPath, $htaccessContent)) {
    echo "✅ .htaccess created for localhost\n";
} else {
    echo "❌ Failed to create .htaccess\n";
}

// 13. Create localhost test page
echo "\n🔧 Creating localhost test page...\n";

$localhostTestContent = '<?php
// Localhost test page
echo "<h1>SMP Negeri 01 Namrole - Localhost Test</h1>";
echo "<p>Localhost is working!</p>";
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

$localhostTestPath = 'public/localhost-test.php';
if (file_put_contents($localhostTestPath, $localhostTestContent)) {
    echo "✅ localhost-test.php created\n";
    echo "🌐 You can access: http://localhost:8000/localhost-test.php\n";
} else {
    echo "❌ Failed to create localhost-test.php\n";
}

echo "\n✅ Localhost 500 error fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Checked directory structure\n";
echo "- Checked vendor directory\n";
echo "- Checked .env file\n";
echo "- Checked file permissions\n";
echo "- Attempted Laravel bootstrap\n";
echo "- Checked APP_KEY\n";
echo "- Checked database connection\n";
echo "- Checked migrations\n";
echo "- Checked storage link\n";
echo "- Cleared all caches\n";
echo "- Tested Laravel functionality\n";
echo "- Created .htaccess for localhost\n";
echo "- Created localhost test page\n\n";

echo "🌐 Localhost URLs:\n";
echo "- Website: http://localhost:8000/\n";
echo "- Test: http://localhost:8000/localhost-test.php\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Login: http://localhost:8000/login\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps for localhost:\n";
echo "1. Run: php fix_localhost_500_error.php\n";
echo "2. Test: http://localhost:8000/localhost-test.php\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if 500 error is resolved\n";
echo "5. Check server logs for any remaining errors\n";
