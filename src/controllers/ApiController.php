<?php

namespace BackOffice\controllers;
use Psr\Http\Message\ResponseInterface as Response;
use BackOffice\models\ArticleRepository;

class ApiController {
    public function index(Response $response, ArticleRepository $repo){
        $articles = $repo->getAll();
        $payload = json_encode(array('articles' => $articles));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}