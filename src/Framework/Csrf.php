<?php

declare(strict_types=1);

namespace Framework;

class Csrf
{
    public static function generateToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    public static function validateToken(?string $token): bool
    {
        return isset($_SESSION['csrf_token'], $token)
            && hash_equals($_SESSION['csrf_token'], $token);
    }
}
