<?php

namespace BackOffice\Controllers;

use BackOffice\Models\UserRepository;
use BackOffice\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\App;
use Slim\Psr7\Request;
use Twig\Environment as Twig;


class LoginController extends AbstractController
{

    private $user_repository;

    function __construct(UserRepository $user_repository)
    {
        $this->user_repository = $user_repository;
    }

    public function index(Response $response, Twig $twig, Session $session)
    {
        if ($session->getCurrentUser()) {
           return header('Location: /');
        }
        return $this->template($twig, $response, 'login.twig');
    }


    function login(Request $request, Response $response, Twig $twig, Session $session, App $app)
    {
        $body = $request->getParsedBody();
        $username = $body['username'];
        $password = $body['password'];
        $user = $this->user_repository->getUserByUsername($username);

        if ($user) {
            // Attempt to execute the prepared statement
            if (password_verify($password, $user->password)) {
                $session->setCurrentUser($username);
                return $response->withHeader('Location', '/');
            } else {
                return $this->template($twig, $response, 'login.twig', ['errors' => ['password' => 'password does not match']]);
            }
        }
        // user not found
        return $this->template($twig, $response, 'login.twig', ['errors' => ['username' => 'user not found']]);
    }

    public function logout(Response $response, Session $session)
    {
        $session->unsetCurrentUser();
        return $response->withHeader('Location', '/login');
    }
}