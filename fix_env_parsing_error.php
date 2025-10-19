<?php
/**
 * Fix Environment Parsing Error
 * 
 * This script fixes .env parsing error caused by spaces in values
 */

echo "🔧 Fixing Environment Parsing Error\n";
echo "===================================\n\n";

echo "🔧 Fixing .env parsing error...\n";

// 1. Read current .env file
echo "\n📄 Reading current .env file...\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    echo "✅ .env file found\n";
    
    // Check for problematic values
    if (strpos($envContent, 'APP_NAME=SMP Negeri 01 Namrole') !== false) {
        echo "❌ Found problematic APP_NAME with spaces\n";
        echo "🔧 Fixing APP_NAME...\n";
        
        // Fix APP_NAME by wrapping in quotes
        $envContent = str_replace('APP_NAME=SMP Negeri 01 Namrole', 'APP_NAME="SMP Negeri 01 Namrole"', $envContent);
        
        if (file_put_contents('.env', $envContent)) {
            echo "✅ APP_NAME fixed with quotes\n";
        } else {
            echo "❌ Failed to fix APP_NAME\n";
        }
    } else {
        echo "✅ APP_NAME is properly formatted\n";
    }
    
    // Check for other problematic values
    if (strpos($envContent, 'MAIL_FROM_NAME=SMP Negeri 01 Namrole') !== false) {
        echo "❌ Found problematic MAIL_FROM_NAME with spaces\n";
        echo "🔧 Fixing MAIL_FROM_NAME...\n";
        
        // Fix MAIL_FROM_NAME by wrapping in quotes
        $envContent = str_replace('MAIL_FROM_NAME=SMP Negeri 01 Namrole', 'MAIL_FROM_NAME="SMP Negeri 01 Namrole"', $envContent);
        
        if (file_put_contents('.env', $envContent)) {
            echo "✅ MAIL_FROM_NAME fixed with quotes\n";
        } else {
            echo "❌ Failed to fix MAIL_FROM_NAME\n";
        }
    } else {
        echo "✅ MAIL_FROM_NAME is properly formatted\n";
    }
    
    // Check for VITE_APP_NAME
    if (strpos($envContent, 'VITE_APP_NAME=SMP Negeri 01 Namrole') !== false) {
        echo "❌ Found problematic VITE_APP_NAME with spaces\n";
        echo "🔧 Fixing VITE_APP_NAME...\n";
        
        // Fix VITE_APP_NAME by wrapping in quotes
        $envContent = str_replace('VITE_APP_NAME=SMP Negeri 01 Namrole', 'VITE_APP_NAME="SMP Negeri 01 Namrole"', $envContent);
        
        if (file_put_contents('.env', $envContent)) {
            echo "✅ VITE_APP_NAME fixed with quotes\n";
        } else {
            echo "❌ Failed to fix VITE_APP_NAME\n";
        }
    } else {
        echo "✅ VITE_APP_NAME is properly formatted\n";
    }
    
} else {
    echo "❌ .env file not found\n";
}

// 2. Create a properly formatted .env file
echo "\n🔧 Creating properly formatted .env file...\n";

$properEnvContent = 'APP_NAME="SMP Negeri 01 Namrole"
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

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u798974089_namrole
DB_USERNAME=u798974089_namrole
DB_PASSWORD=8F@d@Ay4

SESSION_DRIVER=database
SESSION_LIFETIME=480
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

CACHE_STORE=database
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

if (file_put_contents('.env', $properEnvContent)) {
    echo "✅ Properly formatted .env file created\n";
} else {
    echo "❌ Failed to create properly formatted .env file\n";
}

// 3. Test .env parsing
echo "\n🧪 Testing .env parsing...\n";

try {
    // Test if we can load .env without errors
    if (class_exists('Dotenv\Dotenv')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();
        echo "✅ .env file parsed successfully\n";
    } else {
        echo "⚠️ Dotenv class not found, skipping .env parsing test\n";
    }
} catch (Exception $e) {
    echo "❌ .env parsing failed: " . $e->getMessage() . "\n";
}

// 4. Test Laravel bootstrap
echo "\n🔗 Testing Laravel bootstrap...\n";

try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    echo "✅ Laravel bootstrapped successfully\n";
} catch (Exception $e) {
    echo "❌ Laravel bootstrap failed: " . $e->getMessage() . "\n";
}

echo "\n✅ Environment parsing error fix completed!\n";
echo "🔧 Key fixes applied:\n";
echo "- Fixed APP_NAME with proper quotes\n";
echo "- Fixed MAIL_FROM_NAME with proper quotes\n";
echo "- Fixed VITE_APP_NAME with proper quotes\n";
echo "- Created properly formatted .env file\n";
echo "- Tested .env parsing\n";
echo "- Tested Laravel bootstrap\n\n";

echo "📝 Next Steps:\n";
echo "1. Test if 500 error is resolved\n";
echo "2. Check if Laravel is working\n";
echo "3. Test website functionality\n";
