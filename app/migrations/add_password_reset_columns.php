<?php

/**
 * Adds password reset columns to `users` if they are missing.
 *
 * From project root (with Docker):
 *   docker compose exec php php /app/migrations/add_password_reset_columns.php
 *
 * Locally (from app/ with DB env set):
 *   php migrations/add_password_reset_columns.php
 */

declare(strict_types=1);

$appRoot = dirname(__DIR__);
require $appRoot . '/vendor/autoload.php';

$envPath = dirname($appRoot) . DIRECTORY_SEPARATOR . '.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#')) {
            continue;
        }
        if (strpos($line, '=') !== false) {
            putenv(trim($line));
        }
    }
}

use App\Database;

$pdo = Database::getConnection();

$dbName = $pdo->query('SELECT DATABASE()')->fetchColumn();
if (!is_string($dbName) || $dbName === '') {
    fwrite(STDERR, "Could not determine current database name.\n");
    exit(1);
}

$check = $pdo->prepare(
    'SELECT COUNT(*) FROM information_schema.COLUMNS
     WHERE TABLE_SCHEMA = :schema AND TABLE_NAME = :table AND COLUMN_NAME = :column'
);
$check->execute([
    'schema' => $dbName,
    'table'  => 'users',
    'column' => 'password_reset_token',
]);

if ((int) $check->fetchColumn() > 0) {
    echo "password_reset columns already exist on users.\n";
    exit(0);
}

$pdo->exec(
    'ALTER TABLE users
        ADD COLUMN password_reset_token VARCHAR(64) NULL DEFAULT NULL,
        ADD COLUMN password_reset_expires_at DATETIME NULL DEFAULT NULL'
);

echo "Added password_reset_token and password_reset_expires_at to users.\n";
