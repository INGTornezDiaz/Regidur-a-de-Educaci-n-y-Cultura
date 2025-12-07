<?php
/**
 * Autoloader simple para cargar clases automáticamente
 */
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . 'models/',
        BASE_PATH . 'controllers/',
        BASE_PATH . 'config/'
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

