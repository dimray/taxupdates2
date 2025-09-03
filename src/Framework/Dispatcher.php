<?php

declare(strict_types=1);

namespace Framework;

use Exception;
use DomainException;
use ReflectionMethod;

class Dispatcher
{
    public function __construct(
        private Router $router,
        private Container $container,
        private array $middleware_classes
    ) {}

    public function handle(Request $request): Response
    {
        $path = $this->getPath($request->uri);

        $params = $this->router->match($path, $request->method);

        if ($params === false) {
            throw new DomainException("Page not found");
        }

        $controller = $this->getControllerName($params);

        $action = $this->getActionName($params);

        $controller_object = $this->container->get($controller);

        // $controller_object->setRequest($request);

        $controller_object->setResponse($this->container->get(Response::class));

        $controller_object->setViewer($this->container->get(Viewer::class));

        $args = $this->getActionArguments($controller, $action, $params);

        $controller_handler = new ControllerRequestHandler(
            $controller_object,
            $action,
            $args
        );

        $middleware = $this->getMiddleware($params);

        $middleware_handler = new MiddlewareRequestHandler(
            $middleware,
            $controller_handler
        );

        return $middleware_handler->handle($request);
    }

    private function getMiddleware(array $params): array
    {

        if (! array_key_exists("middleware", $params)) {

            return [];
        }

        $middleware = explode("|", $params["middleware"]);

        array_walk($middleware, function (&$value) {

            if (! array_key_exists($value, $this->middleware_classes)) {

                throw new Exception("Middleware '$value' not found in config settings");
            }

            $value = $this->container->get($this->middleware_classes[$value]);
        });

        return $middleware;
    }

    private function getPath(string $uri)
    {

        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === false) {

            throw new DomainException("Malformed URL: '$uri'");
        }

        return $path;
    }


    private function getControllerName(array $params)
    {
        $namespace = "App\Controllers\\";

        if (array_key_exists("namespace", $params)) {
            $namespace .= $params['namespace'] . "\\";
        }

        $controller = str_replace("-", "", ucwords(strtolower($params['controller']), "-"));

        return $namespace . $controller;
    }

    private function getActionName(array $params)
    {
        $action = lcfirst(str_replace("-", "", ucwords(strtolower($params['action']), "-")));

        return $action;
    }


    private function getActionArguments(string $controller, string $action, array $params)
    {
        $args = [];

        $method = new ReflectionMethod($controller, $action);

        foreach ($method->getParameters() as $parameter) {

            $name = $parameter->getName();

            $args[$name] = $params[$name];
        }

        return $args;
    }
}
