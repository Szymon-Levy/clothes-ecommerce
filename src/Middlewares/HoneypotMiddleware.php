<?php

namespace Middlewares;

use Closure;
use Core\Http\Request;

class HoneypotMiddleware
{
    public function __invoke(Request $request, Closure $next)
    {
        $honeypotValue = $request->post('website', null);

        if (is_null($honeypotValue) || $honeypotValue !== '') {
            $response['error'] = 'You are not allowed to send this form!';
            echo json_encode($response);

            return;
        }

        return $next($request);
    }
}