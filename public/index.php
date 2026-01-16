<?php

session_start();

// Composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use app\core\Database;
use app\core\Router;
use app\controller\StudentController;

// Initialize Database
Database::getInstance();

// Router setup
$router = new Router();

// Public routes
$router->get('/', [StudentController::class, 'showLogin']);
$router->get('/register', [StudentController::class, 'showRegister']);
$router->post('/register', [StudentController::class, 'register']);
$router->get('/login', [StudentController::class, 'showLogin']);
$router->post('/login', [StudentController::class, 'login']);

// Protected routes
$router->get('/student/dashboard', [StudentController::class, 'dashboard']);
$router->get('/logout', [StudentController::class, 'logout']);

// Course enrollment - you need to manually add routes for each course ID
// OR use query parameter: /student/enroll?id=1
$router->post('/student/enroll', [StudentController::class, 'enrollCourse']);

// Dispatch
$router->dispatch();