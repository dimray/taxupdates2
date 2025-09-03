<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Helper;
use Framework\Model;
use PDO;

class Individual extends Model
{
    protected $table = "individuals";

    public function searchForNino(string $nino): array|false
    {
        $nino_hash = Helper::getHash($nino);

        return $this->findUserBy('nino_hash', $nino_hash);
    }

    // used by Home
    public function getAccessToken(int $user_id): ?string
    {

        $pdo = $this->database->getConnection();

        $sql = "select access_token from individuals where user_id = :user_id";

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

        $sql = "update individuals set access_token = :access_token, refresh_token = :refresh_token where user_id = :user_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":access_token", $access_token, PDO::PARAM_STR);
        $stmt->bindValue(":refresh_token", $refresh_token, PDO::PARAM_STR);
        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
