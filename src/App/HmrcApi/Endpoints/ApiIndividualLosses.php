<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiCalls;
use App\HmrcApi\ApiErrors;

class ApiIndividualLosses extends ApiCalls
{

    // ****************BFWD LOSSES**********************************

    // retrieve a loss endpoint not used

    public function createABroughtForwardLoss(string $nino, string $loss_year, string $tax_year, string $loss_type, string $business_id, float $loss_amount): array
    {
        $url = $this->base_url . "/individuals/losses/{$nino}/brought-forward-losses/tax-year/brought-forward-from/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode([
            "taxYearBroughtForwardFrom" => $loss_year,
            "typeOfLoss" => $loss_type,
            "businessId" => $business_id,
            "lossAmount" => $loss_amount
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 201) {

            return [
                'type' => 'success',
                'loss_id' => $response['lossId']
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function listBroughtForwardLosses(string $nino, string $tax_year_brought_forward_from, string $query_string = ""): array
    {
        $url_start = $this->base_url . "/individuals/losses/{$nino}/brought-forward-losses/tax-year/{$tax_year_brought_forward_from}";

        $url = $url_start  . $query_string;

        $access_token = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success',
                'losses' => $response['losses']
            ];
        } elseif ($response['code'] === "MATCHING_RESOURCE_NOT_FOUND") {
            // don't return flash error if no losses, dealt with in view.
            return ['type' => 'error'];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function amendABroughtForwardLossAmount(string $nino, string $loss_id, float $loss_amount, string $tax_year): array
    {
        $url = $this->base_url . "/individuals/losses/{$nino}/brought-forward-losses/{$loss_id}/tax-year/{$tax_year}/change-loss-amount";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode([
            "lossAmount" => $loss_amount
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {

            return [
                'type' => 'success',
                'amended_loss' => $response
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function deleteABroughtForwardLoss(string $nino, string $loss_id): array
    {
        $url = $this->base_url . "/individuals/losses/{$nino}/brought-forward-losses/{$loss_id}/tax-year/{$_SESSION['tax_year']}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendDeleteRequest($url, $headers);

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

    // ****************CURRENT LOSSES***********************************************

    public function createALossClaim(string $nino, string $year_claimed_for, string $type_of_loss, string $type_of_claim, string $business_id): array
    {
        $url = $this->base_url . "/individuals/losses/{$nino}/loss-claims";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode([
            "taxYearClaimedFor" => $year_claimed_for,
            "typeOfLoss" => $type_of_loss,
            "typeOfClaim" => $type_of_claim,
            "businessId" => $business_id
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 201) {

            return [
                'type' => 'success',
                'claim_id' => $response['claimId'] ?? ''
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function listLossClaims(string $nino, string $year_claimed_for, string $query_string = ""): array
    {
        $url_start = $this->base_url . "/individuals/losses/{$nino}/loss-claims/tax-year/{$year_claimed_for}";

        $url = $url_start . $query_string;

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success',
                'claims' => $response['claims'] ?? []
            ];
        } elseif ($response_code === 404 && $response['message'] === "Matching resource not found") {
            // dealt with in view if no losses, don't need error message
            return ['type' => 'error'];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function deleteALossClaim(string $nino, string $tax_year, string $claim_id): array
    {
        $url = $this->base_url . "/individuals/losses/{$nino}/loss-claims/{$claim_id}/tax-year/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendDeleteRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 204) {
            return ['type' => 'success'];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function amendALossClaimType(string $nino, string $tax_year, string $claim_id, string $type_of_claim): array
    {

        $url = $this->base_url . "/individuals/losses/{$nino}/loss-claims/{$claim_id}/tax-year/{$tax_year}/change-type-of-claim";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode([
            "typeOfClaim" => $type_of_claim
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'] ?? 0;
        $response = $response_array['response'] ?? [];
        $response_headers = $response_array['headers'] ?? [];

        if ($response_code === 200) {
            return [
                'type' => 'success'
            ];
        } else {
            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function amendLossClaimsOrder(string $nino, string $tax_year, string $type_of_claim, array $claims): array
    {

        $url = $this->base_url . "/individuals/losses/{$nino}/loss-claims/order/{$tax_year}";

        $access_token  = $_SESSION['access_token'];

        $payload = json_encode([
            "typeOfClaim" => $type_of_claim,
            "listOfLossClaims" => $claims
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.6.0+json",
            "Content-Type: application/json",
            "Authorization: Bearer {$access_token}"
        ];

        $test_headers = [
            'Gov-Test-Scenario: STATEFUL'
        ];

        $headers = array_merge($headers, $test_headers);

        $response_array = $this->sendPutRequest($url, $payload, $headers);


        $response = $response_array['response'];
        $response_code = $response_array['response_code'];
        $response_headers = $response_array['headers'];

        if ($response_code === 200) {
            return ['type' => 'success'];
        }

        return ['type' => 'error'];
    }
}
