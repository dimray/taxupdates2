<?php

return [
    "message" => \App\Middleware\ChangeResponseExample::class,
    "trim" => \App\Middleware\ChangeRequestExample::class,
    "deny" => \App\Middleware\RedirectExample::class,
    "auth" => \App\Middleware\RequireLogin::class,
    "guest" => \App\Middleware\RedirectIfAuthenticated::class,
    "hide_tax_year" => \App\Middleware\HideTaxYear::class
];
