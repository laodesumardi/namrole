<?php
// Simple test to check if PHP is working
echo "PHP is working!<br>";
echo "Current time: " . date("Y-m-d H:i:s") . "<br>";
echo "PHP version: " . phpversion() . "<br>";

// Check if Laravel files exist
$laravelFiles = [
    "artisan",
    "composer.json",
    "bootstrap/app.php",
    "vendor/autoload.php"
];

echo "<br>Laravel files check:<br>";
foreach ($laravelFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file not found<br>";
    }
}

// Check if .env exists
if (file_exists(".env")) {
    echo "✅ .env file exists<br>";
} else {
    echo "❌ .env file not found<br>";
}

// Check if storage directory exists
if (is_dir("storage")) {
    echo "✅ storage directory exists<br>";
} else {
    echo "❌ storage directory not found<br>";
}

// Check if public directory exists
if (is_dir("public")) {
    echo "✅ public directory exists<br>";
} else {
    echo "❌ public directory not found<br>";
}
?>