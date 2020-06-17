<?php

namespace BackOffice\controllers;

use BackOffice\services\SessionService as Session;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;
use Twig\Environment as Twig;
use BackOffice\models\ArticleRepository;

class ArticlesController extends AbstractController
{

    public function index(Response $response, Twig $twig, Session $session, ArticleRepository $repo) :Response
    {
        // get All articles and show them
        $articles = $repo->getAll();
        return $this->template($twig, $response, 'articles.twig', ['username' => $session->getCurrentUser(), 'articles' => $articles, 'content_type' => CONTENT_TYPE]);
    }

    public function create(Request $request, Response $response, ArticleRepository $repo) :Response
    {
        $body = $request->getParsedBody();
        $content_params = $this->aggregate($body);
        $repo->create($body['name'], $content_params);
        $response->withStatus(201);
        return $response->withHeader('Location', '/');
    }

    public function delete(int $id, Response $response, ArticleRepository $repo) :Response
    {
        $repo->delete($id);
        return $response->withHeader('Location', '/');
    }

    public function do_update(int $id, Response $response, ArticleRepository $repo, Request $request) :Response
    {
        $body = $request->getParsedBody();
        $repo->update($body, $id);
        return $response->withHeader('Location', '/');
    }

    public function update(int $id, Twig $twig, Response $response, ArticleRepository $repo) :Response
    {
        $article = $repo->getById($id);
        return $this->template($twig, $response, 'update.twig', ['article' => $article]);
    }

    /**
     * Plug together form data with the same id
     * @param array $data
     * @return array
     */
    private function aggregate(array $data) :array
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
