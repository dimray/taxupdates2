<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Helper;
use Framework\Model;
use PDO;

class ClientAgent extends Model
{

    protected $table = "clients_agents";

    // used in Clients
    public function checkDuplicate(int $client_id, int $agent_firm_id): bool
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT 1 FROM clients_agents WHERE client_id = ? AND agent_firm_id = ?";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([$client_id, $agent_firm_id]);

        return $stmt->fetchColumn() !== false;
    }

    // Used in Clients
    public function countClientsForAgent($agent_firm_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT COUNT(*)
        FROM clients_agents
        WHERE agent_firm_id = :agent_firm_id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([':agent_firm_id' => $agent_firm_id]);

        $count = $stmt->fetchColumn();

        return $count;
    }

    // used in Clients
    public function findClientsForAgent($agent_id, $limit, $offset)
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT c_a.client_id, c_a.client_name, c_a.authorisation, c.nino
                FROM clients_agents as c_a
                JOIN clients as c ON c_a.client_id = c.id
                WHERE c_a.agent_firm_id = :agent_firm_id
                ORDER BY c_a.client_name ASC
                LIMIT :limit OFFSET :offset";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':agent_firm_id', $agent_id, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    // used in Clients
    public function findClientByNino(string $nino, int $agent_firm_id): bool|array
    {
        $nino_hash = Helper::getHash($nino);

        $pdo = $this->database->getConnection();

        $sql = "SELECT c_a.client_id, c_a.client_name, c_a.authorisation, c.nino
                FROM clients_agents as c_a
                JOIN clients AS c ON c_a.client_id = c.id
                WHERE c_a.agent_firm_id = :agent_firm_id
                AND c.nino_hash = :nino_hash";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(":agent_firm_id", $agent_firm_id, PDO::PARAM_INT);
        $stmt->bindValue(":nino_hash", $nino_hash, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch();
    }



    // used in Clients
    public function updateClientDetails($agent_id, $client_id, array $data)
    {

        $pdo = $this->database->getConnection();

        $sql = "update clients_agents 
        set client_name = :client_name
        where agent_firm_id = :agent_id and client_id = :client_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':client_name', $data['client_name'], PDO::PARAM_STR);
        $stmt->bindValue(':agent_id', $agent_id, PDO::PARAM_INT);
        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);

        $stmt->execute();
    }

    // used in AgentAuthorisation
    public function updateAuthorisation(?string $agent_type, int $agent_id, int $client_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "update clients_agents 
        set authorisation = :authorisation
        where agent_firm_id = :agent_id and client_id = :client_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':authorisation', $agent_type, PDO::PARAM_STR);
        $stmt->bindValue(':agent_id', $agent_id, PDO::PARAM_INT);
        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);

        $stmt->execute();
    }

    // used in Clients
    public function findClientForAgent($agent_id, $client_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT client_name
                FROM clients_agents
                WHERE agent_firm_id = :agent_id
                AND client_id = :client_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':agent_id', $agent_id, PDO::PARAM_INT);
        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch();

        return $result ?: false;
    }

    // used in Clients
    public function deleteClientForAgent($agent_firm_id, $client_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "DELETE FROM clients_agents 
                WHERE agent_firm_id = :agent_firm_id AND client_id = :client_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':agent_firm_id', $agent_firm_id, PDO::PARAM_INT);
        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // used in Clients
    public function countClientAgentLinks($client_id)
    {
        $pdo = $this->database->getConnection();

        $sql = "SELECT COUNT(*) 
            FROM clients_agents 
            WHERE client_id = :client_id";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':client_id', $client_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
