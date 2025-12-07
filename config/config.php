<?php
/**
 * Configuración general de la aplicación
 */
session_start();

// Configuración de rutas
define('BASE_URL', 'http://localhost/web/');
define('BASE_PATH', __DIR__ . '/../');

// Configuración de sesión
define('SESSION_NAME', 'regiduria_session');
define('SESSION_TIMEOUT', 3600); // 1 hora

// Incluir autoloader
require_once BASE_PATH . 'config/autoload.php';

