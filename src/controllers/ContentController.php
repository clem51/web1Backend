<?php

namespace BackOffice\controllers;


use BackOffice\models\ContentRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Request;


class ContentController extends AbstractController
{
    public function delete(int $id, Response $response, ContentRepository $repo): Response
    {
        $repo->delete($id);
        return $response->withHeader('Location', "/");
    }

    public function create(int $id, Response $response, ContentRepository $repo, Request $request): Response
    {
        $body = $request->getParsedBody();
        $content_params = $this->aggregate($body);
        $repo->create($id, $content_params);
        $response->withStatus(201);
        return $response->withHeader('Location', '/');
    }
}