<?php

declare(strict_types=1);

namespace App\Controllers\Endpoints;

use App\HmrcApi\Endpoints\ApiBusinessDetails;
use Framework\Controller;

class BusinessDetails extends Controller
{
    public function __construct(private ApiBusinessDetails $apiBusinessDetails) {}

    public function listAllBusinesses()
    {
        $response = $this->apiBusinessDetails->listAllBusinesses();


        if ($response['type'] === 'redirect') {
            return $this->redirect($response['location']);
        }

        $businesses  = [];

        if ($response['type'] === 'success') {
            $businesses = $response['businesses'] ?? [];
        }

        $heading = "Your Businesses";

        $hide_tax_year = true;

        return $this->view("/Endpoints/BusinessDetails/index.php", compact("heading", "businesses", "hide_tax_year"));
    }
}
