<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Request;
use Framework\Response;
use Framework\RequestHandlerInterface;
use Framework\MiddlewareInterface;

class HideClientName implements MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        $request->attributes['hide_client_name'] = true;

        $response = $next->handle($request);

        return $response;
    }
}
