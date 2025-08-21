<?php

namespace App\Controllers;

use App\Models\WorkGroup;
use App\Models\Shift;

class AutoAssignController
{
    private $workGroupModel;
    private $shiftModel;

    public function __construct()
    {
        $this->workGroupModel = new WorkGroup();
        $this->shiftModel = new Shift();
    }

    public function index()
    {
        // SuperAdmin ve todos los grupos, Admin solo los suyos
        if ($_SESSION['user_role'] === 'superadmin') {
            $userGroups = $this->workGroupModel->getAll();
        } else {
            $userGroups = $this->workGroupModel->getUserGroups($_SESSION['user_id']);
        }
        include __DIR__ . '/../Views/auto-assign/index.php';
    }

    public function showGroup($groupId)
    {
        // SuperAdmin tiene acceso a todos los grupos
        if ($_SESSION['user_role'] !== 'superadmin') {
            // Verificar que el admin pertenece a este grupo
            $userGroups = $this->workGroupModel->getUserGroups($_SESSION['user_id']);
            $hasAccess = false;
            foreach ($userGroups as $group) {
                if ($group['id'] == $groupId) {
                    $hasAccess = true;
                    break;
                }
            }
            
            if (!$hasAccess) {
                header('Location: /shifts/auto-assign');
                exit;
            }
        }

        $group = $this->workGroupModel->getById($groupId);
        $users = $this->workGroupModel->getUsersByGroup($groupId);
        $shifts = $this->shiftModel->getAll();
        
        include __DIR__ . '/../Views/auto-assign/group.php';
    }
}