<?php
require dirname(__DIR__).'/vendor/autoload.php';
$root = dirname(__DIR__);
Dotenv\Dotenv::createImmutable($root)->load();
$name = $_ENV['DB_DATABASE'];
if (!preg_match('/^[a-z0-9_]+$/i', $name)) {
    throw new RuntimeException('Use only letters, digits and underscores in DB_DATABASE.');
}
$db = new PDO('mysql:host='.$_ENV['DB_HOST'].';port='.$_ENV['DB_PORT'].';charset=utf8mb4',
    $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'] ?? '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$db->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "Database ready: $name\n";
