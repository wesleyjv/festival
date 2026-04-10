<?php
require __DIR__ . '/../src/Database.php';
$db = App\Database::getConnection();

$stmt = $db->query("SELECT * FROM events");
while ($row = $stmt->fetch()) {
    print_r($row);
}
