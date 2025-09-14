<?php

$router = new Framework\Router;

$router->group(["middleware" => "auth"], function ($router) {
    $router->add("/profile/{action}", ["controller" => "profile"]);
    $router->add("/logout", ["controller" => "session", "action" => "destroy"]);
});

$router->group(["middleware" => "guest"], function ($router) {
    $router->add("/register/{action}", ["controller" => "register"]);
    $router->add("/password/{action}", ["controller" => "password"]);
    // can't use /session/destroy as it's caught here. Can use /logout above.
    $router->add("/session/{action}", ["controller" => "session"]);

    $router->add("/register", ["controller" => "register", "action" => "new"]);
    $router->add("/login/new", ["controller" => "session", "action" => "new"]);
    $router->add("/login", ["controller" => "session", "action" => "new"]);
});

$router->group(["middleware" => "hide_tax_year"], function ($router) {
    $router->add("/clients/{action}", ["controller" => "clients"]);
});

$router->group(["namespace" => "Endpoints", "middleware" => "hide_tax_year"], function ($router) {
    $router->add("/agent-authorisation/{action}", ["controller" => "agent-authorisation"]);
    $router->add("/agent-authorisation-test-support/{action}", ["controller" => "agent-authorisation-test-support"]);
});

$router->group(["namespace" => "Endpoints"], function ($router) {
    $router->add("/business-details/{action}", ["controller" => "business-details"]);
    $router->add("/obligations/{action}", ["controller" => "obligations"]);
    $router->add("/self-employment/{action}", ["controller" => "self-employment"]);
    $router->add("/property-business/{action}", ["controller" => "property-business"]);
    $router->add("/individual-calculations/{action}", ["controller" => "individual-calculations"]);
    $router->add("/individual-losses/{action}", ["controller" => "individual-losses"]);
});

$router->add("/", ["controller" => "home", "action" => "index"]);


$router->add("/{controller}/{action}");

// 404 handler if hasn't matched any other route
$router->add("/{any:.*}", [
    "controller" => "error",
    "action" => "notFound"
]);

return $router;
