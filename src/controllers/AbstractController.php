<?php

namespace BackOffice\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\StreamFactory;
use Twig\Environment as Twig;

abstract class AbstractController
{
    protected function template(Twig $twig, Response $response, string $template, array $vars = []) : Response
    {
        // Rendu de template Twig
        $content = $twig->render($template, $vars);

        // Création d'un Stream PSR-7
        $factory = new StreamFactory();
        $stream = $factory->createStream($content);

        // Retourner une Response
        return $response->withBody($stream);
    }
}