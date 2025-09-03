<?php


declare(strict_types=1);

namespace App\Models;

use Framework\Model;
use PDO;

class UserDevice extends Model
{

    protected $table = "user_devices";

    public function findDevice(int $user_id, string $device_id)
    {

        $pdo = $this->database->getConnection();

        $sql = "select * from user_devices where user_id = :user_id and device_id = :device_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindValue(":device_id", $device_id, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }
}
