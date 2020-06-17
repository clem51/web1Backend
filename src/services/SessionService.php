<?php


namespace BackOffice\services;


class SessionService
{
    public function __construct()
    {
        // init session
        session_start();
    }

    public function getCurrentUser(): ?string
    {
        //get current user from session
        if (isset($_SESSION["currentUser"])) {
            return $_SESSION["currentUser"];
        }
        return null;
    }

    public function setCurrentUser(string $username): void
    {
        //register current user in session
        $_SESSION['currentUser'] = $username;
    }

    public function unsetCurrentUser(): void
    {
        // remove user from session
        session_destroy();
    }

}