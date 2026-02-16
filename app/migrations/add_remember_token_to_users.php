<?php

/**
 * Migration: Add remember_token column to users table.
 *
 * Run once:  php app/migrations/add_remember_token_to_users.php
 */

require __DIR__ . '/../vendor/autoload.php';

$envPath = __DIR__ . '/../../.env';
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

$db = App\Database::getConnection();

$db->exec("ALTER TABLE users ADD COLUMN remember_token VARCHAR(64) DEFAULT NULL");

echo "Migration complete: remember_token column added to users table.\n";
