-- Base de datos para Regiduría de Educación y Cultura
CREATE DATABASE IF NOT EXISTS regiduria_educacion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE regiduria_educacion;

-- Tabla de usuarios (solo un usuario)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



-- Tabla de solicitudes
CREATE TABLE IF NOT EXISTS solicitudes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    nombre_solicitante VARCHAR(200) NOT NULL,
    tipo_solicitud VARCHAR(100) NOT NULL,
    asunto TEXT NOT NULL,
    localidad VARCHAR(150) NOT NULL,
    telefono VARCHAR(20) NOT NULL,
    estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

