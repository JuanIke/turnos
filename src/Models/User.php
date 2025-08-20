<?php

namespace App\Models;

use App\Database;
use PDO;

class User
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO users (email, password, name, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['name'],
            $data['role'] ?? 'user'
        ]);
        return $this->db->lastInsertId();
    }

    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ? AND is_active = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = ? AND is_active = true";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function getAll(): array
    {
        $sql = "SELECT id, email, name, role, created_at FROM users WHERE is_active = true ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}