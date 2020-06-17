<?php

use BackOffice\controllers\ContentController;
use BackOffice\DatabaseFactory;
use BackOffice\controllers\ApiController;
use BackOffice\controllers\ArticlesController;
use BackOffice\controllers\LoginController;
use BackOffice\middlewares\AuthenticationMiddleware;
use BackOffice\models\Database;
use DI\Bridge\Slim\Bridge;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use Slim\Psr7\Request;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use function DI\factory;

require_once __DIR__ . '/vendor/autoload.php';


$app = Bridge::create();

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$container = $app->getContainer();


$container->set(Environment::class, factory(function () use ($app) {

    // register twig in container
    $loader = new FilesystemLoader(__DIR__ . '/src/templates');
    $twig = new Environment($loader, [
        'debug' => true,
    ]);

    // register new function to match controller routes and their names
    $uriFunc = new TwigFunction('urlFor', [$app->getRouteCollector()->getRouteParser(), 'urlFor']);
    $twig->addFunction($uriFunc);

    return $twig;
}));


$container->set(Database::class, factory(function () use ($app) {
    // if the environment variable is set
    if (getenv('DATABASE_URL')) {
        return DatabaseFactory::getProductionServerConnection();
    }
    return DatabaseFactory::getDevelopmentServerConnection();
}));

$container->set(AuthenticationMiddleware::class, factory(function () use ($container) {
    return new AuthenticationMiddleware($container);
}));

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


$app->group('/', function (RouteCollectorProxy $group) {
    $group->get('', [ArticlesController::class, 'index'])->setName('index');
    $group->post('', [ArticlesController::class, 'create'])->setName('create');
    $group->get('delete/{id}', [ArticlesController::class, 'delete'])->setName('delete');
    $group->post('update/{id}', [ArticlesController::class, 'do_update'])->setName('do_update');
    $group->get('update/{id}', [ArticlesController::class, 'update'])->setName('update');
})->add(AuthenticationMiddleware::class);

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->get('', [LoginController::class, 'index'])->setName('index');;
    $group->post('', [LoginController::class, 'login'])->setName('login');;
});
$app->get('/logout', [LoginController::class, 'logout'])->setName('logout');

$app->group('/api', function (RouteCollectorProxy $group) {
    $group->get('/articles', [ApiController::class, 'index']);
    $group->get('/articles/{id}', [ApiController::class, 'detail']);
});

$app->group('/contents', function (RouteCollectorProxy $group) {
    $group->get('/delete/{id}', [ContentController::class, 'delete'])->setName('deleteContent');
    $group->post('/create/{id}', [ContentController::class, 'create'])->setName('createContent');
})->add(AuthenticationMiddleware::class);

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function (Request $request, Response $response) {
    throw new HttpNotFoundException($request);
});

$app->run();