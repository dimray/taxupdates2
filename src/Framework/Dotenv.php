<?php

declare(strict_types=1);

namespace Framework;

class Dotenv
{

    public function load($path)
    {

        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $line) {

            list($key, $value) = explode("=", $line, 2);

            $_ENV[$key] = $value;
        }
    }
}
