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
use App\Controllers\ProfileController;

Session::start();

$router = new Router();
$auth = new AuthController();
$dash = new DashboardController();
$post = new PostController();
$profile = new ProfileController();

// Auth routes
$router->get('/', fn() => $auth->showLogin());
$router->get('/login', fn() => $auth->showLogin());
$router->get('/register', fn() => $auth->showRegister());
$router->post('/register', fn() => $auth->register());
$router->post('/login', fn() => $auth->login());
$router->get('/logout', fn() => $auth->logout());

// Dashboard
$router->get('/dashboard', fn() => $dash->index());

// Posts routes
$router->get('/posts', fn() => $post->index());
$router->get('/posts/feed', fn() => $post->feed());
$router->get('/posts/create', fn() => $post->create());
$router->post('/posts/store', fn() => $post->store());
$router->post('/posts/delete', fn() => $post->delete());
$router->post('/posts/like', fn() => $post->like());
$router->post('/posts/comment', fn() => $post->comment());
$router->post('/posts/delete-comment', fn() => $post->deleteComment());

// Profile routes
$router->get('/profile', fn() => $profile->showProfile());
$router->post('/profile/update', fn() => $profile->updateProfile());
$router->post('/profile/picture', fn() => $profile->updateProfilePicture());

// Public profile routes
$router->get('/profile/{id}', [ProfileController::class, 'showPublicProfile']);
$router->get('/profile/{id}/followers', [ProfileController::class, 'showFollowers']);
$router->get('/profile/{id}/following', [ProfileController::class, 'showFollowing']);

// Follow/unfollow routes
$router->post('/profile/follow', fn() => $profile->follow());
$router->post('/profile/unfollow', fn() => $profile->unfollow());

// User search
$router->get('/search/users', fn() => $profile->searchUsers());

$router->dispatch($_SERVER['REQUEST_URI'] ?? '/', $_SERVER['REQUEST_METHOD'] ?? 'GET');