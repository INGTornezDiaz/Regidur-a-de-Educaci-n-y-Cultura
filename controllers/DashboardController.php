<?php

class DashboardController {
    private $solicitudModel;

    public function __construct() {
        AuthController::verificarSesion();
        $this->solicitudModel = new Solicitud();
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $solicitudes_pendientes = $this->solicitudModel->obtenerPendientes();
        $solicitudes_aprobadas = $this->solicitudModel->obtenerAprobadas();
        $localidades_apoyadas = $this->solicitudModel->obtenerLocalidadesApoyadas();

        // Obtener nombre de usuario: usar sesión si existe, si no consultar BD por user_id
        $usuario_nombre = $_SESSION['nombre'] ?? null;
        if (empty($usuario_nombre) && !empty($_SESSION['user_id'])) {
            try {
                $db = Database::getInstance();
                $stmt = $db->prepare("SELECT nombre FROM usuarios WHERE id = :id LIMIT 1");
                $stmt->bindValue(':id', $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $usuario_nombre = $row['nombre'] ?? null;
            } catch (Exception $e) {
                // no interrumpir el flujo por este fallo; dejar nombre nulo
                $usuario_nombre = null;
            }
        }

        require_once BASE_PATH . 'views/dashboard/index.php';
    }

    public function registrarSolicitud() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=dashboard');
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        // debug temporal: inspeccionar sesión y POST
        @file_put_contents(__DIR__ . '/../tmp/registrar_debug.log', date('c') . " | SESSION: " . print_r($_SESSION, true) . " | POST: " . print_r($_POST, true) . "\n", FILE_APPEND);

        // si AuthController::verificarSesion() puede redirigir, comprueba que el user esté
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Sesión no iniciada. Por favor inicie sesión.';
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }

        $data = [
            'fecha' => $_POST['fecha'] ?? '', 
            'nombre_solicitante' => trim($_POST['nombre_solicitante'] ?? ''),
            'tipo_solicitud' => trim($_POST['tipo_solicitud'] ?? ''),
            'asunto' => trim($_POST['asunto'] ?? ''),
            'localidad' => trim($_POST['localidad'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'monto_apoyo' => trim($_POST['monto_apoyo'] ?? '0')
        ];

        if ($this->solicitudModel->crear($data)) {
            $_SESSION['success'] = 'Solicitud registrada exitosamente';
        } else {
            // el modelo puede haber establecido $_SESSION['error']
            if (!isset($_SESSION['error'])) $_SESSION['error'] = 'Error al registrar la solicitud';
        }

        header('Location: ' . BASE_URL . 'index.php?action=dashboard');
        exit;
    }

    public function aprobarSolicitud() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($this->solicitudModel->aprobar($id)) {
                $_SESSION['success'] = 'Solicitud aprobada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al aprobar la solicitud';
            }
        }
        header('Location: ' . BASE_URL . 'index.php?action=dashboard');
        exit;
    }

    public function rechazarSolicitud() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($this->solicitudModel->rechazar($id)) {
                $_SESSION['success'] = 'Solicitud rechazada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al rechazar la solicitud';
            }
        }
        header('Location: ' . BASE_URL . 'index.php?action=dashboard');
        exit;
    }

    public function eliminarSolicitud() {
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            if ($this->solicitudModel->eliminar($id)) {
                $_SESSION['success'] = 'Solicitud eliminada exitosamente';
            } else {
                $_SESSION['error'] = 'Error al eliminar la solicitud';
            }
        }
        header('Location: ' . BASE_URL . 'index.php?action=dashboard');
        exit;
    }

    public function verAprobadas() {
        $solicitudes_aprobadas = $this->solicitudModel->obtenerAprobadas();
        require_once BASE_PATH . 'views/dashboard/aprobadas.php';
    }

    public function actualizarMonto() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'index.php?action=dashboard');
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Sesión expirada.';
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }

        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $monto_raw = trim($_POST['monto_apoyo'] ?? '0');
        $monto_raw = str_replace(',', '.', $monto_raw);

        if ($id <= 0 || !is_numeric($monto_raw) || (float)$monto_raw < 0) {
            $_SESSION['error'] = 'Datos inválidos.';
            header('Location: ' . BASE_URL . 'index.php?action=dashboard');
            exit;
        }

        $monto = (float)$monto_raw;
        if ($monto > 100000.00) {
            $_SESSION['error'] = 'El monto no puede exceder $100,000.00';
            header('Location: ' . BASE_URL . 'index.php?action=dashboard');
            exit;
        }

        if ($this->solicitudModel->actualizarMonto($id, $monto)) {
            $_SESSION['success'] = 'Monto actualizado correctamente.';
        } else {
            if (!isset($_SESSION['error'])) $_SESSION['error'] = 'No se pudo actualizar el monto.';
        }

        header('Location: ' . BASE_URL . 'index.php?action=dashboard');
        exit;
    }
}

