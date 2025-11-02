<?php

declare(strict_types=1);

namespace App\HmrcApi;

class ApiTestFraudPreventionHeaders extends ApiCalls
{

    public function __construct(
        ApiTokenStorage $tokenStorage,
        ApiFraudPreventionHeaders $apiFraudPreventionHeaders
    ) {
        parent::__construct($tokenStorage, $apiFraudPreventionHeaders);
    }

    public function getFeedback(string $controller_name)
    {
        $api_map = [
            'BusinessDetails' => 'business-details-mtd',
            'BSAS' => 'business-source-adjustable-summary-mtd',
            'IndividualCalculations' => 'individual-calculations-mtd',
            'IndividualLosses' => 'individual-losses-mtd',
            'Obligations' => 'obligations-mtd',
            'PropertyBusiness' => 'property-business-mtd',
            'SelfEmploymentBusiness' => 'self-employment-business-mtd'
        ];

        $api = $api_map[$controller_name] ?? null;

        if (!$api) {
            throw new \Exception("No API mapping found for controller {$controller_name}");
        }

        $query_string = "connectionMethod=WEB_APP_VIA_SERVER";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer " . $access_token
        ];

        $url = "https://test-api.service.hmrc.gov.uk/test/fraud-prevention-headers/{$api}/validation-feedback?" . $query_string;

        $response_array = $this->sendGetRequest($url, $headers);

        return $response_array;
    }
}
