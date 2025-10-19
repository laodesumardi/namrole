<?php
echo "<h1>Directory Listing</h1>";
echo "<p>Current directory: " . getcwd() . "</p>";
echo "<p>Files in current directory:</p>";
echo "<ul>";

$files = scandir(".");
foreach ($files as $file) {
    if ($file != "." && $file != "..") {
        echo "<li>$file</li>";
    }
}

echo "</ul>";

echo "<p>Files in parent directory:</p>";
echo "<ul>";

$parentFiles = scandir("..");
foreach ($parentFiles as $file) {
    if ($file != "." && $file != "..") {
        echo "<li>$file</li>";
    }
}

echo "</ul>";
?>