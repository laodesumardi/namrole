<?php
/**
 * Check Database Simple
 */

echo "=== CHECKING DATABASE SIMPLE ===\n";

try {
    $pdo = new PDO('sqlite:database/database.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Available tables:\n";
    foreach ($tables as $table) {
        echo "- $table\n";
    }
    
    // Check if specific tables exist
    $requiredTables = ['home_sections', 'news', 'galleries', 'gallery_items', 'school_profiles', 'headmaster_greetings', 'facilities'];
    
    echo "\nChecking required tables:\n";
    foreach ($requiredTables as $table) {
        if (in_array($table, $tables)) {
            echo "✅ $table exists\n";
        } else {
            echo "❌ $table missing\n";
        }
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
