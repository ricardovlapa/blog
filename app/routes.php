<?php

use App\Controller\AboutController;
use App\Controller\BlogController;
use App\Controller\ContactController;
use App\Controller\EditorialPrivacyController;
use App\Controller\FeedController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Model\PostRepository;
use App\Model\TagRepository;
use App\Router;

return function (Router $router, array $site, PostRepository $posts, TagRepository $tags): void {
    $router->get('/', function () use ($site, $posts) {
        (new HomeController($site, $posts))->show();
    });

    $router->get('/about', function () use ($site) {
        (new AboutController($site))->show();
    });

    $router->get('/nota-editorial-e-de-privacidade', function () use ($site) {
        (new EditorialPrivacyController($site))->show();
    });

    $router->get('/contacto', function () use ($site) {
        (new ContactController($site))->show();
    });

    $router->post('/contacto', function () use ($site) {
        (new ContactController($site))->submit();
    });

    $router->get('/blog', function () use ($site, $posts, $tags) {
        (new BlogController($site, $posts, $tags))->show();
    });

    $router->get('/blog/tag/{tag}', function (array $params) use ($site, $posts, $tags) {
        (new BlogController($site, $posts, $tags))->show($params['tag'] ?? '');
    });

    $router->get('/post/{slug}', function (array $params) use ($site, $posts) {
        (new PostController($site, $posts))->show($params['slug']);
    });

    $router->get('/feed.xml', function () use ($site, $posts) {
        (new FeedController($site, $posts))->show();
    });
};
