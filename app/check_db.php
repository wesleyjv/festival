<?php
require_once 'src/Models/Database.php';

$db = App\Models\Database::getConnection();

echo "Checking database tables...\n";
$stmt = $db->query('SHOW TABLES');
while ($row = $stmt->fetch()) {
    echo "- " . $row[0] . "\n";
}

echo "\nChecking events table structure...\n";
$stmt = $db->query('DESCRIBE events');
while ($row = $stmt->fetch()) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\nChecking for any events...\n";
$stmt = $db->query('SELECT COUNT(*) as count FROM events');
$result = $stmt->fetch();
echo "Total events: " . $result['count'] . "\n";

if ($result['count'] > 0) {
    echo "\nFirst few events:\n";
    $stmt = $db->query('SELECT id, name, type FROM events LIMIT 5');
    while ($row = $stmt->fetch()) {
        echo "- ID: " . $row['id'] . ", Name: " . $row['name'] . ", Type: " . $row['type'] . "\n";
    }
}
?>
