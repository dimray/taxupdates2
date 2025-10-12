<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints\Other;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiCisDeductions extends ApiCalls
{

    public function retrieveCisDeductionsForSubcontractor(string $nino, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/deductions/cis/{$nino}/current-position/{$tax_year}/all";

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

        // var_dump($response_array);
        // exit;

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

    public function createCisDeductionsForSubcontractor(string $nino, array $deductions): array
    {
        $url = $this->base_url . "/individuals/deductions/cis/{$nino}/amendments";


        $payload = json_encode($deductions);

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

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'submission_id' => $response['submissionId']
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function amendCisDeductionsForSubcontractor(string $nino, string $submission_id, array $period_data): array
    {
        $url = $this->base_url . "/individuals/deductions/cis/{$nino}/amendments/{$submission_id}";

        $payload = json_encode(['periodData' => $period_data]);

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

    public function deleteCisDeductionsForSubcontractor(string $nino, string $tax_year, string $submission_id): array
    {
        $url = $this->base_url . "/individuals/deductions/cis/{$nino}/amendments/{$submission_id}/{$tax_year}";

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
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
