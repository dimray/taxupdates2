<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiIndividualCalculations extends ApiCalls
{
    public function triggerASelfAssessmentTaxCalculation(string $nino, string $tax_year, string $calculation_type): array
    {

        $url = $this->base_url . "/individuals/calculations/{$nino}/self-assessment/{$tax_year}/trigger/{$calculation_type}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Length: 0"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario:CALCULATION_IN_PROGRESS'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, "", $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 202) {

            return [
                'type' => 'success',
                'calculation_id' => $response['calculationId'] ?? ''
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function retrieveASelfAssessmentTaxCalculation(string $nino, string $tax_year, string $calculation_id): array
    {

        $url = $this->base_url . "/individuals/calculations/{$nino}/self-assessment/{$tax_year}/{$calculation_id}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: UK_PROP_GIFTAID_EXAMPLE'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'calculation' => $response ?? []
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response, "individual-calculations", "retrieve-calculation");
        }
    }
}
