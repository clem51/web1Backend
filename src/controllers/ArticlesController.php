<?php

namespace BackOffice\Controllers;

use BackOffice\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;
use Twig\Environment as Twig;
use BackOffice\Models\ArticleRepository;

class ArticlesController extends AbstractController
{

    public function index(Response $response, Twig $twig, Session $session, ArticleRepository $repo)
    {
        // get All articles and show them
        $articles = $repo->getAll();
        return $this->template($twig, $response, 'articles.twig', ['username' => $session->getCurrentUser(), 'articles' => $articles]);
    }

    public function create(Request $request, Response $response, ArticleRepository $repo){
        $body = $request->getParsedBody();
        $repo->create($body);
        $response->withStatus(201);
        return $response->withHeader('Location', '/');
    }

    public function delete(int $id, Response $response, ArticleRepository $repo){
       $repo->delete($id);
       return $response->withHeader('Location', '/');
    }

    public function do_update(int $id, Response $response, ArticleRepository $repo, Request $request){
        $body = $request->getParsedBody();
        $repo->update($body, $id);
        return $response->withHeader('Location', '/');
    }

    public function update(int $id, Twig $twig, Response $response, ArticleRepository $repo){
        $article = $repo->getById($id);
        return $this->template($twig, $response, 'update.twig', [ 'article' => $article]);
    }
}
