<?php

declare(strict_types=1);

namespace App\Controllers;

class HelloController
{
    use HandlesControllerErrors;

    public function greet(array $vars = []): void
    {
        try {
            $name = (string) ($vars['name'] ?? 'world');
            echo 'Hello, ' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        } catch (\Throwable $e) {
            $this->logControllerThrowable($e);
            $this->respondWithServerError();
        }
    }
}
