<?php

use BackOffice\Controllers\LoginController;
use BackOffice\Controllers\DashboardController;
use BackOffice\DatabaseFactory;
use BackOffice\Models\Database;
use Slim\Routing\RouteCollectorProxy;
use DI\Bridge\Slim\Bridge;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use function DI\factory;


require_once __DIR__ . '/vendor/autoload.php';


$app = Bridge::create();


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


$app->get('/', [DashboardController::class, 'index'])
    ->setName('index');

$app->group('/login', function (RouteCollectorProxy $group) {
    $group->get('', [LoginController::class, 'index']);
    $group->post('', [LoginController::class, 'login']);
});

$app->run();