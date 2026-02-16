<?php
// Simple database debug test
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$host = 'haarlem-festival-dev-haarlemfestival123.i.aivencloud.com';
$port = '17152';
$dbname = 'defaultdb';
$username = 'avnadmin';
$password = 'AVNS_SzVg-JB6UsaKcS1pDWY';

try {
    echo "Attempting database connection...\n";
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✓ Database connection successful!\n";
    
    // Check if tables exist
    echo "\nChecking tables...\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(', ', $tables) . "\n";
    
    // Check events table structure
    if (in_array('events', $tables)) {
        echo "\nEvents table structure:\n";
        $columns = $pdo->query("DESCRIBE events")->fetchAll();
        foreach ($columns as $col) {
            echo "- {$col['Field']} ({$col['Type']})\n";
        }
        
        // Check for storytelling events
        echo "\nChecking for storytelling events...\n";
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM events WHERE type = 'story'");
        $result = $stmt->fetch();
        echo "Events with type 'story': {$result['count']}\n";
        
        // Show sample events
        $stmt = $pdo->query("SELECT id, name, type FROM events LIMIT 5");
        $events = $stmt->fetchAll();
        echo "\nSample events:\n";
        foreach ($events as $event) {
            echo "- ID: {$event['id']}, Name: {$event['name']}, Type: {$event['type']}\n";
        }
    }
    
    // Check story_events table
    if (in_array('story_events', $tables)) {
        echo "\nStory_events table structure:\n";
        $columns = $pdo->query("DESCRIBE story_events")->fetchAll();
        foreach ($columns as $col) {
            echo "- {$col['Field']} ({$col['Type']})\n";
        }
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM story_events");
        $result = $stmt->fetch();
        echo "Story events count: {$result['count']}\n";
    }
    
    // Check sessions table
    if (in_array('sessions', $tables)) {
        echo "\nSessions table structure:\n";
        $columns = $pdo->query("DESCRIBE sessions")->fetchAll();
        foreach ($columns as $col) {
            echo "- {$col['Field']} ({$col['Type']})\n";
        }
        
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM sessions");
        $result = $stmt->fetch();
        echo "Sessions count: {$result['count']}\n";
    }
    
    // Test the exact query we're using
    echo "\nTesting exact query...\n";
    $query = "
        SELECT 
            e.id,
            e.name,
            e.description,
            e.image,
            se.guide_name,
            se.language,
            s.id as session_id,
            s.start_time,
            s.end_time,
            s.capacity,
            s.price,
            l.name as location_name,
            l.address as location_address
        FROM events e
        INNER JOIN story_events se ON e.id = se.event_id
        INNER JOIN sessions s ON e.id = s.event_id
        INNER JOIN locations l ON s.location_id = l.id
        WHERE e.type = 'story'
        ORDER BY s.start_time ASC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    
    echo "Query results: " . count($results) . " rows\n";
    
    if (!empty($results)) {
        echo "First result:\n";
        print_r($results[0]);
    }
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
