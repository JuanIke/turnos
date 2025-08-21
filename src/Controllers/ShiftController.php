<?php

namespace App\Controllers;

use App\Models\Shift;

class ShiftController
{
    private $shiftModel;

    public function __construct()
    {
        $this->shiftModel = new Shift();
    }

    public function index()
    {
        $shifts = $this->shiftModel->getAll();
        include __DIR__ . '/../Views/shifts/index.php';
    }

    public function create()
    {
        $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($_POST['date'] ?? '', ENT_QUOTES, 'UTF-8');
        $start_time = htmlspecialchars($_POST['start_time'] ?? '', ENT_QUOTES, 'UTF-8');
        $end_time = htmlspecialchars($_POST['end_time'] ?? '', ENT_QUOTES, 'UTF-8');
        $shift_type_id = filter_input(INPUT_POST, 'shift_type_id', FILTER_VALIDATE_INT);

        if (!$name || !$date || !$start_time || !$end_time) {
            $error = 'Todos los campos son requeridos';
            $shifts = $this->shiftModel->getAll();
            include __DIR__ . '/../Views/shifts/index.php';
            return;
        }

        $this->shiftModel->create([
            'name' => $name,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'shift_type_id' => $shift_type_id ?: 1
        ]);

        header('Location: /shifts');
        exit;
    }

    public function delete($id)
    {
        $this->shiftModel->delete($id);
        header('Location: /shifts');
        exit;
    }

    public function edit($id)
    {
        $name = htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8');
        $date = htmlspecialchars($_POST['date'] ?? '', ENT_QUOTES, 'UTF-8');
        $start_time = htmlspecialchars($_POST['start_time'] ?? '', ENT_QUOTES, 'UTF-8');
        $end_time = htmlspecialchars($_POST['end_time'] ?? '', ENT_QUOTES, 'UTF-8');

        if (!$name || !$date || !$start_time || !$end_time) {
            header('Location: /shifts');
            exit;
        }

        $this->shiftModel->update($id, [
            'name' => $name,
            'date' => $date,
            'start_time' => $start_time,
            'end_time' => $end_time
        ]);

        header('Location: /shifts');
        exit;
    }
}