<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints\Other;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiInsuranceIncome extends ApiCalls
{

    public function retrieveInsurancePoliciesIncome(string $nino, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/insurance-policies-income/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.2.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'response' => $response
            ];
        } elseif ($response_code === 404 && $response['code'] === "MATCHING_RESOURCE_NOT_FOUND") {

            return [
                'type' => 'error'
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function createAndAmendInsurancePoliciesIncome(string $nino, string $tax_year, array $insurance_income): array
    {
        $url = $this->base_url . "/individuals/insurance-policies-income/{$nino}/{$tax_year}";

        $payload = json_encode($insurance_income);

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.2.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success'
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function deleteInsurancePoliciesIncome(string $nino, string $tax_year)
    {
        $url = $this->base_url . "/individuals/insurance-policies-income/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.2.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendDeleteRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 204) {
            return [
                'type' => 'success'
            ];
        } elseif ($response_code === 404 && $response['code'] === "MATCHING_RESOURCE_NOT_FOUND") {

            return [
                'type' => 'error'
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
