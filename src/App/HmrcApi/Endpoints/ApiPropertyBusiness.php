<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\Helpers\Helper;
use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;
use App\HmrcApi\ApiFraudPreventionHeaders;
use App\HmrcApi\ApiTestFraudPreventionHeaders;
use App\HmrcApi\ApiTokenStorage;
use App\Flash;

class ApiPropertyBusiness extends ApiCalls
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

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('PropertyBusiness');
        Helper::logFeedback("PropertyBusiness", $feedback);
        // FRAUD PREVENTION HEADERS

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
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        // var_dump($payload);
        // exit;

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('PropertyBusiness');
        Helper::logFeedback("PropertyBusiness", $feedback);
        // FRAUD PREVENTION HEADERS

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

    public function createAndAmendAPropertyBusinessAnnualSubmission(string $location, string $nino, string $business_id, string $tax_year, array $annual_submission): array
    {

        $url = $this->base_url . "/individuals/business/property/{$location}/{$nino}/{$business_id}/annual/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode($annual_submission);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('PropertyBusiness');
        Helper::logFeedback("PropertyBusiness", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_headers = $response_array['headers'];
        $response_code = $response_array['response_code'];
        $response = $response_array['response'];

        if ($response_code === 204 || $response_code === 200) {

            return [
                'type' => 'success',
                'submission_ref' => $response_headers['x-correlationid'] ?? ""
            ];
        } else {

            Flash::addMessage("The Annual Submission has not been submitted", Flash::WARNING);
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function retrieveAPropertyBusinessAnnualSubmission(string $location, string $nino, string $business_id, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/business/property/{$location}/{$nino}/{$business_id}/annual/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: UK_PROPERTY'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('PropertyBusiness');
        Helper::logFeedback("PropertyBusiness", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success',
                'submission' => $response ?? []
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function deleteAPropertyBusinessAnnualSubmission(string $nino, string $business_id, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/business/property/{$nino}/{$business_id}/annual/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $access_token
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendDeleteRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('PropertyBusiness');
        Helper::logFeedback("PropertyBusiness", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 204) {
            return ['type' => 'success'];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function createForeignPropertyDetails(string $nino, string $business_id, string $tax_year, array $property_data)
    {
        $url = $this->base_url . "/individuals/business/property/foreign/{$nino}/{$business_id}/details/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode($property_data);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_headers = $response_array['headers'];
        $response_code = $response_array['response_code'];
        $response = $response_array['response'];

        if ($response_code === 204 || $response_code === 200) {

            return [
                'type' => 'success',
                'property_id' => $response['propertyId'] ?? ''
            ];
        } else {

            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function retrieveForeignPropertyDetails(string $nino, string $business_id, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/business/property/foreign/{$nino}/{$business_id}/details/{$tax_year}";

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $_SESSION['access_token']
        ];

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
                'foreign_property_details' => $response['foreignPropertyDetails'] ?? []
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function updateForeignPropertyDetails(string $nino, string $property_id, string $tax_year, array $property_data): array
    {

        $url = $this->base_url . "/individuals/business/property/foreign/{$nino}/details/{$property_id}/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode($property_data);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer " . $_SESSION['access_token'],
            "Content-Type: application/json"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        $response_headers = $response_array['headers'];
        $response_code = $response_array['response_code'];
        $response = $response_array['response'];

        if ($response_code === 204 || $response_code === 200) {

            return [
                'type' => 'success'
            ];
        } else {

            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
