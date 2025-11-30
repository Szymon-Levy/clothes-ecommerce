<?php

namespace Middlewares;

use Closure;
use Core\Http\Csrf;
use Core\Http\Request;
use Middlewares\Exceptions\CsrfException;

class CsrfMiddleware
{
    public function __construct(
        protected Csrf $csrf
    ){}

    public function __invoke(Request $request, Closure $next)
    {
        $token = $request->post('csrf');

        if ($token === '' || ! $this->csrf->checkIfTokenValid($token)) {
            throw new CsrfException("Invalid CSRF token");
        }

        return $next($request);
    }
}