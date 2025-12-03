<?php

namespace Middlewares;

use Closure;
use Core\Http\Request;
use Core\Http\Response\JsonResponse;

class HoneypotMiddleware
{
    public function __invoke(Request $request, Closure $next)
    {
        $honeypotValue = $request->post('website', null);

        if (is_null($honeypotValue) || $honeypotValue !== '') {
            return new JsonResponse(
                ['error' => 'You are not allowed to send this form!']
            );
        }

        return $next($request);
    }
}