<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\WorkGroup;
use App\Models\MinistryRole;

class UserController
{
    private $userModel;
    private $workGroupModel;
    private $ministryRoleModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->workGroupModel = new WorkGroup();
        $this->ministryRoleModel = new MinistryRole();
    }

    public function index()
    {
        // Obtener todos los usuarios con sus grupos
        $users = $this->getUsersWithGroups();
        include __DIR__ . '/../Views/users/index.php';
    }

    public function show($userId)
    {
        // Obtener información detallada del usuario
        $user = $this->userModel->findById($userId);
        if (!$user) {
            header('Location: /users');
            exit;
        }

        // Obtener grupos del usuario
        $userGroups = $this->workGroupModel->getUserGroups($userId);
        
        // Obtener roles del usuario en cada grupo
        $userGroupsWithRoles = [];
        foreach ($userGroups as $group) {
            $roles = $this->ministryRoleModel->getUserRoles($userId, $group['id']);
            $userGroupsWithRoles[] = [
                'group' => $group,
                'roles' => $roles
            ];
        }
        
        // Obtener información adicional del usuario
        $userDetails = $this->getUserDetails($userId);
        
        include __DIR__ . '/../Views/users/show.php';
    }

    public function updateName($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $name = trim($input['name'] ?? '');

        if (empty($name)) {
            echo json_encode(['success' => false, 'error' => 'Nombre requerido']);
            return;
        }

        $db = \App\Database::getInstance()->getConnection();
        $sql = "UPDATE users SET name = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $success = $stmt->execute([$name, $userId]);

        echo json_encode(['success' => $success]);
    }

    public function showGroups($userId)
    {
        $user = $this->userModel->findById($userId);
        if (!$user) {
            http_response_code(404);
            echo 'Usuario no encontrado';
            return;
        }

        $allGroups = $this->workGroupModel->getAll();
        $userGroups = $this->workGroupModel->getUserGroups($userId);
        $userGroupIds = array_column($userGroups, 'id');

        include __DIR__ . '/../Views/users/groups-modal.php';
    }

    public function showRoles($userId, $groupId)
    {
        $user = $this->userModel->findById($userId);
        $group = $this->workGroupModel->getById($groupId);
        
        if (!$user || !$group) {
            http_response_code(404);
            return;
        }

        $allRoles = $this->ministryRoleModel->getRolesByGroup($groupId);
        $userRoles = $this->ministryRoleModel->getUserRoles($userId, $groupId);
        $userRoleIds = array_column($userRoles, 'id');

        include __DIR__ . '/../Views/users/roles-modal.php';
    }

    public function updateGroups($userId)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $groupIds = $input['groups'] ?? [];

        try {
            $db = \App\Database::getInstance()->getConnection();
            
            // Eliminar grupos actuales
            $sql = "DELETE FROM user_work_groups WHERE user_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId]);

            // Agregar nuevos grupos
            if (!empty($groupIds)) {
                $sql = "INSERT INTO user_work_groups (user_id, work_group_id) VALUES (?, ?)";
                $stmt = $db->prepare($sql);
                foreach ($groupIds as $groupId) {
                    $stmt->execute([$userId, $groupId]);
                }
            }

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function updateRoles($userId, $groupId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $roleIds = $input['roles'] ?? [];

        $success = $this->ministryRoleModel->assignUserRoles($userId, $groupId, $roleIds);
        echo json_encode(['success' => $success]);
    }

    public function showAvailableGroups($userId)
    {
        $user = $this->userModel->findById($userId);
        if (!$user) {
            http_response_code(404);
            return;
        }

        $userGroups = $this->workGroupModel->getUserGroups($userId);
        $userGroupIds = array_column($userGroups, 'id');
        $allGroups = $this->workGroupModel->getAll();
        $availableGroups = array_filter($allGroups, function($group) use ($userGroupIds) {
            return !in_array($group['id'], $userGroupIds);
        });

        include __DIR__ . '/../Views/users/available-groups.php';
    }

    public function addUserToGroup($userId, $groupId)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        try {
            $success = $this->workGroupModel->assignUserToGroup($userId, $groupId);
            echo json_encode(['success' => $success]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function removeUserFromGroup($userId, $groupId)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        try {
            $success = $this->workGroupModel->removeUserFromGroup($userId, $groupId);
            echo json_encode(['success' => $success]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function showAvailableRoles($userId, $groupId)
    {
        $user = $this->userModel->findById($userId);
        $group = $this->workGroupModel->getById($groupId);
        
        if (!$user || !$group) {
            http_response_code(404);
            return;
        }

        $allRoles = $this->ministryRoleModel->getRolesByGroup($groupId);
        $userRoles = $this->ministryRoleModel->getUserRoles($userId, $groupId);
        $userRoleIds = array_column($userRoles, 'id');
        $availableRoles = array_filter($allRoles, function($role) use ($userRoleIds) {
            return !in_array($role['id'], $userRoleIds);
        });

        include __DIR__ . '/../Views/users/available-roles.php';
    }

    public function addRoleToUser($userId, $roleId)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        try {
            $success = $this->ministryRoleModel->assignUserToRole($userId, $roleId);
            echo json_encode(['success' => $success]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function removeRoleFromUser($userId, $roleId)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Método no permitido']);
            return;
        }

        try {
            $success = $this->ministryRoleModel->removeUserFromRole($userId, $roleId);
            echo json_encode(['success' => $success]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function getUsersWithGroups(): array
    {
        $db = \App\Database::getInstance()->getConnection();
        $sql = "
            SELECT u.id, u.name, u.email, u.role, u.created_at,
                   STRING_AGG(wg.name, ', ') as groups
            FROM users u
            LEFT JOIN user_work_groups uwg ON u.id = uwg.user_id
            LEFT JOIN work_groups wg ON uwg.work_group_id = wg.id
            WHERE u.is_active = true
            GROUP BY u.id, u.name, u.email, u.role, u.created_at
            ORDER BY u.name
        ";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }

    private function getUserDetails($userId): array
    {
        $db = \App\Database::getInstance()->getConnection();
        $sql = "
            SELECT u.*, 
                   COUNT(DISTINCT uwg.work_group_id) as total_groups
            FROM users u
            LEFT JOIN user_work_groups uwg ON u.id = uwg.user_id
            WHERE u.id = ? AND u.is_active = true
            GROUP BY u.id, u.name, u.email, u.role, u.created_at, u.is_active
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        return $result ?: [];
    }
}