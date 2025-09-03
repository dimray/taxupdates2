<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use App\Helpers\Helper;

use PDO;


class AgentFirm extends Model
{

    protected $table = "agent_firms";

    public function getFirmId(string $arn_hash)
    {

        $pdo = $this->database->getConnection();

        $sql = "select id from agent_firms where arn_hash = :arn_hash";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":arn_hash", $arn_hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    public function searchForArn(string $arn): array|false
    {
        $arn_hash = Helper::getHash($arn);

        return $this->findUserBy('arn_hash', $arn_hash);
    }
}
