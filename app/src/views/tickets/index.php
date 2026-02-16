<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Tickets</title>
    <style>
        .session-card { border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
        .btn { background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h1>Available Tickets</h1>
    <a href="/events/history">&larr; Back to Overview</a>
    
    <div class="sessions-list" style="margin-top: 20px;">
        <?php if (empty($sessions)): ?>
            <p>No tickets available for this event yet.</p>
        <?php else: ?>
            <?php foreach ($sessions as $session): ?>
                <div class="session-card">
                    <div>
                        <strong>Date:</strong> <?= $session->startTime->format('d M Y') ?><br>
                        <strong>Time:</strong> <?= $session->startTime->format('H:i') ?> - <?= $session->endTime->format('H:i') ?><br>
                        <strong>Price:</strong> €<?= number_format($session->price, 2) ?>
                    </div>
                    
                    <form action="/cart/add" method="POST">
                        <input type="hidden" name="session_id" value="<?= $session->id ?>">
                        <button type="submit" class="btn">Add to Cart</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>