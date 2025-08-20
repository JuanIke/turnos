<?php

namespace App\Models;

use App\Database;
use PDO;

class Worker
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO workers (user_id, can_switch, monthly_limit, training_stage, camera_preferences) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['user_id'],
            $data['can_switch'] ?? false,
            $data['monthly_limit'] ?? 8,
            $data['training_stage'] ?? 'COMPLETED',
            json_encode($data['camera_preferences'] ?? [])
        ]);
        return $this->db->lastInsertId();
    }

    public function getAll(): array
    {
        $sql = "
            SELECT w.*, u.name, u.email, u.role
            FROM workers w
            JOIN users u ON w.user_id = u.id
            WHERE u.is_active = true
            ORDER BY u.name
        ";
        $stmt = $this->db->query($sql);
        $workers = $stmt->fetchAll();
        
        foreach ($workers as &$worker) {
            $worker['camera_preferences'] = json_decode($worker['camera_preferences'], true);
        }
        
        return $workers;
    }

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT w.*, u.name, u.email, u.role
            FROM workers w
            JOIN users u ON w.user_id = u.id
            WHERE w.id = ? AND u.is_active = true
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        
        if ($result) {
            $result['camera_preferences'] = json_decode($result['camera_preferences'], true);
        }
        
        return $result ?: null;
    }

    public function findByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM workers WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        $result = $stmt->fetch();
        
        if ($result) {
            $result['camera_preferences'] = json_decode($result['camera_preferences'], true);
        }
        
        return $result ?: null;
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'camera_preferences') {
                $fields[] = "$key = ?";
                $values[] = json_encode($value);
            } else {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        $values[] = $id;
        $sql = "UPDATE workers SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }
}