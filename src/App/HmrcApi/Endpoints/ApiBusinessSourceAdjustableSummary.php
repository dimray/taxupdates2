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
            // 'Gov-Test-Scenario: DYNAMIC'
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

        var_dump($response_array);
        exit;
    }
}
