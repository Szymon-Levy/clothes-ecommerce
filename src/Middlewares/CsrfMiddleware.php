<?php

namespace Middlewares;

use Closure;
use Core\Http\Request;
use Core\Http\Response\HtmlResponse;
use Core\Utils\Csrf;

class CsrfMiddleware
{
    public function __construct(
        protected Csrf $csrf
    ){}

    public function __invoke(Request $request, Closure $next)
    {
        $token = $request->post('csrf');

        if ($token === '' || ! $this->csrf->checkIfTokenValid($token)) {
            return new HtmlResponse('', 403);
        }

        return $next($request);
    }
}