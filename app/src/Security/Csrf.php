<?php

declare(strict_types=1);

namespace App\Security;


 //ession-bound CSRF tokens for form posts and AJAX (header / multipart field).
 
final class Csrf
{
    public const SESSION_KEY = '_csrf_token';

    public const FIELD_NAME = 'csrf_token';

    /** @var non-empty-string */
    public const HEADER_NAME = 'X-CSRF-Token';

    
     //Ensure a token exists and return it (call once per request after session_start).
     
    public static function getToken(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION[self::SESSION_KEY]) || !is_string($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::SESSION_KEY];
    }

    
     // HTML hidden input for traditional form posts.
     
    public static function field(): string
    {
        $t = htmlspecialchars(self::getToken(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        return '<input type="hidden" name="' . self::FIELD_NAME . '" value="' . $t . '">';
    }

    
     // True when POST body field or X-CSRF-Token header matches the session token.
    
    public static function validateRequest(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $expected = $_SESSION[self::SESSION_KEY] ?? '';
        if ($expected === '' || !is_string($expected)) {
            return false;
        }

        $fromPost = $_POST[self::FIELD_NAME] ?? null;
        if (is_string($fromPost) && hash_equals($expected, $fromPost)) {
            return true;
        }

        $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (is_string($header) && $header !== '' && hash_equals($expected, $header)) {
            return true;
        }

        return false;
    }
}
