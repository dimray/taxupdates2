<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use PDO;


class User extends Model
{
    protected $table = "users";

    public function activateAccount(array $user): bool
    {

        $pdo = $this->database->getConnection();

        $sql = "update {$this->getTable()} set activation_hash = null, is_active = 1 where id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":id", $user['id'], PDO::PARAM_INT);

        return $stmt->execute();
    }


    public function findArnFromUserId(int $user_id): string|bool
    {

        $pdo = $this->database->getConnection();

        $sql = "SELECT af.arn
        FROM users u
        JOIN agents a ON u.id = a.user_id
        JOIN agent_firms af ON a.agent_firm_id = af.id
        WHERE u.id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
