<?php

namespace App\Models;

use App\Database;
use PDO;

class Shift
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO shifts (name, date, start_time, end_time, shift_type_id, status) VALUES (?, ?, ?, ?, ?, 'confirmed')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['name'],
            $data['date'],
            $data['start_time'],
            $data['end_time'],
            $data['shift_type_id']
        ]);
        return $this->db->lastInsertId();
    }

    public function getAll(): array
    {
        $sql = "
            SELECT s.*, st.name as shift_type_name, st.color as shift_type_color
            FROM shifts s
            LEFT JOIN shift_types st ON s.shift_type_id = st.id
            ORDER BY s.date DESC, s.start_time
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getByMonth(int $year, int $month): array
    {
        $sql = "
            SELECT s.*, st.name as shift_type_name, st.color as shift_type_color
            FROM shifts s
            LEFT JOIN shift_types st ON s.shift_type_id = st.id
            WHERE EXTRACT(YEAR FROM s.date) = ? AND EXTRACT(MONTH FROM s.date) = ?
            ORDER BY s.date, s.start_time
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$year, $month]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array
    {
        $sql = "
            SELECT s.*, st.name as shift_type_name, st.color as shift_type_color
            FROM shifts s
            LEFT JOIN shift_types st ON s.shift_type_id = st.id
            WHERE s.id = ?
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public function getWithAssignments(int $id): ?array
    {
        $shift = $this->findById($id);
        if (!$shift) return null;

        $sql = "
            SELECT a.*, w.id as worker_id, u.name as worker_name
            FROM assignments a
            JOIN workers w ON a.worker_id = w.id
            JOIN users u ON w.user_id = u.id
            WHERE a.shift_id = ?
            ORDER BY a.camera_type
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $shift['assignments'] = $stmt->fetchAll();

        return $shift;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM shifts WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}