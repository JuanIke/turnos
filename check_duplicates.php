<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $db = Database::getInstance()->getConnection();
    
    // Buscar usuarios duplicados por nombre
    $sql = "SELECT id, email, name, role FROM users WHERE name LIKE '%Juan%' OR name LIKE '%juan%' ORDER BY id";
    $stmt = $db->query($sql);
    $users = $stmt->fetchAll();
    
    echo "Usuarios con 'Juan' en el nombre:\n";
    foreach ($users as $user) {
        echo "ID: {$user['id']}, Email: {$user['email']}, Nombre: {$user['name']}, Rol: {$user['role']}\n";
    }
    
    // Buscar duplicados exactos por nombre
    $sql = "SELECT name, COUNT(*) as count FROM users GROUP BY name HAVING COUNT(*) > 1";
    $stmt = $db->query($sql);
    $duplicates = $stmt->fetchAll();
    
    echo "\nNombres duplicados:\n";
    foreach ($duplicates as $duplicate) {
        echo "Nombre: {$duplicate['name']}, Cantidad: {$duplicate['count']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}