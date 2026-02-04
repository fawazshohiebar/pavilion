<?php
// Bypass Laravel - direct PHP diagnostic
phpinfo();
echo "\n\n=== ENVIRONMENT ===\n";
echo "APP_ENV: " . ($_ENV['APP_ENV'] ?? 'NOT SET') . "\n";
echo "APP_DEBUG: " . ($_ENV['APP_DEBUG'] ?? 'NOT SET') . "\n";
echo "DB_CONNECTION: " . ($_ENV['DB_CONNECTION'] ?? 'NOT SET') . "\n";
echo "DB_DATABASE: " . ($_ENV['DB_DATABASE'] ?? 'NOT SET') . "\n";

echo "\n\n=== FILES ===\n";
echo "Database exists: " . (file_exists('/var/www/html/database/database.sqlite') ? 'YES' : 'NO') . "\n";
echo "Storage writable: " . (is_writable('/var/www/html/storage') ? 'YES' : 'NO') . "\n";

echo "\n\n=== PHP EXTENSIONS ===\n";
print_r(get_loaded_extensions());
