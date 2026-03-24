<?php

/**
 * Standalone contact demo (also reachable outside the front controller).
 */

require __DIR__ . '/../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
\App\Security\Csrf::getToken();

/**
 * PHP has several global variables that are available in all scopes.
 *
 * $_SERVER is a PHP superglobal array that holds information about the server, the request, and the current script.
 *
 * Which $_SERVER variable can we use to determine the request method?
 * Answer: $_SERVER['REQUEST_METHOD']
 */

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!\App\Security\Csrf::validateRequest()) {
        http_response_code(403);
        echo 'Invalid session. Please reload the page and try again.';
        exit;
    }

    // Collect and sanitize input
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Basic validation
    if ($name && $email && $message && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Here you could send an email or save the data to a database
        ?>
        <h2>Thank you, <?php echo $name; ?>!</h2>
        <p>Your message has been received:</p>
        <blockquote><?php echo nl2br($message); ?></blockquote>
        <p>We'll contact you at <strong><?php echo $email; ?></strong> soon.</p>
        <?php
    } else {
        ?>
        <p style='color:red;'>Please fill in all fields with valid information.</p>
        <?php
    }
    ?>
    <p><a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">Back to form</a></p>
    <?php
} else {
    ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <?= \App\Security\Csrf::field() ?>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="5" cols="30" required></textarea><br><br>

        <button type="submit">Send</button>
    </form>
    <?php
}
