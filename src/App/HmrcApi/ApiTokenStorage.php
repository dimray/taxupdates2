<?php

declare(strict_types=1);

namespace App\HmrcApi;

use App\Models\Agent;
use App\Models\Individual;
use Framework\Encryption;

class ApiTokenStorage
{

    public function __construct(private Individual $individual, private Agent $agent) {}

    public function saveTokens($data)
    {
        $encrypted_access_token = Encryption::encrypt($data['access_token']);
        $encrypted_refresh_token = Encryption::encrypt($data['refresh_token']);

        if ($_SESSION['user_role'] === "individual") {
            return $this->individual->saveTokens($_SESSION['user_id'], $encrypted_access_token, $encrypted_refresh_token);
        } elseif ($_SESSION['user_role'] === "agent") {
            return $this->agent->saveTokens($_SESSION['user_id'], $encrypted_access_token, $encrypted_refresh_token);
        } else {
            return null;
        }
    }

    public function retrieveSavedAccessToken(?string $encrypted_access_token = null)
    {
        if (!$encrypted_access_token) {

            if ($_SESSION['user_role'] === "individual") {
                $encrypted_access_token = $this->individual->getFromDatabase("access_token", $_SESSION['user_id']);
            } elseif ($_SESSION['user_role'] === "agent") {
                $encrypted_access_token = $this->agent->getFromDatabase("access_token", $_SESSION['user_id']);
            }

            if (!$encrypted_access_token) {
                return null;
            }
        }

        return Encryption::decrypt($encrypted_access_token);
    }

    public function retrieveSavedRefreshToken()
    {
        if ($_SESSION['user_role'] === "individual") {
            $encrypted_refresh_token = $this->individual->getFromDatabase("refresh_token", $_SESSION['user_id']);
        } elseif ($_SESSION['user_role'] === "agent") {
            $encrypted_refresh_token = $this->agent->getFromDatabase("refresh_token", $_SESSION['user_id']);
        }

        if (!$encrypted_refresh_token) {
            return false;
        }

        $refresh_token = Encryption::decrypt($encrypted_refresh_token);

        return $refresh_token;
    }
}
