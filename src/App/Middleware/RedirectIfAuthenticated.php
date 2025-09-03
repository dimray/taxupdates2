<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Request;
use Framework\Response;
use Framework\RequestHandlerInterface;
use Framework\MiddlewareInterface;

class RedirectIfAuthenticated implements MiddlewareInterface
{
    public function __construct(private Response $response) {}

    public function process(Request $request, RequestHandlerInterface $next): Response
    {
        // if logged in, redirect to home page
        if (!empty($_SESSION['user_id'])) {

            $this->response->redirect("/");

            return $this->response;
        }

        $response = $next->handle($request);

        return $response;
    }
}
