<?php

spl_autoload_register(function (string $class): void {
    $prefix = 'Distributed\\';

    if (! str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $path = __DIR__.'/src/'.str_replace('\\', '/', $relativeClass).'.php';

    if (file_exists($path)) {
        require $path;
    }
});

return require __DIR__.'/config.php';
