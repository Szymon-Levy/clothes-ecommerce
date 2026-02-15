<?php

namespace Core\Security\Csrf;

use Core\Http\Session\Session;
use Core\Security\TokenGenerator\TokenGenerator;

class Csrf
{
    protected string $token;

    public function __construct(
        protected Session $session,
        protected TokenGenerator $tokenGenerator
    )
    {
        if ($session->has('csrf_token')) {
            $this->token = $this->session->get('csrf_token');
        } else {
            $this->token = $this->tokenGenerator->generate();

            $this->session->set('csrf_token', $this->token);
        }
    }

    public function setInCookie()
    {
        if (! isset($_COOKIE['csrf_token'])) {
            setcookie('csrf_token', $this->token, 0, '/', '', false, false);
        }
    }

    public function checkIfTokenValid(string $requestToken)
    {
        return hash_equals($this->token, $requestToken);
    }
}