<?php

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = $_POST['usuario'] ?? '';
            $password = $_POST['password'] ?? '';

            $usuarioModel = new Usuario();
            $user = $usuarioModel->login($usuario, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nombre'];
                $_SESSION['logged_in'] = true;
                header('Location: ' . BASE_URL . 'index.php?action=dashboard');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos';
                require_once BASE_PATH . 'views/auth/login.php';
            }
        } else {
            require_once BASE_PATH . 'views/auth/login.php';
        }
    }

    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . 'index.php?action=login');
        exit;
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = trim($_POST['usuario'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $nombre = trim($_POST['nombre'] ?? '');

            // Validaciones
            $error = null;
            if (empty($usuario)) {
                $error = 'El usuario es requerido';
            } elseif (empty($password)) {
                $error = 'La contraseña es requerida';
            } elseif (strlen($password) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres';
            } elseif ($password !== $password_confirm) {
                $error = 'Las contraseñas no coinciden';
            } elseif (empty($nombre)) {
                $error = 'El nombre es requerido';
            }

            if ($error) {
                require_once BASE_PATH . 'views/auth/registro.php';
                return;
            }

            $usuarioModel = new Usuario();
            $result = $usuarioModel->crear([
                'usuario' => $usuario,
                'password' => $password,
                'nombre' => $nombre
            ]);

            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                header('Location: ' . BASE_URL . 'index.php?action=login');
                exit;
            } else {
                $error = $result['message'];
                require_once BASE_PATH . 'views/auth/registro.php';
            }
        } else {
            require_once BASE_PATH . 'views/auth/registro.php';
        }
    }

    public static function verificarSesion() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . BASE_URL . 'index.php?action=login');
            exit;
        }
    }
}

