<?php

namespace BackOffice\Controllers;

use BackOffice\Session;
use Psr\Http\Message\ResponseInterface as Response;
use Twig\Environment as Twig;

class DashboardController extends AbstractController
{

    public function index(Response $response, Twig $twig, Session $session)
    {
        return $this->template($twig, $response, 'dashboard.twig', ['username' => $session->getCurrentUser()]);
    }

}
