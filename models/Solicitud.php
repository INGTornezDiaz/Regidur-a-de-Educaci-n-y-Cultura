<?php
require_once __DIR__ . '/../config/database.php';

class Solicitud {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function obtenerPendientes() {
        try {
            $query = "SELECT * FROM solicitudes WHERE estado = 'Pendiente' ORDER BY fecha DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al obtener solicitudes: ' . $e->getMessage();
            return [];
        }
    }

    public function obtenerAprobadas() {
        try {
            $query = "SELECT * FROM solicitudes WHERE estado = 'Aprobada' ORDER BY fecha DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al obtener solicitudes: ' . $e->getMessage();
            return [];
        }
    }

    public function crear($data) {
        // Validar campos requeridos
        if (empty($data['nombre_solicitante']) || empty($data['tipo_solicitud']) || empty($data['asunto']) || empty($data['telefono'])) {
            $_SESSION['error'] = 'Todos los campos marcados con * son obligatorios.';
            return false;
        }

        // Validar monto
        $monto_apoyo = 0.00;
        if ($data['tipo_solicitud'] === 'Apoyo') {
            $monto_raw = isset($data['monto_apoyo']) ? trim($data['monto_apoyo']) : '';
            
            if (empty($monto_raw)) {
                $_SESSION['error'] = 'El monto de apoyo es requerido para solicitudes de tipo "Apoyo".';
                return false;
            }

            $monto_raw = str_replace(',', '.', $monto_raw);

            if (!is_numeric($monto_raw) || (float)$monto_raw <= 0) {
                $_SESSION['error'] = 'El monto debe ser un valor numérico positivo.';
                return false;
            }

            $monto_apoyo = (float)$monto_raw;

            if ($monto_apoyo > 100000.00) {
                $_SESSION['error'] = 'El monto no puede exceder $100,000.00';
                return false;
            }
        }

        try {
            $query = "INSERT INTO solicitudes (user_id, fecha, nombre_solicitante, tipo_solicitud, asunto, localidad, telefono, monto_apoyo, estado) 
                      VALUES (:user_id, CURDATE(), :nombre, :tipo, :asunto, :localidad, :telefono, :monto_apoyo, 'Pendiente')";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':user_id', $_SESSION['user_id'] ?? 1, PDO::PARAM_INT);
            $stmt->bindValue(':nombre', $data['nombre_solicitante'], PDO::PARAM_STR);
            $stmt->bindValue(':tipo', $data['tipo_solicitud'], PDO::PARAM_STR);
            $stmt->bindValue(':asunto', $data['asunto'], PDO::PARAM_STR);
            $stmt->bindValue(':localidad', $data['localidad'] ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':telefono', $data['telefono'], PDO::PARAM_STR);
            $stmt->bindValue(':monto_apoyo', $monto_apoyo, PDO::PARAM_STR);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al guardar: ' . $e->getMessage();
            return false;
        }
    }

    public function aprobar($id) {
        try {
            $query = "UPDATE solicitudes SET estado = 'Aprobada' WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function rechazar($id) {
        try {
            $query = "UPDATE solicitudes SET estado = 'Rechazada' WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function eliminar($id) {
        try {
            $query = "DELETE FROM solicitudes WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            return false;
        }
    }

    public function obtenerLocalidadesApoyadas() {
        try {
            $query = "SELECT localidad, asunto, monto_apoyo, fecha, nombre_solicitante 
                      FROM solicitudes 
                      WHERE estado = 'Aprobada' AND monto_apoyo > 0
                      ORDER BY monto_apoyo DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al obtener localidades apoyadas: ' . $e->getMessage();
            return [];
        }
    }

    public function actualizarMonto($id, $monto) {
        try {
            $query = "UPDATE solicitudes SET monto_apoyo = :monto WHERE id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':monto', number_format((float)$monto, 2, '.', ''), PDO::PARAM_STR);
            $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error al actualizar monto: ' . $e->getMessage();
            return false;
        }
    }
}
?>

