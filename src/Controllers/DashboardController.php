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
        $currentMonth = date('n');
        $currentYear = date('Y');
        
        $shifts = $this->shiftModel->getByMonth($currentYear, $currentMonth);
        $workers = $this->workerModel->getAll();
        
        $stats = [
            'total_shifts' => count($shifts),
            'total_workers' => count($workers),
            'pending_shifts' => count(array_filter($shifts, fn($s) => $s['status'] === 'pending')),
            'training_workers' => count(array_filter($workers, fn($w) => $w['training_stage'] !== 'COMPLETED'))
        ];

        include __DIR__ . '/../Views/dashboard/index.php';
    }
}