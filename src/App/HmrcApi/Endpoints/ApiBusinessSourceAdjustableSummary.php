<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiBusinessSourceAdjustableSummary extends ApiCalls
{

    public function triggerABusinessSourceAdjustableSummary(string $nino, string $business_id, string $type_of_business, array $accounting_period): array
    {

        $url = $this->base_url . "/individuals/self-assessment/adjustable-summary/{$nino}/trigger";

        $access_token = $_SESSION['access_token'];

        $payload = json_encode([
            "accountingPeriod" => $accounting_period,
            "typeOfBusiness" => $type_of_business,
            "businessId" => $business_id
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, $payload, $headers);



        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'calculation_id' => $response['calculationId'] ?? ""
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function submitAccountingAdjustments(string $nino, string $type_of_business, string $calculation_id, string $tax_year, array $bsas_data): array
    {

        $url = $this->base_url  . "/individuals/self-assessment/adjustable-summary/{$nino}/{$type_of_business}/{$calculation_id}/adjust/{$tax_year}";

        $access_token = $_SESSION['access_token'];

        $payload = json_encode($bsas_data);

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        if ($response_code === 200) {
            return [
                'type' => 'success',
                'submission_id' => $response_headers['x-correlationid'] ?? ""
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function listBusinessSourceAdjustableSummaries(string $nino, string $tax_year, string $business_id): array
    {

        $url_start = $this->base_url . "/individuals/self-assessment/adjustable-summary/{$nino}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $query_params = http_build_query([
            'businessId' => $business_id
        ]);

        $url = $url_start . "?" . $query_params;

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'data' => $response['businessSources'][0]
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response, "list-bsas");
        }
    }

    public function retrieveBusinessSourceAdjustableSummary(string $nino, string $business_type, string $calculation_id, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/self-assessment/adjustable-summary/{$nino}/{$business_type}/{$calculation_id}/{$tax_year}";

        $access_token  = $this->tokenStorage->retrieveSavedAccessToken();

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [];

        if ($this->base_url === "https://test-api.service.hmrc.gov.uk") {

            if ($business_type === "self-employment") {
                $test_headers = [
                    'Gov-Test-Scenario: SELF_EMPLOYMENT_PROFIT'
                    // 'Gov-Test-Scenario: REQUEST_CANNOT_BE_FULFILLED'

                ];
            }

            if ($business_type === "uk-property") {
                $test_headers = [
                    'Gov-Test-Scenario: UK_PROPERTY_PROFIT'
                ];
            }

            if ($business_type === "foreign-property") {
                $test_headers = [
                    'Gov-Test-Scenario: FOREIGN_PROPERTY_PROFIT'
                ];
            }
        }


        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'summary' => $response
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
