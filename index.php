<?php

use BackOffice\controllers\LoginController;
use BackOffice\controllers\ArticlesController;
use BackOffice\DatabaseFactory;
use BackOffice\models\Database;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use DI\Bridge\Slim\Bridge;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use function DI\factory;
use BackOffice\middlewares\AuthenticationMiddleware;
use BackOffice\controllers\ApiController;

require_once __DIR__ . '/vendor/autoload.php';


$app = Bridge::create();

$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
// Récupération du container
$container = $app->getContainer();

// Lorsque la classe Environment est demandée, instancier tel que:
$container->set(Environment::class, factory(function () use ($app) {
    // Voir https://twig.symfony.com/doc/2.x/api.html#basics
    $loader = new FilesystemLoader(__DIR__ . '/src/templates');
    $twig = new Environment($loader, [
        'debug' => true,
    ]);

    // Ajout d'une fonction urlFor() pour générer des liens
    $uriFunc = new TwigFunction('urlFor', [$app->getRouteCollector()->getRouteParser(), 'urlFor']);
    $twig->addFunction($uriFunc);

    return $twig;
}));


$container->set(Database::class, factory(function () use ($app) {
    if (getenv('DATABASE_URL')) {
        return DatabaseFactory::getProductionServerConnection();
    }
    return DatabaseFactory::getDevelopmentServerConnection();
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
})->add(new AuthenticationMiddleware($container));

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->get('', [LoginController::class, 'index'])->setName('index');;
    $group->post('', [LoginController::class, 'login'])->setName('login');;
});
$app->get('/logout', [LoginController::class, 'logout']);
$app->get('/api/articles', [ApiController::class, 'index']);

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

$app->run();