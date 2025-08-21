<?php

require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Database;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $db = Database::getInstance()->getConnection();
    
    // Ver grupos actuales del usuario 2
    echo "=== GRUPOS ACTUALES DEL USUARIO 2 ===\n";
    $sql = "SELECT wg.id, wg.name FROM work_groups wg 
            JOIN user_work_groups uwg ON wg.id = uwg.work_group_id 
            WHERE uwg.user_id = 2";
    $stmt = $db->query($sql);
    $currentGroups = $stmt->fetchAll();
    
    foreach ($currentGroups as $group) {
        echo "ID: {$group['id']}, Nombre: {$group['name']}\n";
    }
    
    if (empty($currentGroups)) {
        echo "El usuario no pertenece a ningún grupo\n";
    }
    
    echo "\n=== TODOS LOS GRUPOS DISPONIBLES ===\n";
    $sql = "SELECT id, name FROM work_groups ORDER BY name";
    $stmt = $db->query($sql);
    $allGroups = $stmt->fetchAll();
    
    foreach ($allGroups as $group) {
        echo "ID: {$group['id']}, Nombre: {$group['name']}\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}