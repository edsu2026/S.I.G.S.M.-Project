-- Insertar 3 categorías (Servicios)
INSERT INTO categoria (nombre_categoria, activo) VALUES
('Pediatría', 1),
('Cardiología', 1),
('Urgencias', 1);

-- Insertar 3 documentos (asociados a las categorías creadas)
INSERT INTO documento (id_categoria, nombre_documento, fecha_creación_doc, activo) VALUES
(1, 'Guía de Vacunación Infantil', '2026-01-15', 1),
(2, 'Protocolo de Hipertensión', '2026-02-10', 1),
(3, 'Manual de Triaje Urgente', '2026-03-01', 1);