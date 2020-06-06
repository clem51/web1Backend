<?php

use BackOffice\Controllers\LoginController;
use BackOffice\Models\Database;
use BackOffice\Models\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

require_once __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$twig = Twig::create('src/templates');
$app->add(TwigMiddleware::create($app, $twig));

// Todo DI
$db = new Database('mysql:host=localhost;dbname=dashboard_db;port=3306;', 'root', 'root');
$user_repository = new UserRepository($db);
$login_controler = new LoginController($user_repository);

$app->get('/', function (Request $request, Response $response, $args) {
    $lol = 'lolol';
    return Twig::fromRequest($request)->render($response, 'dashboard.twig', [
        'lol' => $lol
    ]);
});

$app->get('/login', function (Request $request, Response $response, $args){
    global $login_controler;
    return $login_controler->index($request, $response, $args);
});

$app->post('/login', function (Request $request, Response $response, $args) {
    global $login_controler;
    return $login_controler->login($request, $response, $args);
});

$app->run();

//[GET] url = /login ->  redirige sur login_controller ->  action index -> render login vue
//[POST] url /login ( username=admin, password=clemen51) -> redirige sur login_controller -> action login (utilise models pour interagir avec la bdd) -> if login ok -> welcome.php
                                                                                                        //-> if login ko -> login.php with errors