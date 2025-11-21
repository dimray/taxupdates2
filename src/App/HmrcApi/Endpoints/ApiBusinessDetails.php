<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\Helpers\Helper;
use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;
use App\HmrcApi\ApiFraudPreventionHeaders;
use App\HmrcApi\ApiTestFraudPreventionHeaders;
use App\HmrcApi\ApiTokenStorage;

class ApiBusinessDetails extends ApiCalls
{
    // FRAUD PREVENTION HEADERS
    public function __construct(
        ApiTokenStorage $tokenStorage,
        ApiFraudPreventionHeaders $apiFraudPreventionHeaders,
        private ApiTestFraudPreventionHeaders $testHeaders
    ) {
        parent::__construct($tokenStorage, $apiFraudPreventionHeaders);
    }
    // FRAUD PREVENTION HEADERS

    public function listAllBusinesses(string $nino): array
    {
        $access_token = $_SESSION['access_token'];

        $url = $this->base_url . "/individuals/business/details/{$nino}/list";

        $headers = [
            "Accept: application/vnd.hmrc.2.0+json",
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
        $feedback = $this->testHeaders->getFeedback('BusinessDetails');
        Helper::logFeedback("BusinessDetails", $feedback);
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
            "Accept: application/vnd.hmrc.2.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [

            "Gov-Test-Scenario: {$test_headers}"
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);
        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('BusinessDetails');
        Helper::logFeedback("BusinessDetails", $feedback);
        // FRAUD PREVENTION HEADERS

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
            "Accept: application/vnd.hmrc.2.0+json",
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
        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('BusinessDetails');
        Helper::logFeedback("BusinessDetails", $feedback);
        // FRAUD PREVENTION HEADERS

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

    public function retrieveAccountingType(string $nino, string $business_id, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/business/details/{$nino}/{$business_id}/{$tax_year}/accounting-type";

        $access_token = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.2.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);
        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('BusinessDetails');
        Helper::logFeedback("BusinessDetails", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'accounting_type' => $response
            ];
        } else {

            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function updateAcccountingType(string $nino, string $business_id, string $tax_year, string $accounting_type): array
    {

        $url = $this->base_url . "/individuals/business/details/{$nino}/{$business_id}/{$tax_year}/accounting-type";

        $access_token = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.2.0+json",
            "Authorization: Bearer {$access_token}",
            "Content-Type: application/json"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $payload = json_encode([
            "accountingType" => $accounting_type
        ]);

        $response_array = $this->sendPutRequest($url, $payload, $headers);
        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('BusinessDetails');
        Helper::logFeedback("BusinessDetails", $feedback);
        // FRAUD PREVENTION HEADERS

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

    public function retrievePeriodsOfAccount(string $nino, string $business_id, string $tax_year): array
    {

        $url = $this->base_url . "/individuals/business/details/{$nino}/{$business_id}/{$tax_year}/periods-of-account";

        $access_token = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.2.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);
        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('BusinessDetails');
        Helper::logFeedback("BusinessDetails", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success',
                'periods' => $response
            ];
        } else {

            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
