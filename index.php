<?php
/**
 * Archivo principal - Router simple
 */
require_once 'config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start(); // <-- Asegura que la sesión esté iniciada antes de cualquier controlador
}

// Obtener la acción desde la URL
$action = $_GET['action'] ?? 'login';

// Router simple
switch ($action) {
    case 'login':
        $controller = new AuthController();
        $controller->login();
        break;

    case 'registrar':
        $controller = new AuthController();
        $controller->registrar();
        break;

    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;

    case 'registrar_solicitud':
        $controller = new DashboardController();
        $controller->registrarSolicitud();
        break;

    case 'aprobar_solicitud':
        $controller = new DashboardController();
        $controller->aprobarSolicitud();
        break;

    case 'rechazar_solicitud':
        $controller = new DashboardController();
        $controller->rechazarSolicitud();
        break;

    case 'eliminar_solicitud':
        $controller = new DashboardController();
        $controller->eliminarSolicitud();
        break;

    case 'ver_aprobadas':
        $controller = new DashboardController();
        $controller->verAprobadas();
        break;

    case 'actualizar_monto':                      // <-- nuevo case
        $controller = new DashboardController();
        $controller->actualizarMonto();
        break;

    default:
        header('Location: ' . BASE_URL . 'index.php?action=login');
        exit;
}

// después de instanciar $controller = new DashboardController(...);
if ($action === 'actualizar_monto') {
    $controller->actualizarMonto();
    exit;
}

