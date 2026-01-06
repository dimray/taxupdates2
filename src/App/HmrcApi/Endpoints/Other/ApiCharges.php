<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints\Other;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiCharges extends ApiCalls
{
    public function retrievePensionCharges(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/charges/pensions/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
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

    public function createAndAmendPensionCharges(string $nino, string $tax_year, array $pension_charges): array
    {
        $url = $this->test_url . "/individuals/charges/pensions/{$nino}/{$tax_year}";

        $payload = json_encode($pension_charges);

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 204) {
            return [
                'type' => 'success'
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function deletePensionCharges(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/charges/pensions/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
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

    public function retrieveHighIncomeChildBenefitChargeSubmission(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/charges/high-income-child-benefit/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
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

    public function createOrAmendHighIncomeChildBenefitChargeSubmission(string $nino, string $tax_year, array $pension_charges): array
    {
        $url = $this->test_url . "/individuals/charges/high-income-child-benefit/{$nino}/{$tax_year}";

        $payload = json_encode($pension_charges);

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
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

        if ($response_code === 204) {
            return [
                'type' => 'success'
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function deleteHighIncomeChildBenefitChargeSubmission(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/charges/high-income-child-benefit/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
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
