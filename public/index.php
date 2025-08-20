<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\ShiftController;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Iniciar sesión
session_start();

// Obtener la ruta
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Enrutamiento simple
switch ($path) {
    case '/':
        if (isset($_SESSION['user_id'])) {
            $controller = new DashboardController();
            $controller->index();
        } else {
            $controller = new AuthController();
            $controller->login();
        }
        break;
        
    case '/login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->authenticate();
        } else {
            $controller->login();
        }
        break;
        
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case '/dashboard':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case '/shifts':
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        $controller = new ShiftController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->create();
        } else {
            $controller->index();
        }
        break;
        
    case (preg_match('/^\/shifts\/([0-9]+)\/delete$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new ShiftController();
        $controller->delete($matches[1]);
        break;
        
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}