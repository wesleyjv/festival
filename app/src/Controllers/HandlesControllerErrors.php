<?php

declare(strict_types=1);

namespace App\Controllers;

use Throwable;

 // Shared logging and HTTP response for uncaught controller errors.
 
trait HandlesControllerErrors
{
    protected function logControllerThrowable(Throwable $e): void
    {
        error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        error_log($e->getTraceAsString());
    }

    
     // @param array<string, mixed>|null $jsonBody
     
    protected function respondWithServerError(bool $json = false, ?array $jsonBody = null): never
    {
        if (!headers_sent()) {
            http_response_code(500);
            if ($json) {
                header('Content-Type: application/json; charset=utf-8');
            }
        }

        if ($json) {
            $body = $jsonBody ?? ['ok' => false, 'error' => 'Something went wrong.'];
            echo json_encode($body);
        } else {
            echo 'Something went wrong. Please try again later.';
        }

        exit;
    }
}
