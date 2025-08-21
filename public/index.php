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
        
    case (preg_match('/^\/shifts\/([0-9]+)\/edit$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin')) {
            header('Location: /dashboard');
            exit;
        }
        $controller = new ShiftController();
        $controller->edit($matches[1]);
        break;
        
    case '/shifts/auto-assign':
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin')) {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\AutoAssignController();
        $controller->index();
        break;
        
    case (preg_match('/^\/shifts\/auto-assign\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin')) {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\AutoAssignController();
        $controller->showGroup($matches[1]);
        break;
        
    case '/shifts/ai-assign':
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'superadmin')) {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\AIAssignController();
        $controller->processAssignment();
        break;
        
    case '/work-groups':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->index();
        break;
        
    case '/work-groups/assign':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->assignUser();
        break;
        
    case '/work-groups/remove':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->removeUser();
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/edit$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->edit($matches[1]);
        break;
        
    case '/work-groups/create':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->createGroup();
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/delete$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->delete($matches[1]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/users$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->showUsers($matches[1]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/add-user$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->addUserToGroup($matches[1]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/remove-user\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->removeUserFromGroup($matches[1], $matches[2]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/user-roles\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->getUserRoles($matches[1], $matches[2]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/assign-roles$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->assignRoles($matches[1]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/create-role$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->createRole($matches[1]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/edit-role$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->editRole($matches[1]);
        break;
        
    case (preg_match('/^\/work-groups\/([0-9]+)\/delete-role\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\WorkGroupController();
        $controller->deleteRole($matches[1], $matches[2]);
        break;
        
    case '/users':
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->index();
        break;
        
    case (preg_match('/^\/users\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('Location: /dashboard');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->show($matches[1]);
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/update-name$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->updateName($matches[1]);
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/groups$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->showGroups($matches[1]);
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/update-groups$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->updateGroups($matches[1]);
        break;
        

        
    case (preg_match('/^\/users\/([0-9]+)\/update-roles\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->updateRoles($matches[1], $matches[2]);
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/available-groups$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->showAvailableGroups($matches[1]);
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/groups\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->addUserToGroup($matches[1], $matches[2]);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $controller->removeUserFromGroup($matches[1], $matches[2]);
        }
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/groups\/([0-9]+)\/available-roles$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->showAvailableRoles($matches[1], $matches[2]);
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/roles\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->addRoleToUser($matches[1], $matches[2]);
        } elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $controller->removeRoleFromUser($matches[1], $matches[2]);
        } else {
            $controller->showRoles($matches[1], $matches[2]);
        }
        break;
        
    case (preg_match('/^\/users\/([0-9]+)\/user-roles\/([0-9]+)$/', $path, $matches) ? true : false):
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'superadmin') {
            header('HTTP/1.1 403 Forbidden');
            exit;
        }
        $controller = new \App\Controllers\UserController();
        $controller->showRoles($matches[1], $matches[2]);
        break;
        
    default:
        http_response_code(404);
        echo "Página no encontrada";
        break;
}