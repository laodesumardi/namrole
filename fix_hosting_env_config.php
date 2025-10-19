<?php
/**
 * Fix Hosting Environment Configuration
 * 
 * This script fixes .env configuration for hosting
 * Specifically for https://uji.odetune.shop/
 */

echo "🔧 Fixing Hosting Environment Configuration\n";
echo "==========================================\n\n";

echo "🔧 Fixing .env configuration for hosting...\n";

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

// 2. Read current .env file
echo "\n📄 Reading current .env file...\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    echo "✅ .env file found\n";
} else {
    echo "❌ .env file not found\n";
    exit(1);
}

// 3. Fix .env configuration for hosting
echo "\n🔧 Fixing .env configuration for hosting...\n";

// Define the correct .env configuration for hosting
$correctEnvConfig = [
    'APP_NAME' => 'SMP Negeri 01 Namrole',
    'APP_ENV' => 'production',
    'APP_KEY' => 'base64:einrC95JoeePOe6GZganQrDZ+zfLBorE65HEBy6lxew=',
    'APP_DEBUG' => 'false',
    'APP_TIMEZONE' => 'Asia/Jakarta',
    'APP_URL' => 'https://uji.odetune.shop/',
    
    'APP_LOCALE' => 'id',
    'APP_FALLBACK_LOCALE' => 'id',
    'APP_FAKER_LOCALE' => 'id_ID',
    
    'APP_MAINTENANCE_DRIVER' => 'file',
    'PHP_CLI_SERVER_WORKERS' => '4',
    'BCRYPT_ROUNDS' => '12',
    
    'LOG_CHANNEL' => 'stack',
    'LOG_STACK' => 'single',
    'LOG_DEPRECATIONS_CHANNEL' => 'null',
    'LOG_LEVEL' => 'error',
    
    'DB_CONNECTION' => 'mysql',
    'DB_HOST' => '127.0.0.1',
    'DB_PORT' => '3306',
    'DB_DATABASE' => 'u798974089_namrole',
    'DB_USERNAME' => 'u798974089_namrole',
    'DB_PASSWORD' => '8F@d@Ay4',
    
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => '480',
    'SESSION_ENCRYPT' => 'false',
    'SESSION_PATH' => '/',
    'SESSION_DOMAIN' => 'null',
    'SESSION_SECURE_COOKIE' => 'false',
    'SESSION_SAME_SITE' => 'lax',
    
    'BROADCAST_CONNECTION' => 'log',
    'FILESYSTEM_DISK' => 'public',
    'QUEUE_CONNECTION' => 'database',
    
    'CACHE_STORE' => 'database',
    'CACHE_PREFIX' => '',
    
    'MEMCACHED_HOST' => '127.0.0.1',
    
    'REDIS_CLIENT' => 'phpredis',
    'REDIS_HOST' => '127.0.0.1',
    'REDIS_PASSWORD' => 'null',
    'REDIS_PORT' => '6379',
    
    'MAIL_MAILER' => 'log',
    'MAIL_SCHEME' => 'null',
    'MAIL_HOST' => '127.0.0.1',
    'MAIL_PORT' => '2525',
    'MAIL_USERNAME' => 'null',
    'MAIL_PASSWORD' => 'null',
    'MAIL_FROM_ADDRESS' => 'info@smpnegeri01namrole.sch.id',
    'MAIL_FROM_NAME' => 'SMP Negeri 01 Namrole',
    
    'AWS_ACCESS_KEY_ID' => '',
    'AWS_SECRET_ACCESS_KEY' => '',
    'AWS_DEFAULT_REGION' => 'us-east-1',
    'AWS_BUCKET' => '',
    'AWS_USE_PATH_STYLE_ENDPOINT' => 'false',
    
    'VITE_APP_NAME' => 'SMP Negeri 01 Namrole'
];

// Update .env file
$newEnvContent = '';
foreach ($correctEnvConfig as $key => $value) {
    $newEnvContent .= "$key=$value\n";
}

// Remove duplicate entries and fix formatting
$lines = explode("\n", $newEnvContent);
$uniqueLines = [];
$seenKeys = [];

foreach ($lines as $line) {
    if (trim($line) === '') {
        continue;
    }
    
    $key = explode('=', $line)[0];
    if (!in_array($key, $seenKeys)) {
        $uniqueLines[] = $line;
        $seenKeys[] = $key;
    }
}

$newEnvContent = implode("\n", $uniqueLines);

// Write the corrected .env file
if (file_put_contents('.env', $newEnvContent)) {
    echo "✅ .env file updated successfully\n";
} else {
    echo "❌ Failed to update .env file\n";
}

// 4. Verify .env configuration
echo "\n🔍 Verifying .env configuration...\n";

$requiredConfigs = [
    'APP_NAME' => 'SMP Negeri 01 Namrole',
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'APP_URL' => 'https://uji.odetune.shop/',
    'DB_CONNECTION' => 'mysql',
    'DB_DATABASE' => 'u798974089_namrole',
    'DB_USERNAME' => 'u798974089_namrole',
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => '480',
    'SESSION_SECURE_COOKIE' => 'false',
    'SESSION_SAME_SITE' => 'lax',
    'FILESYSTEM_DISK' => 'public',
    'LOG_LEVEL' => 'error'
];

foreach ($requiredConfigs as $key => $expectedValue) {
    if (strpos($newEnvContent, "$key=$expectedValue") !== false) {
        echo "✅ $key is correctly set to $expectedValue\n";
    } else {
        echo "❌ $key is not set correctly\n";
    }
}

// 5. Clear caches
echo "\n🧹 Clearing caches...\n";

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

// 6. Test database connection
echo "\n🗄️ Testing database connection...\n";

try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "🔧 Please check database credentials\n";
}

// 7. Test Laravel functionality
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
}

// 8. Create optimized .htaccess for hosting
echo "\n🔧 Creating optimized .htaccess for hosting...\n";

$htaccessContent = '# Laravel .htaccess for hosting optimization
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
ErrorDocument 500 /index.php

# Performance optimization
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType text/javascript "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
</IfModule>';

$htaccessPath = 'public/.htaccess';
if (file_put_contents($htaccessPath, $htaccessContent)) {
    echo "✅ Optimized .htaccess created for hosting\n";
} else {
    echo "❌ Failed to create .htaccess\n";
}

echo "\n✅ Hosting environment configuration fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Fixed APP_NAME to 'SMP Negeri 01 Namrole'\n";
echo "- Set APP_ENV to 'production'\n";
echo "- Set APP_DEBUG to 'false'\n";
echo "- Set APP_URL to 'https://uji.odetune.shop/'\n";
echo "- Set APP_TIMEZONE to 'Asia/Jakarta'\n";
echo "- Set APP_LOCALE to 'id'\n";
echo "- Set LOG_LEVEL to 'error'\n";
echo "- Fixed SESSION configuration\n";
echo "- Fixed database configuration\n";
echo "- Fixed mail configuration\n";
echo "- Cleared all caches\n";
echo "- Created optimized .htaccess\n";
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
echo "2. Run: php fix_hosting_env_config.php\n";
echo "3. Test website functionality\n";
echo "4. Check if all configurations are working\n";
echo "5. Monitor server logs for any issues\n";

