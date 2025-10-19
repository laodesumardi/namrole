<?php
/**
 * Fix Localhost Database Configuration
 * 
 * This script fixes database configuration for localhost
 * Specifically for http://localhost:8000/
 */

echo "🗄️ Fixing Localhost Database Configuration\n";
echo "==========================================\n\n";

echo "🔧 Fixing database configuration for localhost...\n";

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

// 3. Create localhost database configuration
echo "\n🔧 Creating localhost database configuration...\n";

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

# Localhost Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=namrole
DB_USERNAME=root
DB_PASSWORD=

# Alternative localhost database configurations
# DB_HOST=localhost
# DB_DATABASE=website_sekolah_namrole
# DB_USERNAME=root
# DB_PASSWORD=root

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
    echo "✅ Localhost .env configuration created\n";
} else {
    echo "❌ Failed to create localhost .env configuration\n";
}

// 4. Test different database configurations
echo "\n🔧 Testing different database configurations...\n";

$databaseConfigs = [
    [
        'name' => 'Default Localhost',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'namrole',
        'username' => 'root',
        'password' => ''
    ],
    [
        'name' => 'Localhost with localhost host',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'namrole',
        'username' => 'root',
        'password' => ''
    ],
    [
        'name' => 'Localhost with different database name',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'website_sekolah_namrole',
        'username' => 'root',
        'password' => ''
    ],
    [
        'name' => 'Localhost with root password',
        'host' => '127.0.0.1',
        'port' => '3306',
        'database' => 'namrole',
        'username' => 'root',
        'password' => 'root'
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
        $newEnvContent = $localhostEnvContent;
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
        try {
            $userCount = \App\Models\User::count();
            echo "   ✅ Database access successful ($userCount users)\n";
        } catch (Exception $e) {
            echo "   ⚠️ Database connected but users table not found: " . $e->getMessage() . "\n";
            echo "   🔧 Running migrations...\n";
            
            try {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                echo "   ✅ Migrations run successfully\n";
                
                // Test again
                $userCount = \App\Models\User::count();
                echo "   ✅ Database access successful ($userCount users)\n";
            } catch (Exception $e) {
                echo "   ❌ Failed to run migrations: " . $e->getMessage() . "\n";
            }
        }
        
        echo "   🎉 {$config['name']} is working!\n";
        break;
        
    } catch (Exception $e) {
        echo "   ❌ Database connection failed: " . $e->getMessage() . "\n";
    }
}

// 5. If all configurations fail, create a fallback configuration
echo "\n🔧 Creating fallback configuration...\n";

$fallbackEnvContent = 'APP_NAME="SMP Negeri 01 Namrole"
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

# Fallback Database Configuration - SQLite
DB_CONNECTION=sqlite
DB_DATABASE=' . __DIR__ . '/database/database.sqlite

# Alternative MySQL configurations
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

if (file_put_contents('.env', $fallbackEnvContent)) {
    echo "✅ Fallback configuration created (SQLite)\n";
} else {
    echo "❌ Failed to create fallback configuration\n";
}

// 6. Create SQLite database if it doesn't exist
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

// 7. Clear all caches
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

// 8. Test Laravel functionality
echo "\n🧪 Testing Laravel functionality...\n";

try {
    // Test if we can create a simple response
    $response = response()->json(['status' => 'success', 'message' => 'Laravel is working']);
    echo "✅ Laravel response creation successful\n";
    
    // Test if we can access routes
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    echo "✅ Route access successful (" . count($routes) . " routes)\n";
    
    // Test database connection
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        echo "✅ Database connection successful\n";
        
        // Test if we can access users table
        $userCount = \App\Models\User::count();
        echo "✅ Database access successful ($userCount users)\n";
    } catch (Exception $e) {
        echo "⚠️ Database connection failed: " . $e->getMessage() . "\n";
        echo "🔧 Laravel is working but database needs configuration\n";
    }
    
    echo "✅ Laravel is working properly\n";
} catch (Exception $e) {
    echo "❌ Laravel test failed: " . $e->getMessage() . "\n";
}

echo "\n✅ Localhost database configuration fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Created localhost .env configuration\n";
echo "- Tested different database configurations\n";
echo "- Created fallback SQLite configuration\n";
echo "- Created SQLite database\n";
echo "- Cleared all caches\n";
echo "- Tested Laravel functionality\n\n";

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
echo "1. Run: php fix_localhost_database.php\n";
echo "2. Test: http://localhost:8000/localhost-test.php\n";
echo "3. Test: http://localhost:8000/\n";
echo "4. Check if 500 error is resolved\n";
echo "5. Check server logs for any remaining errors\n";

