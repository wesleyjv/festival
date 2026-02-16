<?php
// Test database connection and query
require_once 'app/src/Models/Database.php';
require_once 'app/src/Models/StorytellingEvent.php';

use App\Models\Database;
use App\Models\StorytellingEvent;

try {
    echo "Testing database connection...\n";
    $db = Database::getConnection();
    echo "Database connection successful!\n";
    
    echo "Testing StorytellingEvent model...\n";
    $storytellingModel = new StorytellingEvent($db);
    
    echo "Getting storytelling events...\n";
    $events = $storytellingModel->getStorytellingEvents();
    
    echo "Found " . count($events) . " events\n";
    
    if (!empty($events)) {
        echo "First event: " . $events[0]['title'] . "\n";
        print_r($events[0]);
    }
    
    echo "Testing locations...\n";
    $locations = $storytellingModel->getStorytellingLocations();
    echo "Found " . count($locations) . " locations\n";
    
    echo "Testing featured storyteller...\n";
    $featured = $storytellingModel->getFeaturedStoryteller();
    echo "Featured: " . ($featured ? $featured['title'] : 'None') . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
?>
