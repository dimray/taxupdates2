<?php

declare(strict_types=1);

namespace App\Models;

use Framework\Model;

class Client extends Model
{

    protected $table = "clients";

    public function deleteOrphanedClients()
    {
        $pdo = $this->database->getConnection();

        $sql = "DELETE FROM clients
        WHERE NOT EXISTS (
            SELECT 1 FROM clients_agents WHERE clients.id = clients_agents.client_id
        )";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute();
    }
}
