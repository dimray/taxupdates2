<?php

declare(strict_types=1);

namespace App;

use PDO;

class Database
{
    private ?PDO $pdo = null;

    public function __construct(
        private string $host,
        private string $dbname,
        private string $username,
        private string $password
    ) {}

    public function getConnection(): PDO
    {
        if ($this->pdo === null) {

            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return $this->pdo;
    }
}