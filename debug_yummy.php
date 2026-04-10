<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/yummy';

require 'vendor/autoload.php';

\ = __DIR__ . '/.env';
if (file_exists(\)) {
    \ = file(\, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach (\ as \) {
        if (str_starts_with(trim(\), '#')) { continue; }
        if (strpos(\, '=') !== false) { putenv(trim(\)); }
    }
}
\App\DB::initialize();

try {
    \ = new \App\Controllers\EventsController();
    \->yummy();
} catch (\Throwable \) {
    echo "CAUGHT EXCEPTION: " . \->getMessage() . "\n";
    echo \->getTraceAsString();
}
