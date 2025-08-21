<?php

namespace App\Controllers;

use App\Models\WorkGroup;
use App\Models\Shift;

class AIAssignController
{
    private $workGroupModel;
    private $shiftModel;

    public function __construct()
    {
        $this->workGroupModel = new WorkGroup();
        $this->shiftModel = new Shift();
    }

    public function processAssignment()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        $groupId = $input['group_id'] ?? null;
        $instructions = $input['instructions'] ?? '';

        if (!$groupId || !$instructions) {
            echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
            return;
        }

        try {
            // Obtener datos del grupo
            $users = $this->workGroupModel->getUsersByGroup($groupId);
            $shifts = $this->shiftModel->getAll();

            // Procesar con IA simulada (aquí integrarías OpenAI, Claude, etc.)
            $assignments = $this->simulateAIAssignment($users, $shifts, $instructions);

            // Guardar asignaciones (aquí implementarías la lógica de guardado)
            $this->saveAssignments($assignments);

            echo json_encode(['success' => true, 'assignments' => $assignments]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function simulateAIAssignment($users, $shifts, $instructions)
    {
        // Simulación de IA - aquí integrarías una IA real
        $assignments = [];
        
        // Lógica básica de asignación basada en instrucciones
        foreach ($shifts as $shift) {
            // Seleccionar usuario basado en instrucciones
            $selectedUser = $this->selectUserBasedOnInstructions($users, $shift, $instructions);
            
            if ($selectedUser) {
                $assignments[] = [
                    'shift_id' => $shift['id'],
                    'user_id' => $selectedUser['id'],
                    'shift_name' => $shift['name'],
                    'user_name' => $selectedUser['name'],
                    'date' => $shift['date']
                ];
            }
        }

        return $assignments;
    }

    private function selectUserBasedOnInstructions($users, $shift, $instructions)
    {
        // Lógica simple basada en palabras clave en las instrucciones
        $instructions = strtolower($instructions);
        
        // Si menciona rotación equitativa
        if (strpos($instructions, 'rotar') !== false || strpos($instructions, 'equitativ') !== false) {
            return $users[array_rand($users)];
        }
        
        // Si menciona nombres específicos
        foreach ($users as $user) {
            $userName = strtolower($user['name']);
            if (strpos($instructions, $userName) !== false) {
                return $user;
            }
        }
        
        // Por defecto, seleccionar aleatoriamente
        return $users[array_rand($users)];
    }

    private function saveAssignments($assignments)
    {
        // Aquí implementarías el guardado real en base de datos
        // Por ahora es simulado
        return true;
    }
}