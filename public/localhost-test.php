<?php
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
?>