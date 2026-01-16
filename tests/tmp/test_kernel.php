<?php
require __DIR__ . '/../../vendor/autoload.php';
$k = new App\Kernel('test', true);
echo get_class($k) . "\n";