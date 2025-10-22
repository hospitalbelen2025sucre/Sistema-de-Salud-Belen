-- Base de datos para Sistema de Salud Integral BELÉN
-- Versión 1.0

-- Crear base de datos
CREATE DATABASE IF NOT EXISTS sistema_salud;
USE sistema_salud;

-- Tabla de citas médicas
CREATE TABLE IF NOT EXISTS citas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    servicio VARCHAR(100) NOT NULL,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    mensaje TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'medico', 'recepcionista') DEFAULT 'recepcionista',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de resultados PAP
CREATE TABLE IF NOT EXISTS resultados_pap (
    id INT AUTO_INCREMENT PRIMARY KEY,
    paciente_nombre VARCHAR(100) NOT NULL,
    paciente_dni VARCHAR(20) NOT NULL,
    fecha_examen DATE NOT NULL,
    resultado TEXT,
    observaciones TEXT,
    medico_responsable VARCHAR(100),
    corregido BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de reclamos
CREATE TABLE IF NOT EXISTS reclamos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(20),
    asunto VARCHAR(200) NOT NULL,
    mensaje TEXT NOT NULL,
    estado ENUM('pendiente', 'en_proceso', 'resuelto') DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertar usuario administrador por defecto
INSERT INTO usuarios (username, password, rol) VALUES 
('admin', '\\\.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Insertar datos de ejemplo
INSERT INTO citas (nombre, email, telefono, servicio, fecha, hora, mensaje) VALUES
('Juan Pérez', 'juan@email.com', '123456789', 'Consulta General', '2025-10-25', '10:30:00', 'Dolor de cabeza persistente'),
('María García', 'maria@email.com', '987654321', 'Revisión PAP', '2025-10-26', '14:00:00', 'Control anual');

INSERT INTO resultados_pap (paciente_nombre, paciente_dni, fecha_examen, resultado, observaciones, medico_responsable) VALUES
('Ana López', '12345678A', '2025-10-20', 'Resultado normal', 'Sin observaciones', 'Dr. Martínez'),
('Carlos Ruiz', '87654321B', '2025-10-19', 'Resultado con anomalías leves', 'Seguimiento en 6 meses', 'Dra. Fernández');

INSERT INTO reclamos (nombre, email, telefono, asunto, mensaje, estado) VALUES
('Pedro Sánchez', 'pedro@email.com', '555123456', 'Largo tiempo de espera', 'Esperé más de 2 horas para mi cita', 'resuelto'),
('Laura Jiménez', 'laura@email.com', '555987654', 'Falta de información', 'No me avisaron del cambio de horario', 'pendiente');
