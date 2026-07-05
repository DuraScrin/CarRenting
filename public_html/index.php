<?php
declare(strict_types=1);

session_start();

$router = require __DIR__ . '/src/routes/web.php';

if ($router instanceof Router) {
    $router->run();
    exit;
}

http_response_code(500);
echo 'Router not initialized.';
