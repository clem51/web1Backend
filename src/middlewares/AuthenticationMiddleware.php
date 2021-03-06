<?php

namespace BackOffice\middlewares;

use BackOffice\services\SessionService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use BackOffice\services\SessionService as Session;

class AuthenticationMiddleware
{
    private SessionService $session;

    public function __construct($container)
    {
        $this->session = $container->get(Session::class);
    }

    /**
     * Example middleware invokable class
     *
     * @param Request $request PSR-7 request
     * @param RequestHandler $handler PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        if (!$this->session->getCurrentUser()) {
            $response = new Response();
            $response->withStatus(401);
            return $response->withHeader('Location', '/login');
        }

        return $handler->handle($request);
    }
}