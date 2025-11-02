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

class ApiSelfEmployment extends ApiCalls
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

    public function retrieveASelfEmploymentCumulativePeriodSummary(string $nino, string $business_id, string $tax_year): array
    {
        $url = $this->base_url .  "/individuals/business/self-employment/{$nino}/{$business_id}/cumulative/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.5.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: CONSOLIDATED_EXPENSES'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('SelfEmploymentBusiness');
        Helper::logFeedback("SelfEmploymentBusiness", $feedback);
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

    public function createAndAmendASelfEmploymentCumulativePeriodSummary(string $nino, string $business_id, string $tax_year, array $cumulative_upload_data): array
    {

        $url = $this->base_url . "/individuals/business/self-employment/{$nino}/{$business_id}/cumulative/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.5.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $payload = json_encode($cumulative_upload_data);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('SelfEmploymentBusiness');
        Helper::logFeedback("SelfEmploymentBusiness", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];


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

    public function createAndAmendSelfEmploymentAnnualSubmission(string $nino, string $business_id, string $tax_year, array $annual_submission): array
    {

        $url = $this->base_url . "/individuals/business/self-employment/{$nino}/{$business_id}/annual/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode($annual_submission);

        $headers = [
            "Accept: application/vnd.hmrc.5.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        // test scenario headers
        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('SelfEmploymentBusiness');
        Helper::logFeedback("SelfEmploymentBusiness", $feedback);
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

    public function retrieveASelfEmploymentAnnualSubmission(string $nino, string $business_id, string $tax_year): array
    {

        $url = $this->base_url . "/individuals/business/self-employment/{$nino}/{$business_id}/annual/{$tax_year}";


        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.5.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('SelfEmploymentBusiness');
        Helper::logFeedback("SelfEmploymentBusiness", $feedback);
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

    public function deleteASelfEmploymentAnnualSubmission(string $nino, string $business_id, string $tax_year): array
    {

        $url = $this->base_url . "/individuals/business/self-employment/{$nino}/{$business_id}/annual/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.5.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendDeleteRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('SelfEmploymentBusiness');
        Helper::logFeedback("SelfEmploymentBusiness", $feedback);
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
}
