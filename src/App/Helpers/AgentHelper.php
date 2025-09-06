<?php

declare(strict_types=1);

namespace App\Helpers;

class AgentHelper
{

    public static function setClientSession(array $data): bool
    {
        $client_id = (int) $data['client_id'] ?? '';
        $nino = $data['nino'] ?? '';
        $client_name = $data['client_name'] ?? '';

        if (empty($nino) || empty($client_id) || empty($client_name)) {
            return false;
        }

        $_SESSION['client']['nino'] = $nino;
        $_SESSION['client']['name'] = $client_name;
        $_SESSION['client']['id'] = $client_id;

        return true;
    }


    public static function isSupportingAgent(): bool
    {
        $supporting_agent = false;

        if (isset($_SESSION['agent_type']) && $_SESSION['agent_type'] === "supporting") {
            $supporting_agent = true;
        }

        return $supporting_agent;
    }
}
