<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Flash;
use Framework\MiddlewareInterface;
use Framework\Response;
use Framework\Request;
use Framework\RequestHandlerInterface;

class RequireLogin implements MiddlewareInterface
{
    public function __construct(private Response $response) {}


    public function process(Request $request, RequestHandlerInterface $next): Response
    {

        // redirect to login page if not logged in
        if (!empty($_SESSION['user_id'])) {

            $response = $next->handle($request);

            return $response;
        } else {

            $_SESSION['redirect'] = $request->uri;

            Flash::addMessage("You must be logged in to access this content");

            $this->response->redirect("/session/new");

            return $this->response;
        }
    }
}
