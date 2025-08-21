-- Sistema de Gestión de Turnos - Base de Datos PostgreSQL

-- Tabla de usuarios
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    role VARCHAR(50) DEFAULT 'user' CHECK (role IN ('superadmin', 'admin', 'user')),
    is_active BOOLEAN DEFAULT true,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de trabajadores (extiende users)
CREATE TABLE workers (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    can_switch BOOLEAN DEFAULT false,
    monthly_limit INTEGER DEFAULT 8,
    training_stage VARCHAR(50) DEFAULT 'COMPLETED' CHECK (training_stage IN ('VISUALIZATION', 'THEORY_1', 'ADVANCED_VISUALIZATION', 'CAMERA_6_PRACTICE', 'THEORY_2', 'COMPLETED')),
    camera_preferences JSONB DEFAULT '[]',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de tipos de turno
CREATE TABLE shift_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3B82F6',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de turnos
CREATE TABLE shifts (
    id SERIAL PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    shift_type_id INTEGER REFERENCES shift_types(id),
    status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'cancelled')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de asignaciones
CREATE TABLE assignments (
    id SERIAL PRIMARY KEY,
    shift_id INTEGER REFERENCES shifts(id) ON DELETE CASCADE,
    worker_id INTEGER REFERENCES workers(id) ON DELETE CASCADE,
    camera_type VARCHAR(50) NOT NULL CHECK (camera_type IN ('CAMERA_5', 'CAMERA_6', 'CAMERA_7', 'CAMERA_8', 'SWITCH')),
    status VARCHAR(50) DEFAULT 'pending' CHECK (status IN ('pending', 'confirmed', 'cancelled')),
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at TIMESTAMP NULL,
    UNIQUE(shift_id, camera_type)
);

-- Tabla de disponibilidad
CREATE TABLE availability (
    id SERIAL PRIMARY KEY,
    worker_id INTEGER REFERENCES workers(id) ON DELETE CASCADE,
    date DATE NOT NULL,
    is_available BOOLEAN DEFAULT true,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(worker_id, date)
);

-- Tabla de grupos de trabajo
CREATE TABLE work_groups (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    color VARCHAR(7) DEFAULT '#3B82F6',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de asignación de usuarios a grupos
CREATE TABLE user_work_groups (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE CASCADE,
    work_group_id INTEGER REFERENCES work_groups(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, work_group_id)
);

-- Índices para optimización
CREATE INDEX idx_shifts_date ON shifts(date);
CREATE INDEX idx_assignments_shift_worker ON assignments(shift_id, worker_id);
CREATE INDEX idx_availability_worker_date ON availability(worker_id, date);