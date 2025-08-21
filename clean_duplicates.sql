-- Script para limpiar usuarios duplicados

-- Primero, ver los duplicados
SELECT id, email, name, role, created_at 
FROM users 
WHERE name = 'Juan Pérez' 
ORDER BY id;

-- Eliminar duplicados manteniendo el más antiguo (menor ID)
-- CUIDADO: Ejecutar solo después de verificar los IDs
DELETE FROM users 
WHERE name = 'Juan Pérez' 
AND id NOT IN (
    SELECT MIN(id) 
    FROM users 
    WHERE name = 'Juan Pérez'
);

-- Verificar que solo quede uno
SELECT id, email, name, role 
FROM users 
WHERE name = 'Juan Pérez';