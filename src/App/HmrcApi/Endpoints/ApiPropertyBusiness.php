<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;
use App\Flash;

class ApiPropertyBusiness extends ApiCalls
{
    public function retrieveAPropertyCumulativePeriodSummary(string $location, string $nino, string $business_id, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/business/property/{$location}/{$nino}/{$business_id}/cumulative/{$tax_year}";

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $_SESSION['access_token']
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: NOT_FOUND'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success',
                'summary' => $response ?? []
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function createAndAmendAPropertyCumulativePeriodSummary(string $location, string $nino, string $business_id, string $tax_year, array $cumulative_data): array
    {

        $url = $this->base_url . "/individuals/business/property/{$location}/{$nino}/{$business_id}/cumulative/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode($cumulative_data);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $_SESSION['access_token'],
            "Content-Type: application/json"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        $response_headers = $response_array['headers'];
        $response_code = $response_array['response_code'];
        $response = $response_array['response'];

        if ($response_code === 204 || $response_code === 200) {

            return [
                'type' => 'success',
                'submission_ref' => $response_headers['x-correlationid'] ?? ""
            ];
        } else {
            Flash::addMessage("The Cumulative Summary has not been submitted to HMRC", Flash::WARNING);
            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
