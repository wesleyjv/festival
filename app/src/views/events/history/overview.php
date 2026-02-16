<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Haarlem History</title>
    </head>
<body>
    <h1>Historic Haarlem</h1>
    <div class="events-list">
        <?php foreach ($events as $event): ?>
            <div class="event-item" style="border:1px solid #ccc; margin:10px; padding:10px;">
                <h2><?= htmlspecialchars($event->name) ?></h2>
                <p><?= htmlspecialchars($event->description) ?></p>
                
                <p><strong>Guide:</strong> <?= htmlspecialchars($event->guide) ?></p>
                <p><strong>Language:</strong> <?= htmlspecialchars($event->language) ?></p>
                
                <a href="/tickets?event_id=<?= $event->id ?>">View Tickets</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>