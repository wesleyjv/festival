<?php
require __DIR__ . '/../src/Database.php';
$db = App\Database::getConnection();

function describe($db, $table) {
    echo "--- $table ---\n";
    $stmt = $db->query("DESCRIBE `$table` ");
    while ($row = $stmt->fetch()) {
        echo "{$row['Field']} {$row['Type']}\n";
    }
    echo "\n";
}

describe($db, 'history_events');
describe($db, 'tickets');
describe($db, 'order_items');
