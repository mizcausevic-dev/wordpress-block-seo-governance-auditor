<?php

declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($path !== false) {
    $candidate = __DIR__ . '/public' . $path;
    if ($path !== '/' && is_file($candidate)) {
        return false;
    }
}

require __DIR__ . '/public/index.php';
