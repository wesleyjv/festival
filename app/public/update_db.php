<?php
require __DIR__ . '/../src/Database.php';
$db = App\Database::getConnection();

try {
    $db->exec("ALTER TABLE tickets ADD COLUMN event_date VARCHAR(50) NULL AFTER name");
    $db->exec("ALTER TABLE tickets ADD COLUMN event_time VARCHAR(50) NULL AFTER event_date");
    $db->exec("ALTER TABLE tickets ADD COLUMN event_language VARCHAR(50) NULL AFTER event_time");
    echo "Columns added successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
