<?php
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
    $app->make("Illuminate\Contracts\Console\Kernel")->bootstrap();
    echo "<p>✅ Laravel bootstrap successful</p>";
    
    // Test database
    try {
        $userCount = \App\Models\User::count();
        echo "<p>✅ Database access successful ($userCount users)</p>";
    } catch (Exception $e) {
        echo "<p>⚠️ Database access failed: " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Laravel bootstrap failed: " . $e->getMessage() . "</p>";
}
?>