<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\Helpers\Helper;
use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;
use App\HmrcApi\ApiFraudPreventionHeaders;
use App\HmrcApi\ApiTestFraudPreventionHeaders;
use App\HmrcApi\ApiTokenStorage;

class ApiIndividualCalculations extends ApiCalls
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

    public function triggerASelfAssessmentTaxCalculation(string $nino, string $tax_year, string $calculation_type): array
    {

        $url = $this->base_url . "/individuals/calculations/{$nino}/self-assessment/{$tax_year}/trigger/{$calculation_type}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Length: 0"
        ];

        // test scenario headers
        $test_headers = [
            // 'Gov-Test-Scenario:CALCULATION_IN_PROGRESS'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, "", $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('IndividualCalculations');
        Helper::logFeedback("IndividualCalculations", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 202) {

            return [
                'type' => 'success',
                'calculation_id' => $response['calculationId'] ?? ''
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function retrieveASelfAssessmentTaxCalculation(string $nino, string $tax_year, string $calculation_id): array
    {

        $url = $this->base_url . "/individuals/calculations/{$nino}/self-assessment/{$tax_year}/{$calculation_id}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers
        $test_headers = [
            'Gov-Test-Scenario: UK_PROP_GIFTAID_EXAMPLE'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('IndividualCalculations');
        Helper::logFeedback("IndividualCalculations", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'calculation' => $response ?? []
            ];
        } elseif ($response_code === 404) {
            return [
                'type' => 'redirect',
                'location' => '/individual-calculations/wait-for-calculation'
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response, "individual-calculations", "retrieve-calculation");
        }
    }

    public function listSelfAssessmentTaxCalculations(string $nino, string $tax_year, string $calculation_type): array
    {

        $url_start = $this->base_url . "/individuals/calculations/{$nino}/self-assessment/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        if ($calculation_type !== "") {

            $query_string = http_build_query(['calculationType' => $calculation_type]);
            $url = $url_start . "?" . $query_string;
        } else {

            $url = $url_start;
        }

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer " . $access_token,
        ];

        // test scenario headers
        $test_headers = [];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('IndividualCalculations');
        Helper::logFeedback("IndividualCalculations", $feedback);
        // FRAUD PREVENTION HEADERS

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'calculations' => $response['calculations'] ?? []
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function submitASelfAssessmentFinalDeclaration(string $nino, string $tax_year, string $calculation_id, string $calculation_type): array
    {

        $url = $this->base_url . "/individuals/calculations/{$nino}/self-assessment/{$tax_year}/{$calculation_id}/{$calculation_type}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.7.0+json",
            "Authorization: Bearer " . $access_token
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: NO_INCOME_SUBMISSIONS_EXIST'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, "", $headers);

        // FRAUD PREVENTION HEADERS
        $feedback = $this->testHeaders->getFeedback('IndividualCalculations');
        Helper::logFeedback("IndividualCalculations", $feedback);
        // FRAUD PREVENTION HEADERS


        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 204) {

            return [
                'type' => 'success',
                'submission_id' => $response_headers['x-correlationid'] ?? ""
            ];
        } else {

            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
