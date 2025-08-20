-- Datos iniciales para el sistema de turnos

-- Insertar tipos de turno
INSERT INTO shift_types (name, description, color) VALUES
('Culto Dominical', 'Servicio dominical principal', '#3B82F6'),
('Culto de Jóvenes', 'Servicio especial para jóvenes', '#10B981'),
('Evento Especial', 'Eventos y conferencias especiales', '#F59E0B'),
('Ensayo', 'Ensayos y pruebas técnicas', '#8B5CF6');

-- Insertar usuarios
INSERT INTO users (email, password, name, role) VALUES
('admin@turnos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrador', 'admin'),
('juan@turnos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Pérez', 'user'),
('maria@turnos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'María García', 'user'),
('pedro@turnos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro López', 'user'),
('ana@turnos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ana Martínez', 'user'),
('carlos@turnos.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Carlos Rodríguez', 'user');

-- Insertar trabajadores
INSERT INTO workers (user_id, can_switch, monthly_limit, training_stage, camera_preferences) VALUES
(2, true, 10, 'COMPLETED', '["CAMERA_5", "CAMERA_6"]'),
(3, true, 8, 'COMPLETED', '["CAMERA_7", "CAMERA_8"]'),
(4, false, 6, 'COMPLETED', '["CAMERA_6", "CAMERA_7"]'),
(5, false, 8, 'CAMERA_6_PRACTICE', '["CAMERA_8"]'),
(6, true, 12, 'COMPLETED', '["CAMERA_5", "SWITCH"]');

-- Insertar algunos turnos de ejemplo
INSERT INTO shifts (name, date, start_time, end_time, shift_type_id) VALUES
('Culto Dominical Matutino', '2024-01-07', '09:00:00', '11:30:00', 1),
('Culto Dominical Vespertino', '2024-01-07', '18:00:00', '20:30:00', 1),
('Culto de Jóvenes', '2024-01-12', '19:00:00', '21:00:00', 2),
('Ensayo General', '2024-01-13', '15:00:00', '17:00:00', 4);

-- Insertar algunas asignaciones de ejemplo
INSERT INTO assignments (shift_id, worker_id, camera_type, status) VALUES
(1, 1, 'CAMERA_5', 'confirmed'),
(1, 2, 'CAMERA_6', 'confirmed'),
(1, 3, 'CAMERA_7', 'pending'),
(1, 5, 'SWITCH', 'confirmed'),
(2, 2, 'CAMERA_5', 'pending'),
(2, 4, 'CAMERA_8', 'pending');