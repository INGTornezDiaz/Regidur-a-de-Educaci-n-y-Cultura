<?php
require_once __DIR__ . '/../config/database.php';

class Usuario {
    private $conn;
    private $table = 'usuarios';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function login($usuario, $password) {
        $query = "SELECT id, usuario, password, nombre FROM " . $this->table . " WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($password, $row['password'])) {
                return [
                    'id' => $row['id'],
                    'usuario' => $row['usuario'],
                    'nombre' => $row['nombre']
                ];
            }
        }
        return false;
    }

    public function usuarioExiste($usuario) {
        $query = "SELECT id FROM " . $this->table . " WHERE usuario = :usuario LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function crear($data) {
        // Verificar si el usuario ya existe
        if ($this->usuarioExiste($data['usuario'])) {
            return ['success' => false, 'message' => 'El usuario ya existe'];
        }

        $query = "INSERT INTO " . $this->table . " (usuario, password, nombre) 
                  VALUES (:usuario, :password, :nombre)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        $stmt->bindParam(':usuario', $data['usuario']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':nombre', $data['nombre']);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Usuario creado exitosamente'];
        } else {
            return ['success' => false, 'message' => 'Error al crear el usuario'];
        }
    }
}

