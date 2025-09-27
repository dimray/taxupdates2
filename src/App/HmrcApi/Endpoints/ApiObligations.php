<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiObligations extends ApiCalls
{

    public function retrieveIncomeTaxIncomeAndExpenditureObligations(string $nino, string $business_id, string $type_of_business, string $from_date, string $to_date): ?array
    {

        $query_params = [
            "businessId" => $business_id,
            "typeOfBusiness" => $type_of_business,
            "fromDate" => $from_date,
            "toDate" => $to_date
        ];

        $url_start = $this->base_url . "/obligations/details/{$nino}/income-and-expenditure";
        $url = $url_start .  '?' . http_build_query($query_params);

        $access_token  = $this->tokenStorage->retrieveSavedAccessToken();

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers. Open is needed for foreign property
        $test_headers = [
            // 'Gov-Test-Scenario: CUMULATIVE'
            'Gov-Test-Scenario: DYNAMIC'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];


        if ($response_code === 200) {
            return [
                'type' => 'success',
                'obligations' => $response['obligations'][0]['obligationDetails'] ?? []
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function retrieveIncomeTaxFinalDeclarationObligations(string $nino, string $tax_year): array
    {
        $url_start = $this->base_url . "/obligations/details/{$nino}/crystallisation";

        $query_params = ["taxYear" => $tax_year];

        $url = $url_start .  '?' . http_build_query($query_params);

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.3.0+json",
            "Authorization: Bearer " . $access_token
        ];

        // test scenario headers. Open is needed for foreign property
        $test_headers = [
            'Gov-Test-Scenario: DYNAMIC'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'obligations' => $response['obligations'] ?? []
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }
}
