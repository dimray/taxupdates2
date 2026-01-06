<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints\Other;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiReliefs extends ApiCalls
{

    // INVESTMENTS

    public function retrieveReliefInvestments(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/investment/{$nino}/{$tax_year}";

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

    public function createAndAmendReliefInvestments(string $nino, string $tax_year, array $investment_reliefs)
    {
        $url = $this->test_url . "/individuals/reliefs/investment/{$nino}/{$tax_year}";

        $payload = json_encode($investment_reliefs);

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

    public function deleteReliefInvestments(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/investment/{$nino}/{$tax_year}";

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

    // OTHER

    public function retrieveOtherReliefs(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/other/{$nino}/{$tax_year}";

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

    public function createAndAmendOtherReliefs(string $nino, string $tax_year, array $other_reliefs): array
    {
        $url = $this->test_url . "/individuals/reliefs/other/{$nino}/{$tax_year}";

        $payload = json_encode($other_reliefs);

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

    public function deleteOtherReliefs(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/other/{$nino}/{$tax_year}";

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

    // FOREIGN

    public function retrieveForeignReliefs(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/foreign/{$nino}/{$tax_year}";

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

    public function createAndAmendForeignReliefs(string $nino, string $tax_year, array $foreign_reliefs): array
    {

        $url = $this->test_url . "/individuals/reliefs/foreign/{$nino}/{$tax_year}";

        $payload = json_encode($foreign_reliefs);

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

    public function deleteForeignReliefs(string $nino, string $tax_year): array
    {

        $url = $this->test_url . "/individuals/reliefs/foreign/{$nino}/{$tax_year}";

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

    // PENSIONS
    public function retrievePensionsReliefs(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/pensions/{$nino}/{$tax_year}";

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

    public function createAndAmendPensionsReliefs(string $nino, string $tax_year, array $pensions_reliefs): array
    {

        $url = $this->test_url . "/individuals/reliefs/pensions/{$nino}/{$tax_year}";

        $payload = json_encode($pensions_reliefs);

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

    public function deletePensionsReliefs(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/pensions/{$nino}/{$tax_year}";

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

    // CHARITABLE GIVING

    public function retrieveCharitableGivingTaxRelief(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/charitable-giving/{$nino}/{$tax_year}";

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

    public function createAndAmendCharitableGivingTaxRelief(string $nino, string $tax_year, array $charitable_giving): array
    {
        $url = $this->test_url . "/individuals/reliefs/charitable-giving/{$nino}/{$tax_year}";

        $payload = json_encode($charitable_giving);

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

    public function deleteCharitableGivingTaxRelief(string $nino, string $tax_year): array
    {
        $url = $this->test_url . "/individuals/reliefs/charitable-giving/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
            // 'Gov-Test-Scenario: DELETE'
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
