<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;

class Home extends Controller
{

    public function index()
    {
        if (isset($_SESSION['user_id'])) {

            $role = $_SESSION['user_role'] ?? "";

            if ($role === "individual") {

                if (!empty($_SESSION['access_token'])) {

                    return $this->redirect("/business-details/list-all-businesses");
                } else {
                    return $this->redirect("/authenticate/new");
                }
            }

            if ($role === "agent") {


                if (!empty($_SESSION['access_token'])) {

                    return $this->redirect("/clients/show-clients");
                } else {
                    return $this->redirect("/authenticate/new");
                }
            }

            // if somehow they don't have a role, unlog them:
            return $this->redirect("/session/destroy");
        } else {
            // if not logged in

            return $this->view("Home/index.php", ["heading" => "Home Page"]);
        }
    }
}
