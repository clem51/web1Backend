<?php

namespace BackOffice\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use BackOffice\models\ArticleRepository;

class ApiController
{
    public function index(Response $response, ArticleRepository $repo): Response
    {
        $articles = $repo->getAll();
        $payload = json_encode(array('articles' => $articles));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function detail(int $id, Response $response, ArticleRepository $repo): Response
    {
        $article = $repo->getById($id);
        $payload = json_encode(array('article' => $article));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}

