<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints\Other;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiSelfAssessmentAssist extends ApiCalls
{

    public function produceAHmrcSelfAssessmentAssistReport(string $nino, string $tax_year, string $calculation_id): array
    {
        $url = $this->base_url . "/individuals/self-assessment/assist/reports/{$nino}/{$tax_year}/{$calculation_id}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, "", $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success',
                'response' => $response
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }


    public function acknowledgeAHmrcSelfAssessmentAssistReport(string $nino, string $report_id, string $correlation_id): array
    {

        $url = $this->base_url . "/individuals/self-assessment/assist/reports/acknowledge/{$nino}/{$report_id}/{$correlation_id}";


        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            // 'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, "", $headers);

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
