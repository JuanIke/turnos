<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Worker;

class AuthController
{
    private $userModel;
    private $workerModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->workerModel = new Worker();
    }

    public function login()
    {
        include __DIR__ . '/../Views/auth/login.php';
    }

    public function authenticate()
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $error = 'Email y contraseña son requeridos';
            include __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $user = $this->userModel->findByEmail($email);
        
        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $error = 'Credenciales inválidas';
            include __DIR__ . '/../Views/auth/login.php';
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];

        // Obtener datos del trabajador si existe
        $worker = $this->workerModel->findByUserId($user['id']);
        if ($worker) {
            $_SESSION['worker_id'] = $worker['id'];
        }

        header('Location: /dashboard');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }
}