<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Encryption;
use App\Helpers\TaxYearHelper;
use App\Models\Agent;
use App\Models\Individual;
use App\Models\User;

class UserLogin
{
    public function __construct(private Individual $individual, private Agent $agent, private User $user) {}

    public function loginUser(array $user): void
    {

        session_regenerate_id(true);

        $this->setUserSession($user);
    }

    private function setUserSession(array $user)
    {
        // set session
        $_SESSION['user_id'] = (int) $user['id'];

        $_SESSION['email'] = Encryption::decrypt($user['email']);

        $_SESSION['user_name'] = Encryption::decrypt($user['name']);

        $_SESSION['user_role'] = $user['user_role'];

        $_SESSION['login_time'] = time();

        $_SESSION['tax_year'] = TaxYearHelper::getCurrentTaxYear();

        if ($user['user_role'] === "individual") {
            $individual = $this->individual->find((int)$user['id']);

            $nino = $individual['nino'];
            $_SESSION['nino'] = Encryption::decrypt($nino);

            $encrypted_access_token = $this->individual->getFromDatabase("access_token", $_SESSION['user_id']);
        }

        if ($user['user_role'] === "agent") {

            $arn = $this->user->findArnFromUserId((int)$user['id']);

            $_SESSION['arn'] = Encryption::decrypt($arn);

            $_SESSION['firm_id'] = $this->agent->getFirmId((int) $user['id']);

            $encrypted_access_token = $this->agent->getFromDatabase("access_token", $_SESSION['user_id']);
        }

        if (!empty($encrypted_access_token)) {

            $access_token = Encryption::decrypt($encrypted_access_token);

            $_SESSION['access_token'] = $access_token;
        }
    }
}
