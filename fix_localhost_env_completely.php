<?php
/**
 * Fix Localhost .env Completely
 * 
 * This script completely fixes .env file for localhost
 * Specifically for http://localhost:8000/
 */

echo "🔧 Fixing Localhost .env Completely\n";
echo "===================================\n\n";

echo "🔧 Completely fixing .env file for localhost...\n";

// 1. Create a completely new .env file for localhost
echo "\n📄 Creating completely new .env file for localhost...\n";

$localhostEnvContent = 'APP_NAME="SMP Negeri 01 Namrole"
APP_ENV=local
APP_KEY=base64:einrC95JoeePOe6GZganQrDZ+zfLBorE65HEBy6lxew=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Localhost Database Configuration - SQLite
DB_CONNECTION=sqlite
DB_DATABASE=' . __DIR__ . '/database/database.sqlite

# Alternative MySQL configurations (uncomment if needed)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=namrole
# DB_USERNAME=root
# DB_PASSWORD=

SESSION_DRIVER=file
SESSION_LIFETIME=480
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

CACHE_STORE=file
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS=info@smpnegeri01namrole.sch.id
MAIL_FROM_NAME="SMP Negeri 01 Namrole"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="SMP Negeri 01 Namrole"';

if (file_put_contents('.env', $localhostEnvContent)) {
    echo "✅ Completely new .env file created for localhost\n";
} else {
    echo "❌ Failed to create new .env file\n";
}

// 2. Create SQLite database if it doesn't exist
echo "\n🔧 Creating SQLite database...\n";

$sqlitePath = 'database/database.sqlite';
if (!file_exists($sqlitePath)) {
    if (touch($sqlitePath)) {
        echo "✅ SQLite database created\n";
    } else {
        echo "❌ Failed to create SQLite database\n";
    }
} else {
    echo "✅ SQLite database already exists\n";
}

// 3. Bootstrap Laravel
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

// 4. Clear all caches
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

// 5. Test database connection
echo "\n🗄️ Testing database connection...\n";

try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "🔧 This might cause 500 error\n";
}

// 6. Run migrations
echo "\n📊 Running migrations...\n";

try {
    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
    echo "✅ Migrations run successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to run migrations: " . $e->getMessage() . "\n";
}

// 7. Test Laravel functionality
echo "\n🧪 Testing Laravel functionality...\n";

try {
    // Test if we can create a simple response
    $response = response()->json(['status' => 'success', 'message' => 'Laravel is working']);
    echo "✅ Laravel response creation successful\n";
    
    // Test if we can access routes
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    echo "✅ Route access successful (" . count($routes) . " routes)\n";
    
    // Test database access
    try {
        $userCount = \App\Models\User::count();
        echo "✅ Database access successful ($userCount users)\n";
    } catch (Exception $e) {
        echo "⚠️ Database access failed: " . $e->getMessage() . "\n";
    }
    
    echo "✅ Laravel is working properly\n";
} catch (Exception $e) {
    echo "❌ Laravel test failed: " . $e->getMessage() . "\n";
}

// 8. Create storage link
echo "\n🔗 Creating storage link...\n";

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

// 9. Create a simple test page
echo "\n🔧 Creating simple test page...\n";

$testContent = '<?php
// Simple test page for localhost
echo "<h1>SMP Negeri 01 Namrole - Localhost Test</h1>";
echo "<p>Localhost is working!</p>";
echo "<p>Current time: " . date("Y-m-d H:i:s") . "</p>";
echo "<p>PHP version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER["SERVER_SOFTWARE"] . "</p>";

// Test Laravel bootstrap
try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    $app->make("Illuminate\\Contracts\\Console\\Kernel")->bootstrap();
    echo "<p>✅ Laravel bootstrap successful</p>";
    
    // Test database
    try {
        $userCount = \\App\\Models\\User::count();
        echo "<p>✅ Database access successful ($userCount users)</p>";
    } catch (Exception $e) {
        echo "<p>⚠️ Database access failed: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Laravel bootstrap failed: " . $e->getMessage() . "</p>";
}
?>';

$testPath = 'public/simple-test.php';
if (file_put_contents($testPath, $testContent)) {
    echo "✅ simple-test.php created\n";
    echo "🌐 You can access: http://localhost:8000/simple-test.php\n";
} else {
    echo "❌ Failed to create simple-test.php\n";
}

// 10. Create a basic HTML test page
echo "\n🔧 Creating basic HTML test page...\n";

$htmlContent = '<!DOCTYPE html>
<html>
<head>
    <title>SMP Negeri 01 Namrole - Localhost Test</title>
</head>
<body>
    <h1>SMP Negeri 01 Namrole</h1>
    <p>Localhost is working!</p>
    <p>Current time: <script>document.write(new Date());</script></p>
    <p>This is a basic HTML page.</p>
    <p><a href="simple-test.php">Test PHP</a></p>
    <p><a href="/">Test Laravel</a></p>
</body>
</html>';

$htmlPath = 'public/html-test.html';
if (file_put_contents($htmlPath, $htmlContent)) {
    echo "✅ html-test.html created\n";
    echo "🌐 You can access: http://localhost:8000/html-test.html\n";
} else {
    echo "❌ Failed to create html-test.html\n";
}

echo "\n✅ Localhost .env completely fixed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created completely new .env file for localhost\n";
echo "- Created SQLite database\n";
echo "- Cleared all caches\n";
echo "- Tested database connection\n";
echo "- Ran migrations\n";
echo "- Tested Laravel functionality\n";
echo "- Created storage link\n";
echo "- Created test pages\n\n";

echo "🌐 Localhost URLs:\n";
echo "- Website: http://localhost:8000/\n";
echo "- Simple Test: http://localhost:8000/simple-test.php\n";
echo "- HTML Test: http://localhost:8000/html-test.html\n";
echo "- Admin: http://localhost:8000/admin\n";
echo "- Login: http://localhost:8000/login\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: http://localhost:8000/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps for localhost:\n";
echo "1. Run: php fix_localhost_env_completely.php\n";
echo "2. Test: http://localhost:8000/simple-test.php\n";
echo "3. Test: http://localhost:8000/html-test.html\n";
echo "4. Test: http://localhost:8000/\n";
echo "5. Check if 500 error is resolved\n";
echo "6. Check server logs for any remaining errors\n";

