<?php

declare(strict_types=1);

namespace App\Helpers;

use App\HmrcApi\Endpoints\ApiBusinessDetails;

class PeriodsOfAccountHelper
{
    public function __construct(private ApiBusinessDetails $apiBusinessDetails) {}

    public function getPeriodsOfAccount(string $nino, string $business_id): string
    {
        if (isset($_SESSION[$nino]['cache']['period_type'])) {
            return $_SESSION[$nino]['cache']['period_type'];
        }


        $response = $this->apiBusinessDetails->retrieveBusinessDetails($nino, $business_id);

        $periods = $response['response']['quarterlyTypeChoice']['quarterlyPeriodType'] ?? '';
        $_SESSION[$nino]['cache']['period_type'] = $periods;
        return $periods;
    }
}
