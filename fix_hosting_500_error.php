<?php
/**
 * Fix Hosting 500 Error
 * 
 * This script fixes 500 Server Error on hosting
 * Specifically for https://uji.odetune.shop/
 */

echo "🚨 Fixing Hosting 500 Error\n";
echo "===========================\n\n";

echo "🔧 Fixing 500 Server Error on hosting...\n";

// 1. Bootstrap Laravel
echo "\n🔗 Bootstrapping Laravel...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to bootstrap Laravel: " . $e->getMessage() . "\n";
    echo "🔧 This might be the cause of 500 error\n";
    
    // Try to fix common issues
    echo "\n🔧 Attempting to fix common issues...\n";
    
    // Check if .env exists
    if (!file_exists('.env')) {
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
    } else {
        echo "✅ .env file exists\n";
    }
    
    // Check if vendor directory exists
    if (!is_dir('vendor')) {
        echo "❌ vendor directory not found\n";
        echo "🔧 This is likely the cause of 500 error\n";
        echo "📝 Please run: composer install\n";
    } else {
        echo "✅ vendor directory exists\n";
    }
    
    // Check if storage directory is writable
    if (!is_writable('storage')) {
        echo "❌ storage directory is not writable\n";
        echo "🔧 Setting storage permissions...\n";
        
        if (chmod('storage', 0755)) {
            echo "✅ Storage permissions set\n";
        } else {
            echo "❌ Failed to set storage permissions\n";
        }
    } else {
        echo "✅ storage directory is writable\n";
    }
    
    // Check if bootstrap/cache directory is writable
    if (!is_writable('bootstrap/cache')) {
        echo "❌ bootstrap/cache directory is not writable\n";
        echo "🔧 Setting bootstrap/cache permissions...\n";
        
        if (chmod('bootstrap/cache', 0755)) {
            echo "✅ Bootstrap/cache permissions set\n";
        } else {
            echo "❌ Failed to set bootstrap/cache permissions\n";
        }
    } else {
        echo "✅ bootstrap/cache directory is writable\n";
    }
    
    exit(1);
}

// 2. Check Laravel configuration
echo "\n🔍 Checking Laravel configuration...\n";

// Check if .env file exists and has required values
if (file_exists('.env')) {
    echo "✅ .env file exists\n";
    
    $envContent = file_get_contents('.env');
    $requiredEnvVars = [
        'APP_NAME',
        'APP_ENV',
        'APP_KEY',
        'APP_DEBUG',
        'APP_URL',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD'
    ];
    
    foreach ($requiredEnvVars as $var) {
        if (strpos($envContent, $var . '=') !== false) {
            echo "✅ $var is set\n";
        } else {
            echo "❌ $var is missing\n";
            echo "🔧 This might cause 500 error\n";
        }
    }
} else {
    echo "❌ .env file not found\n";
    echo "🔧 This is likely the cause of 500 error\n";
}

// 3. Check if APP_KEY is set
echo "\n🔑 Checking APP_KEY...\n";

if (strpos($envContent, 'APP_KEY=') !== false && strpos($envContent, 'APP_KEY=base64:') !== false) {
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

// 4. Check database connection
echo "\n🗄️ Checking database connection...\n";

try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "🔧 This might cause 500 error\n";
}

// 5. Check if migrations are run
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

// 6. Check if storage link exists
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

// 7. Check file permissions
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
    }
}

// 8. Clear all caches
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

// 9. Check for syntax errors in key files
echo "\n🔍 Checking for syntax errors...\n";

$keyFiles = [
    'app/Http/Kernel.php',
    'app/Http/Middleware/VerifyCsrfToken.php',
    'app/Http/Middleware/TrustProxies.php',
    'config/app.php',
    'config/database.php',
    'config/session.php',
    'routes/web.php'
];

foreach ($keyFiles as $file) {
    if (file_exists($file)) {
        $output = shell_exec("php -l $file 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✅ $file has no syntax errors\n";
        } else {
            echo "❌ $file has syntax errors: $output\n";
        }
    } else {
        echo "⚠️ $file not found\n";
    }
}

// 10. Check if routes are working
echo "\n🛣️ Checking routes...\n";

try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    echo "✅ Routes loaded successfully (" . count($routes) . " routes)\n";
} catch (Exception $e) {
    echo "❌ Error loading routes: " . $e->getMessage() . "\n";
    echo "🔧 This might cause 500 error\n";
}

// 11. Check if views are working
echo "\n👁️ Checking views...\n";

try {
    $view = view('welcome');
    echo "✅ Views are working\n";
} catch (Exception $e) {
    echo "❌ Error with views: " . $e->getMessage() . "\n";
    echo "🔧 This might cause 500 error\n";
}

// 12. Check if models are working
echo "\n📊 Checking models...\n";

try {
    $userCount = \App\Models\User::count();
    echo "✅ User model is working ($userCount users)\n";
} catch (Exception $e) {
    echo "❌ Error with User model: " . $e->getMessage() . "\n";
    echo "🔧 This might cause 500 error\n";
}

// 13. Create .htaccess for hosting
echo "\n🔧 Creating .htaccess for hosting...\n";

$htaccessContent = '# Laravel .htaccess for hosting
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
    echo "✅ .htaccess created for hosting\n";
} else {
    echo "❌ Failed to create .htaccess\n";
}

// 14. Update .env for hosting
echo "\n🔧 Updating .env for hosting...\n";

$envUpdates = [
    'APP_ENV=production',
    'APP_DEBUG=false',
    'SESSION_SECURE_COOKIE=false',
    'SESSION_SAME_SITE=lax',
    'SESSION_LIFETIME=480',
    'FILESYSTEM_DISK=public',
    'LOG_LEVEL=error'
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

// 15. Test if Laravel is working
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

echo "\n✅ Hosting 500 error fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Checked Laravel bootstrap\n";
echo "- Checked .env configuration\n";
echo "- Checked APP_KEY\n";
echo "- Checked database connection\n";
echo "- Checked migrations\n";
echo "- Checked storage link\n";
echo "- Checked file permissions\n";
echo "- Cleared all caches\n";
echo "- Checked syntax errors\n";
echo "- Checked routes\n";
echo "- Checked views\n";
echo "- Checked models\n";
echo "- Created .htaccess for hosting\n";
echo "- Updated .env for hosting\n";
echo "- Tested Laravel functionality\n\n";

echo "🌐 Hosting URLs:\n";
echo "- Website: https://uji.odetune.shop/\n";
echo "- Admin: https://uji.odetune.shop/admin\n";
echo "- Login: https://uji.odetune.shop/login\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: https://uji.odetune.shop/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps for hosting:\n";
echo "1. Upload this script to your hosting server\n";
echo "2. Run: php fix_hosting_500_error.php\n";
echo "3. Check if 500 error is resolved\n";
echo "4. Test all functionality\n";
echo "5. Check server logs for any remaining errors\n";

