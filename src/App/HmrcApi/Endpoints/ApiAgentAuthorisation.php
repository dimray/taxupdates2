<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiErrors;
use App\HmrcApi\ApiCalls;



class ApiAgentAuthorisation extends ApiCalls
{
    public function createNewAuthorisation(string $arn, string $nino, string $postcode, string $agent_type): array
    {
        $access_token  = $_SESSION['access_token'];

        $url = $this->base_url . "/agents/{$arn}/invitations";

        $payload = json_encode([
            "service" => ["MTD-IT"],
            "clientType" => "personal",
            "clientIdType" => "ni",
            "clientId" => $nino,
            "knownFact" => $postcode,
            "agentType" => $agent_type
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        $response_array = $this->sendPostRequest($url, $payload, $headers);



        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        if ($response_code === 204) {

            return [
                'type' => 'success',
                'location' => $response_headers['location'] ?? ""
            ];
        } else {

            return ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function getAllAuthorisationRequests(string $arn): array
    {
        $url = $this->base_url . "/agents/{$arn}/invitations";

        $access_token  = $this->tokenStorage->retrieveSavedAccessToken();

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer " . $access_token,

        ];

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];



        if ($response_code === 200) {
            return [
                'type' => 'success',
                'code' => 200,
                'requests' => $response
            ];
        } elseif ($response_code === 204) {

            return [
                'type' => 'success',
                'code' => 204
            ];
        } else {
            return  ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function getAnInvitationById(string $arn, string $invitation_id): array
    {
        $access_token  = $_SESSION['access_token'];

        $url = $this->base_url .  "/agents/{$arn}/invitations/{$invitation_id}";

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer " . $access_token,
        ];

        $response_array = $this->sendGetRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        if ($response_code ===  200) {
            return [
                'type' => 'success',
                'url' => $response['clientActionUrl'] ?? ""
            ];
        } else {
            return  ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function cancelAnInvitationById(string $arn, string $invitation_id): array
    {
        $access_token  = $_SESSION['access_token'];

        $url = $this->base_url . "/agents/{$arn}/invitations/{$invitation_id}";

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        $response_array = $this->sendDeleteRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        if ($response_code === 204) {
            return [
                'type' => 'success'
            ];
        } else {
            return  ApiErrors::dealWithError($response_code, $response);
        }
    }

    public function getStatusOfRelationship(string $arn, string $nino, string $postcode, string $agent_type): array
    {
        $access_token  = $_SESSION['access_token'];

        $url = $this->base_url . "/agents/{$arn}/relationships";

        $payload = json_encode([
            "service" => ["MTD-IT"],
            "clientIdType" => "ni",
            "clientId" => $nino,
            "knownFact" => $postcode,
            "agentType" => $agent_type
        ]);

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Authorization: Bearer " . $access_token,
            "Content-Type: application/json"
        ];

        $response_array = $this->sendPostRequest($url, $payload, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        if ($response_code === 204) {
            return ['type' => 'success'];
        } else {

            return  ApiErrors::dealWithError($response_code, $response, "agentAuthorisation", "getStatusOfRelationship");
        }
    }
}
