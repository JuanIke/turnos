<?php

namespace App\Models;

use App\Database;

class MinistryRole
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getRolesByGroup(int $groupId): array
    {
        $sql = "SELECT * FROM ministry_roles WHERE work_group_id = ? ORDER BY name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$groupId]);
        return $stmt->fetchAll();
    }

    public function getUserRoles(int $userId, int $groupId): array
    {
        $sql = "
            SELECT mr.* 
            FROM ministry_roles mr
            JOIN user_ministry_roles umr ON mr.id = umr.ministry_role_id
            WHERE umr.user_id = ? AND mr.work_group_id = ?
            ORDER BY mr.name
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $groupId]);
        return $stmt->fetchAll();
    }

    public function getAdminPermissions(int $adminId): array
    {
        $sql = "
            SELECT mr.* 
            FROM ministry_roles mr
            JOIN admin_role_permissions arp ON mr.id = arp.ministry_role_id
            WHERE arp.admin_user_id = ?
            ORDER BY mr.name
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$adminId]);
        return $stmt->fetchAll();
    }

    public function assignUserToRole(int $userId, int $roleId): bool
    {
        $sql = "INSERT INTO user_ministry_roles (user_id, ministry_role_id) VALUES (?, ?) ON CONFLICT DO NOTHING";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $roleId]);
    }

    public function removeUserFromRole(int $userId, int $roleId): bool
    {
        $sql = "DELETE FROM user_ministry_roles WHERE user_id = ? AND ministry_role_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $roleId]);
    }
    
    public function assignUserRoles(int $userId, int $groupId, array $roleIds): bool
    {
        // Primero eliminar roles existentes del usuario en este grupo
        $sql = "DELETE FROM user_ministry_roles WHERE user_id = ? AND ministry_role_id IN (SELECT id FROM ministry_roles WHERE work_group_id = ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $groupId]);
        
        // Luego insertar los nuevos roles
        if (!empty($roleIds)) {
            $sql = "INSERT INTO user_ministry_roles (user_id, ministry_role_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            foreach ($roleIds as $roleId) {
                $stmt->execute([$userId, $roleId]);
            }
        }
        
        return true;
    }
    
    public function create(array $data): int
    {
        $sql = "INSERT INTO ministry_roles (name, work_group_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$data['name'], $data['work_group_id']]);
        return $this->db->lastInsertId();
    }
    
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE ministry_roles SET name = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$data['name'], $id]);
    }
    
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM ministry_roles WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}