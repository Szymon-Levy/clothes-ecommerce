<?php

namespace Core\Http;

class Csrf
{
    protected Session $session;
    protected string $token;

    public function __construct(Session $session)
    {
        $this->session = $session;

        if ($session->has('csrf_token')) {
            $this->token = $this->session->get('csrf_token');
        } else {
            $this->token = $this->generateToken();
            $this->session->set('csrf_token', $this->token);
        }
    }

    private function generateToken()
    {
        return bin2hex(random_bytes(32));
    }

    public function setInCookie()
    {
        if (!isset($_COOKIE['csrf_token'])) {
            setcookie('csrf_token', $this->token, 0, '/', '', false, false);
        }
    }

    public function validateToken(string $requestToken)
    {
        return hash_equals($this->token, $requestToken);
    }
}