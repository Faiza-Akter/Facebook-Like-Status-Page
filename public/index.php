<?php
declare(strict_types=1);

// autoload
require __DIR__ . '/../vendor/autoload.php';

// tiny .env loader (reads .env into getenv and $_ENV)
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        [$key, $val] = array_map('trim', explode('=', $line, 2) + [1=>null]);
        if ($key && $val !== null) {
            putenv("$key=$val");
            $_ENV[$key] = $val;
        }
    }
}

use App\Core\Router;
use App\Core\Session;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\PostController;

Session::start();

$router = new Router();
$auth = new AuthController();
$dash = new DashboardController();
$post = new PostController();
use App\Controllers\ProfileController; 

// Profile routes
$router->get('/profile', [ProfileController::class, 'showProfile']);
$router->post('/profile/update', [ProfileController::class, 'updateProfile']);
$router->post('/profile/picture', [ProfileController::class, 'updateProfilePicture']);

// Post delete route
$router->post('/posts/delete', [PostController::class, 'delete']);

$router->get('/', fn() => $auth->showLogin());
$router->get('/login', fn() => $auth->showLogin());
$router->get('/register', fn() => $auth->showRegister());
$router->get('/dashboard', fn() => $dash->index());
$router->get('/posts', fn() => $post->index());
$router->get('/posts/create', fn() => $post->create());

$router->post('/register', fn() => $auth->register());
$router->post('/login', fn() => $auth->login());
$router->post('/posts/store', fn() => $post->store());

$router->get('/logout', fn() => $auth->logout());

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');