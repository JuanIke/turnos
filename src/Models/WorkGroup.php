<?php

namespace App\Models;

use App\Database;
use PDO;

class WorkGroup
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array
    {
        $sql = "SELECT * FROM work_groups ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO work_groups (name, color) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['color'] ?? '#3B82F6'
        ]);
        return $this->db->lastInsertId();
    }

    public function assignUserToGroup(int $userId, int $groupId): bool
    {
        $sql = "INSERT INTO user_work_groups (user_id, work_group_id) VALUES (?, ?) ON CONFLICT DO NOTHING";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $groupId]);
    }

    public function getUserGroups(int $userId): array
    {
        $sql = "
            SELECT wg.* 
            FROM work_groups wg
            JOIN user_work_groups uwg ON wg.id = uwg.work_group_id
            WHERE uwg.user_id = ?
            ORDER BY wg.name
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function removeUserFromGroup(int $userId, int $groupId): bool
    {
        $sql = "DELETE FROM user_work_groups WHERE user_id = ? AND work_group_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$userId, $groupId]);
    }

    public function getGroupsWithUsers(): array
    {
        $sql = "
            SELECT wg.*, u.id as user_id, u.name as user_name, u.email as user_email, u.role as user_role
            FROM work_groups wg
            LEFT JOIN user_work_groups uwg ON wg.id = uwg.work_group_id
            LEFT JOIN users u ON uwg.user_id = u.id AND u.is_active = true
            ORDER BY wg.name, u.name
        ";
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll();
        
        $groups = [];
        foreach ($results as $row) {
            $groupId = $row['id'];
            if (!isset($groups[$groupId])) {
                $groups[$groupId] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'color' => $row['color'],
                    'users' => []
                ];
            }
            
            if ($row['user_id']) {
                $groups[$groupId]['users'][] = [
                    'id' => $row['user_id'],
                    'name' => $row['user_name'],
                    'email' => $row['user_email'],
                    'role' => $row['user_role']
                ];
            }
        }
        
        return array_values($groups);
    }

    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE work_groups SET name = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['name'],
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM work_groups WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT * FROM work_groups WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getUsersByGroup(int $groupId): array
    {
        $sql = "
            SELECT u.id, u.name, u.email, u.role
            FROM users u
            JOIN user_work_groups uwg ON u.id = uwg.user_id
            WHERE uwg.work_group_id = ? AND u.is_active = true
            ORDER BY u.name
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$groupId]);
        return $stmt->fetchAll();
    }

    public function getAvailableUsers(int $groupId): array
    {
        $sql = "
            SELECT u.id, u.name, u.email, u.role
            FROM users u
            WHERE u.is_active = true 
            AND u.role != 'superadmin'
            AND u.id NOT IN (
                SELECT uwg.user_id 
                FROM user_work_groups uwg 
                WHERE uwg.work_group_id = ?
            )
            ORDER BY u.name
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$groupId]);
        return $stmt->fetchAll();
    }
}