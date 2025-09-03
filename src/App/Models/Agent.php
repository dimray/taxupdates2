<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use PDO;

class Agent extends Model
{

    protected $table = "agents";

    public function isAdmin(int $user_id): bool
    {

        $pdo = $this->database->getConnection();

        $sql = "select agent_admin from agents where user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(['user_id' => $user_id]);

        $result = $stmt->fetchColumn();

        return $result === '1' || $result === 1;
    }


    public function countAgentsFromFirm($firm_id)
    { // counts active agents, needs to join users table

        $pdo = $this->database->getConnection();

        $sql = "SELECT COUNT(*) AS count FROM agents a 
        JOIN users u ON a.user_id = u.id
        WHERE a.agent_firm_id = :firm_id
        AND u.is_active = 1";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(['firm_id' => $firm_id]);

        return (int) $stmt->fetchColumn();
    }

    public function getAgentsArray(int $agent_firm_id, int $limit, int $offset): array
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT a.user_id, u.name, u.email, a.agent_admin
        FROM agents a
        JOIN users u ON a.user_id = u.id
        WHERE a.agent_firm_id = :agent_firm_id
        AND u.is_active = 1
        ORDER BY a.agent_admin DESC, u.name ASC
        LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":agent_firm_id", $agent_firm_id, PDO::PARAM_INT);
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getFirmId(int $user_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "select agent_firm_id from agents where user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function deleteInactiveAgents(int $firm_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "DELETE u FROM users u
        JOIN agents a ON u.id = a.user_id
        WHERE a.agent_firm_id = :firm_id
        AND u.is_active = 0;";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(['firm_id' => $firm_id]);
    }

    // used by Home
    public function getAccessToken(int $user_id): ?string
    {
        $pdo = $this->database->getConnection();

        $sql = "select access_token from agents where user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        $stmt->execute();

        $row = $stmt->fetch();

        if ($row && isset($row['access_token'])) {
            return (string) $row['access_token'];
        } else {
            return null;
        }
    }

    public function saveTokens(int $user_id, string $access_token, string $refresh_token): bool
    {
        $pdo = $this->database->getConnection();

        $sql = "update agents set access_token = :access_token, refresh_token = :refresh_token where user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":access_token", $access_token, PDO::PARAM_STR);
        $stmt->bindValue(":refresh_token", $refresh_token, PDO::PARAM_STR);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
