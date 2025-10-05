<?php

namespace Core;

class Csrf
{
    protected Session $session;
    protected string $token;

    public function __construct(Session $session)
    {
        $this->session = $session;

        if (!$session->has('csrf_token')) {
            $this->token = $this->generateToken();
            $this->session->set('csrf_token', $this->token);
        } else {
            $this->token = $this->session->get('csrf_token');
        }

        $this->setTokenInCookie();
    }

    private function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function regenerateToken()
    {
        $this->token = $this->generateToken();
        $this->session->set('csrf_token', $this->token);
        $this->setTokenInCookie();
    }

    private function setTokenInCookie()
    {
        setcookie('csrf_token', $this->token, 0, '/', '', false, false);
    }

    public function validateToken(string $request_token)
    {
        return $this->token == $request_token;
    }
}