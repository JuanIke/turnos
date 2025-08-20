# 🎬 Sistema de Gestión de Turnos - PHP

Sistema web completo para la gestión de turnos de producción audiovisual, desarrollado con PHP y PostgreSQL.

## ✨ Características

- **Autenticación segura** con hash de contraseñas
- **Dashboard intuitivo** con métricas en tiempo real
- **Gestión de turnos** completa
- **Sistema de trabajadores** con capacitación
- **Asignación de cámaras** (5, 6, 7, 8) y switch
- **Disponibilidad personal** configurable
- **Responsive design** con Tailwind CSS

## 🚀 Instalación

### Prerrequisitos
- PHP 8.1+
- PostgreSQL 13+
- Composer

### 1. Clonar e instalar dependencias
```bash
git clone <tu-repo>
cd turnos
composer install
```

### 2. Configurar base de datos
```bash
# Crear base de datos PostgreSQL
createdb turnos_db

# Crear usuario
psql -c "CREATE USER turnos_user WITH PASSWORD 'turnos_password';"
psql -c "GRANT ALL PRIVILEGES ON DATABASE turnos_db TO turnos_user;"

# Ejecutar esquema
psql -d turnos_db -f database/schema.sql

# Insertar datos iniciales
psql -d turnos_db -f database/seed.sql
```

### 3. Configurar entorno
```bash
cp .env.example .env
# Editar .env con tus configuraciones
```

### 4. Ejecutar servidor
```bash
composer serve
# O manualmente: php -S localhost:8000 -t public
```

## 👥 Usuarios de Prueba

- **Admin:** admin@turnos.com / admin123
- **Usuario:** juan@turnos.com / user123

## 📁 Estructura

```
turnos/
├── src/
│   ├── Controllers/     # Controladores
│   ├── Models/         # Modelos de datos
│   ├── Views/          # Vistas HTML
│   └── Database.php    # Conexión DB
├── database/
│   ├── schema.sql      # Esquema de BD
│   └── seed.sql        # Datos iniciales
├── public/
│   └── index.php       # Punto de entrada
└── composer.json       # Dependencias
```

## 🔧 Configuración

### Variables de Entorno (.env)
```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=turnos_db
DB_USER=turnos_user
DB_PASS=turnos_password

APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000
```

## 🌐 Deployment

### Con Docker
```bash
# Crear Dockerfile y docker-compose.yml según necesidades
docker-compose up -d
```

### En servidor tradicional
1. Subir archivos al servidor
2. Configurar base de datos PostgreSQL
3. Configurar variables de entorno
4. Apuntar dominio a `/public`

## 🛠️ Desarrollo

### Agregar nuevas rutas
Editar `public/index.php` y agregar casos al switch.

### Crear nuevos modelos
Extender la clase base en `src/Models/`.

### Agregar vistas
Crear archivos PHP en `src/Views/`.

## 🔒 Seguridad

- Contraseñas hasheadas con `password_hash()`
- Consultas preparadas (PDO)
- Sanitización de entrada con `htmlspecialchars()`
- Validación de sesiones
- Filtros de entrada con `filter_input()`

## 📄 Licencia

Uso personal. Todos los derechos reservados.

---

**¡Sistema listo para gestionar turnos de producción audiovisual! 🎬✨**