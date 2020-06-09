<?php


namespace BackOffice;


class Session
{
    public function __construct()
    {
        // init session
        session_start();
    }

    public function getCurrentUser(): string
    {
        //get current user from session
        return $_SESSION["currentUser"];
    }

    public function setCurrentUser(string $username): void
    {
        //register current user in session
        $_SESSION['currentUser']= $username;
    }

    public function unsetCurrentUser()
    {
        // remove user from session
        session_destroy();
    }

}