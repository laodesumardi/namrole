<?php
echo "=== SIMPLE HOSTING DEPLOYMENT ===\n";

// Fix permissions
exec("chmod -R 777 storage/");
exec("chmod -R 777 public/storage/");
exec("chmod -R 777 public/uploads/");
exec("chmod -R 666 storage/*");
exec("chmod -R 666 public/storage/*");
exec("chmod -R 666 public/uploads/*");

// Copy images
exec("cp -r storage/app/public/* public/storage/");
exec("cp -r storage/app/public/* public/uploads/");

echo "=== DEPLOYMENT COMPLETE ===\n";
echo "Website: https://uji.odetune.shop/\n";
?>