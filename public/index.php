<?php
require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/app/dotenv.php';

load_env([
    dirname(__DIR__) . '/.env',
    dirname(__DIR__) . '/.env.local',
]);

header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header(
    "Content-Security-Policy: default-src 'self'; " .
    "img-src 'self' https: data:; " .
    "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
    "font-src https://fonts.gstatic.com; " .
    "script-src 'self' 'unsafe-inline'; " .
    "connect-src 'self'; " .
    "base-uri 'self'; " .
    "form-action 'self'; " .
    "frame-ancestors 'self'"
);

use App\Model\PostRepository;
use App\Model\TagRepository;
use App\Router;

$config = require dirname(__DIR__) . '/app/config.php';

$site = $config['site'];
$posts = new PostRepository($config['postsData']);
$tags = new TagRepository($config['tagsData'] ?? '');

$router = new Router();
$registerRoutes = require dirname(__DIR__) . '/app/routes.php';
$registerRoutes($router, $site, $posts, $tags);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim($path, '/');
$path = $path === '' ? '/' : $path;

$router->dispatch($_SERVER['REQUEST_METHOD'], $path);
