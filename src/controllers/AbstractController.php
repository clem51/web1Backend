<?php

namespace BackOffice\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Factory\StreamFactory;
use Twig\Environment as Twig;

abstract class AbstractController
{
    protected function template(Twig $twig, Response $response, string $template, array $vars = []): Response
    {
        // Rendu de template Twig
        $content = $twig->render($template, $vars);

        // Création d'un Stream PSR-7
        $factory = new StreamFactory();
        $stream = $factory->createStream($content);

        // Retourner une Response
        return $response->withBody($stream);
    }


    /**
     * Plug together form data with the same id
     * @param array $data
     * @return array
     */
    protected function aggregate(array $data): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            preg_match("/^value_([0-9]+)/", $key, $id);
            if ($id[1]) {
                array_push($result, array('type' => $data["type_$id[1]"], 'value' => $value, "name" => $data["name_$id[1]"]));
            }
        }
        return $result;
    }
}