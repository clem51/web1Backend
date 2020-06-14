<?php

namespace BackOffice\Controllers;
use Psr\Http\Message\ResponseInterface as Response;
use BackOffice\Models\ArticleRepository;
use \PDO;

class ApiController {
    public function index(Response $response, ArticleRepository $repo){
        $articles = $repo->getAll(PDO::FETCH_ASSOC);
        $payload = json_encode(array('articles' => $articles));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}