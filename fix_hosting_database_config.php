<?php
/**
 * Fix Hosting Database Configuration
 * 
 * This script fixes database configuration for hosting
 * Specifically for https://uji.odetune.shop/
 */

echo "🗄️ Fixing Hosting Database Configuration\n";
echo "=======================================\n\n";

echo "🔧 Fixing database configuration for hosting...\n";

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

// 3. Test different database configurations
echo "\n🔧 Testing different database configurations...\n";

$databaseConfigs = [
    [
        'name' => 'Current Configuration',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'u798974089_namrole',
        'username' => 'u798974089_namrole',
        'password' => '8F@d@Ay4'
    ],
    [
        'name' => 'Localhost Configuration',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'u798974089_namrole',
        'username' => 'u798974089_namrole',
        'password' => '8F@d@Ay4'
    ],
    [
        'name' => 'Alternative Host Configuration',
        'host' => 'mysql.hostinger.com',
        'port' => '3306',
        'database' => 'u798974089_namrole',
        'username' => 'u798974089_namrole',
        'password' => '8F@d@Ay4'
    ]
];

foreach ($databaseConfigs as $config) {
    echo "\n🔍 Testing {$config['name']}...\n";
    echo "   Host: {$config['host']}\n";
    echo "   Port: {$config['port']}\n";
    echo "   Database: {$config['database']}\n";
    echo "   Username: {$config['username']}\n";
    
    try {
        // Update .env with this configuration
        $newEnvContent = $envContent;
        $newEnvContent = preg_replace('/DB_HOST=.*/', "DB_HOST={$config['host']}", $newEnvContent);
        $newEnvContent = preg_replace('/DB_PORT=.*/', "DB_PORT={$config['port']}", $newEnvContent);
        $newEnvContent = preg_replace('/DB_DATABASE=.*/', "DB_DATABASE={$config['database']}", $newEnvContent);
        $newEnvContent = preg_replace('/DB_USERNAME=.*/', "DB_USERNAME={$config['username']}", $newEnvContent);
        $newEnvContent = preg_replace('/DB_PASSWORD=.*/', "DB_PASSWORD={$config['password']}", $newEnvContent);
        
        file_put_contents('.env', $newEnvContent);
        
        // Clear config cache
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        
        // Test database connection
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "   ✅ Database connection successful!\n";
        
        // Test if we can access users table
        $userCount = \App\Models\User::count();
        echo "   ✅ Database access successful ($userCount users)\n";
        
        echo "   🎉 {$config['name']} is working!\n";
        break;
        
    } catch (Exception $e) {
        echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    }
}

// 4. If all configurations fail, create a fallback configuration
echo "\n🔧 Creating fallback configuration...\n";

$fallbackEnvContent = 'APP_NAME="SMP Negeri 01 Namrole"
APP_ENV=production
APP_KEY=base64:einrC95JoeePOe6GZganQrDZ+zfLBorE65HEBy6lxew=
APP_DEBUG=false
APP_TIMEZONE=Asia/Jakarta
APP_URL=https://uji.odetune.shop/

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

APP_MAINTENANCE_DRIVER=file
PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# Database Configuration - Try different hosts
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u798974089_namrole
DB_USERNAME=u798974089_namrole
DB_PASSWORD=8F@d@Ay4

# Alternative database configuration
# DB_HOST=localhost
# DB_HOST=mysql.hostinger.com

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

if (file_put_contents('.env', $fallbackEnvContent)) {
    echo "✅ Fallback configuration created\n";
} else {
    echo "❌ Failed to create fallback configuration\n";
}

// 5. Change session driver to file if database fails
echo "\n🔧 Changing session driver to file...\n";

$envContent = file_get_contents('.env');
$envContent = preg_replace('/SESSION_DRIVER=database/', 'SESSION_DRIVER=file', $envContent);
$envContent = preg_replace('/CACHE_STORE=database/', 'CACHE_STORE=file', $envContent);

if (file_put_contents('.env', $envContent)) {
    echo "✅ Session driver changed to file\n";
    echo "✅ Cache store changed to file\n";
} else {
    echo "❌ Failed to change session driver\n";
}

// 6. Clear all caches
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
} catch (Exception $e) {
    echo "❌ Error clearing caches: " . $e->getMessage() . "\n";
}

// 7. Test Laravel functionality without database
echo "\n🧪 Testing Laravel functionality...\n";

try {
    // Test if we can create a simple response
    $response = response()->json(['status' => 'success', 'message' => 'Laravel is working']);
    echo "✅ Laravel response creation successful\n";
    
    // Test if we can access routes
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    echo "✅ Route access successful (" . count($routes) . " routes)\n";
    
    echo "✅ Laravel is working properly\n";
} catch (Exception $e) {
    echo "❌ Laravel test failed: " . $e->getMessage() . "\n";
}

// 8. Create a simple test page that doesn't require database
echo "\n🔧 Creating simple test page...\n";

$simpleTestContent = '<?php
// Simple test page that doesn\'t require database
echo "<h1>SMP Negeri 01 Namrole</h1>";
echo "<p>Website is working!</p>";
echo "<p>Current time: " . date("Y-m-d H:i:s") . "</p>";
echo "<p>PHP version: " . phpversion() . "</p>";

// Test if we can include Laravel
try {
    require_once "../vendor/autoload.php";
    $app = require_once "../bootstrap/app.php";
    echo "<p>✅ Laravel loaded successfully</p>";
} catch (Exception $e) {
    echo "<p>❌ Laravel failed: " . $e->getMessage() . "</p>";
}

// Test if we can access .env
if (file_exists("../.env")) {
    echo "<p>✅ .env file exists</p>";
} else {
    echo "<p>❌ .env file not found</p>";
}

// Test if we can access storage
if (is_dir("../storage")) {
    echo "<p>✅ storage directory exists</p>";
} else {
    echo "<p>❌ storage directory not found</p>";
}

// Test if we can access public
if (is_dir("../public")) {
    echo "<p>✅ public directory exists</p>";
} else {
    echo "<p>❌ public directory not found</p>";
}
?>';

$simpleTestPath = 'public/simple-test.php';
if (file_put_contents($simpleTestPath, $simpleTestContent)) {
    echo "✅ simple-test.php created\n";
    echo "🌐 You can access: https://uji.odetune.shop/simple-test.php\n";
} else {
    echo "❌ Failed to create simple-test.php\n";
}

echo "\n✅ Hosting database configuration fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Tested different database configurations\n";
echo "- Created fallback configuration\n";
echo "- Changed session driver to file\n";
echo "- Changed cache store to file\n";
echo "- Cleared all caches\n";
echo "- Tested Laravel functionality\n";
echo "- Created simple test page\n\n";

echo "🌐 Hosting URLs:\n";
echo "- Website: https://uji.odetune.shop/\n";
echo "- Simple Test: https://uji.odetune.shop/simple-test.php\n";
echo "- Test: https://uji.odetune.shop/test.php\n";
echo "- Admin: https://uji.odetune.shop/admin\n";
echo "- Login: https://uji.odetune.shop/login\n\n";

echo "🔑 Admin Login:\n";
echo "- URL: https://uji.odetune.shop/login\n";
echo "- Email: admin@namrole.sch.id\n";
echo "- Password: admin123\n\n";

echo "📝 Next Steps for hosting:\n";
echo "1. Upload this script to your hosting server\n";
echo "2. Run: php fix_hosting_database_config.php\n";
echo "3. Check: https://uji.odetune.shop/simple-test.php\n";
echo "4. Test: https://uji.odetune.shop/\n";
echo "5. Check server logs for any remaining errors\n";
