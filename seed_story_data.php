<?php
// Seed storytelling events with sessions and locations
require_once 'app/src/Models/Database.php';

use App\Models\Database;

try {
    $db = Database::getConnection();
    echo "Connected to database\n";
    
    // First, add locations if they don't exist
    echo "Adding locations...\n";
    $locations = [
        ['name' => 'Grote Markt', 'address' => 'Grote Markt, Haarlem', 'capacity' => 500],
        ['name' => 'St. Bavokerk', 'address' => 'St. Bavokerk, Haarlem', 'capacity' => 200],
        ['name' => 'Frans Hals Museum', 'address' => 'Frans Hals Museum, Haarlem', 'capacity' => 100]
    ];
    
    foreach ($locations as $location) {
        $stmt = $db->prepare("INSERT IGNORE INTO locations (name, address, capacity) VALUES (?, ?, ?)");
        $stmt->execute([$location['name'], $location['address'], $location['capacity']]);
    }
    
    // Get location IDs
    $locationStmt = $db->prepare("SELECT id, name FROM locations WHERE name = ?");
    $groteMarktId = null;
    $stBavokerkId = null;
    $fransHalsId = null;
    
    $locationStmt->execute(['Grote Markt']);
    $result = $locationStmt->fetch();
    if ($result) $groteMarktId = $result['id'];
    
    $locationStmt->execute(['St. Bavokerk']);
    $result = $locationStmt->fetch();
    if ($result) $stBavokerkId = $result['id'];
    
    $locationStmt->execute(['Frans Hals Museum']);
    $result = $locationStmt->fetch();
    if ($result) $fransHalsId = $result['id'];
    
    echo "Location IDs: Grote Markt=$groteMarktId, St. Bavokerk=$stBavokerkId, Frans Hals=$fransHalsId\n";
    
    // Get existing story events
    echo "Finding story events...\n";
    $eventsStmt = $db->prepare("SELECT id, name FROM events WHERE type = 'story'");
    $eventsStmt->execute();
    $storyEvents = $eventsStmt->fetchAll();
    
    foreach ($storyEvents as $event) {
        echo "Found story event: {$event['id']} - {$event['name']}\n";
        
        // Add story_events record if not exists
        $storyEventStmt = $db->prepare("INSERT IGNORE INTO story_events (event_id, guide_name, language) VALUES (?, ?, ?)");
        $storyEventStmt->execute([$event['id'], 'Elena van der Meer', 'English']);
        
        // Add sessions for this event
        $sessions = [
            [
                'event_id' => $event['id'],
                'location_id' => $groteMarktId,
                'start_time' => '2026-07-24 19:00:00',
                'end_time' => '2026-07-24 21:00:00',
                'capacity' => 100,
                'price' => 15.00
            ],
            [
                'event_id' => $event['id'],
                'location_id' => $stBavokerkId,
                'start_time' => '2026-07-25 20:30:00',
                'end_time' => '2026-07-25 22:30:00',
                'capacity' => 150,
                'price' => 20.00
            ],
            [
                'event_id' => $event['id'],
                'location_id' => $fransHalsId,
                'start_time' => '2026-07-26 10:00:00',
                'end_time' => '2026-07-26 11:30:00',
                'capacity' => 50,
                'price' => 10.00
            ],
            [
                'event_id' => $event['id'],
                'location_id' => $groteMarktId,
                'start_time' => '2026-07-27 18:00:00',
                'end_time' => '2026-07-27 20:00:00',
                'capacity' => 120,
                'price' => 18.00
            ]
        ];
        
        foreach ($sessions as $session) {
            $sessionStmt = $db->prepare("INSERT IGNORE INTO sessions (event_id, location_id, start_time, end_time, capacity, price) VALUES (?, ?, ?, ?, ?, ?)");
            $sessionStmt->execute([
                $session['event_id'],
                $session['location_id'],
                $session['start_time'],
                $session['end_time'],
                $session['capacity'],
                $session['price']
            ]);
        }
    }
    
    echo "Data seeding completed!\n";
    
    // Test the query
    echo "\nTesting query...\n";
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
        LEFT JOIN story_events se ON e.id = se.event_id
        LEFT JOIN sessions s ON e.id = s.event_id
        LEFT JOIN locations l ON s.location_id = l.id
        WHERE e.type = 'story'
        ORDER BY s.start_time ASC
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $results = $stmt->fetchAll();
    
    echo "Query returned " . count($results) . " results\n";
    foreach ($results as $result) {
        echo "- {$result['name']} at {$result['location_name']} on {$result['start_time']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
