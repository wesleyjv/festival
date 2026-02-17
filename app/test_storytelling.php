<?php
// Test script for storytelling events database connection
require_once 'src/Models/Database.php';
require_once 'src/Models/StorytellingEvent.php';

use App\Models\Database;
use App\Models\StorytellingEvent;

try {
    echo "Testing database connection...\n";
    
    // Test database connection
    $db = Database::getConnection();
    echo "✓ Database connection successful\n";
    
    // Test storytelling events
    echo "\nTesting storytelling events retrieval...\n";
    $storytelling = new StorytellingEvent($db);
    
    // Get all storytelling events
    $events = $storytelling->getStorytellingEvents();
    echo "✓ Found " . count($events) . " storytelling events\n";
    
    if (!empty($events)) {
        echo "\nFirst event details:\n";
        $firstEvent = $events[0];
        echo "- Title: " . $firstEvent['title'] . "\n";
        echo "- Date: " . $firstEvent['date'] . "\n";
        echo "- Time: " . $firstEvent['time'] . "\n";
        echo "- Location: " . $firstEvent['location_name'] . "\n";
        echo "- Price: €" . $firstEvent['price'] . "\n";
    }
    
    // Test featured storyteller
    echo "\nTesting featured storyteller...\n";
    $featured = $storytelling->getFeaturedStoryteller();
    if ($featured) {
        echo "✓ Featured storyteller found: " . $featured['title'] . "\n";
        echo "- Guide: " . $featured['guide_name'] . "\n";
        echo "- Language: " . $featured['language'] . "\n";
    } else {
        echo "⚠ No featured storyteller found\n";
    }
    
    // Test locations
    echo "\nTesting storytelling locations...\n";
    $locations = $storytelling->getStorytellingLocations();
    echo "✓ Found " . count($locations) . " locations\n";
    
    foreach ($locations as $location) {
        echo "- " . $location['name'] . " (" . $location['address'] . ")\n";
    }
    
    echo "\n✓ All tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
