<?php

namespace BackOffice\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;

Class LoginController{
    
    private $user_repository;

    function __construct($user_repository){
        $this->user_repository = $user_repository;
    }

    function index(Request $request, Response $response, $args){
        return Twig::fromRequest($request)->render($response, 'login.twig');
    }

    function login(Request $request, Response $response, $args){
        $body = $request->getParsedBody();
        $username = $body['username'];
        $password = $body['password'];
        $user = $this->user_repository->getUserByUsername($username);
        if($user){
            // Attempt to execute the prepared statement
            if(password_verify($password, $user->password)){
                // TODO set session
                return Twig::fromRequest($request)->render($response, 'dashboard.twig');
            }else{
                // TODO redirect with errors
                return Twig::fromRequest($request)->render($response, 'login.twig', ['errors' => 'wrong credentials' ]);
            }
        }
    }
}