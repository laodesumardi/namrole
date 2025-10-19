<?php
echo "<h1>PHP Information</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Server: " . $_SERVER["SERVER_SOFTWARE"] . "</p>";
echo "<p>Document Root: " . $_SERVER["DOCUMENT_ROOT"] . "</p>";
echo "<p>Script Name: " . $_SERVER["SCRIPT_NAME"] . "</p>";
echo "<p>Request URI: " . $_SERVER["REQUEST_URI"] . "</p>";

// Show PHP info
phpinfo();
?>