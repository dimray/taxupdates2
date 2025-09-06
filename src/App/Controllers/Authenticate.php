<?php

declare(strict_types=1);

namespace App\Controllers;

use App\HmrcApi\ApiAuthenticate;
use Framework\Controller;
use App\Flash;

class Authenticate extends Controller
{
    public function __construct(private ApiAuthenticate $apiAuthenticate) {}

    public function new()
    {
        $heading = "Authenticate With HMRC";

        $user_role = $_SESSION['user_role'];

        $client_name = $_SESSION['client']['name'] ?? null;

        $hide_tax_year = true;

        $hide_menu = true;

        return $this->view("Authenticate/new.php", compact("heading", "hide_tax_year", "hide_menu", "user_role", "client_name"));
    }

    public function goToHmrc()
    {
        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        $role = $_SESSION['user_role'];


        if ($role === "individual") {
            $scope = "write:self-assessment read:self-assessment";
        }

        // Add read and write self-assessment scopes to agent if a client nino is set
        if ($role === "agent") {
            $scope = "read:sent-invitations read:check-relationship write:sent-invitations write:cancel-invitations";

            if (isset($_SESSION['client']['nino'])) {
                $scope .= " " . "read:self-assessment write:self-assessment";
            }
        }

        $url = $this->apiAuthenticate->getAuthorisationUrl();

        $redirect_uri = $_ENV['HMRC_REDIRECT_URI'];

        $query_string = $this->apiAuthenticate->getQueryParams($scope, $redirect_uri, $state);

        return $this->redirect($url . "?" . $query_string);
    }

    // this is where hmrc redirect to
    public function getAccessToken()
    {
        if (!isset($_SESSION['oauth_state'])) {
            Flash::addMessage("Unable to authenticate. Please try again");
            return $this->redirect("/authenticate/new");
        }

        $state = $this->request->get['state'] ?? null;

        if ($state === null || $state !== $_SESSION['oauth_state']) {
            Flash::addMessage("Unable to authenticate. Please try again");
            return $this->redirect("/authenticate/new");
        }

        unset($_SESSION['oauth_state']);

        $authorisation_code = $this->request->get['code'] ?? null;

        if ($authorisation_code === null) {
            Flash::addMessage("Unable to authenticate. Please try again");
            return $this->redirect("/authenticate/new");
        }

        $redirect_uri = $_ENV['HMRC_REDIRECT_URI'];

        $token_data = $this->apiAuthenticate->getAccessToken($authorisation_code, $redirect_uri);

        if ($token_data) {
            $access_token = $token_data['access_token'];
            $_SESSION['access_token'] = $access_token;
        } else {
            Flash::addMessage("Unable to retrieve and save access token from HMRC. Please try again.");
            return $this->redirect("/authenticate/new");
        }

        $role = $_SESSION['user_role'];

        if ($role === "agent") {
            return $this->redirect("/clients/show-clients");
        } else {
            return $this->redirect("/business-details/list-all-businesses");
        }
    }
}
