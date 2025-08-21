<?php

namespace App\Controllers;

use App\Models\Shift;
use App\Models\Worker;

class DashboardController
{
    private $shiftModel;
    private $workerModel;

    public function __construct()
    {
        $this->shiftModel = new Shift();
        $this->workerModel = new Worker();
    }

    public function index()
    {
        // Obtener conteos dinámicos
        $db = \App\Database::getInstance()->getConnection();
        
        // Contar turnos
        $stmt = $db->query("SELECT COUNT(*) as total FROM shifts");
        $totalShifts = $stmt->fetch()['total'];
        
        // Contar servidores (usuarios que no son superadmin)
        $stmt = $db->query("SELECT COUNT(*) as total FROM users WHERE role != 'superadmin' AND is_active = true");
        $totalServers = $stmt->fetch()['total'];
        
        // Contar grupos
        $stmt = $db->query("SELECT COUNT(*) as total FROM work_groups");
        $totalGroups = $stmt->fetch()['total'];

        include __DIR__ . '/../Views/dashboard/index.php';
    }
}