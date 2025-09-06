<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiBusinessDetails extends ApiCalls
{

    public function listAllBusinesses(string $nino): array
    {
        $access_token = $_SESSION['access_token'];

        $url = $this->base_url . "/individuals/business/details/{$nino}/list";

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: NOT_FOUND'
            'Gov-Test-Scenario: BUSINESS_AND_PROPERTY'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        // $feedback = $this->testHeaders->getFeedback();
        // var_dump($feedback);
        // exit;
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'businesses' => $response['listOfBusinesses']
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function retrieveBusinessDetails(string $nino, string $business_id, string $test_headers = ""): array
    {
        $url = $this->base_url . "/individuals/business/details/{$nino}/{$business_id}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [

            "Gov-Test-Scenario: {$test_headers}"
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'business' => $response
            ];
        } else {

            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function createAmendPeriodTypeForBusiness($nino, $business_id, $tax_year, $new_period): array
    {

        $url = $this->base_url . "/individuals/business/details/{$nino}/{$business_id}/{$tax_year}";

        $access_token = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $payload = json_encode([
            "quarterlyPeriodType" => $new_period
        ]);

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
}
