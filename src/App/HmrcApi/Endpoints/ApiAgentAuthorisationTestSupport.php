<?php

declare(strict_types=1);

namespace App\HmrcApi\Endpoints;

use App\HmrcApi\ApiCalls;

class ApiAgentAuthorisationTestSupport extends ApiCalls
{
    public function accept(string $invitation_id)
    {

        $url = $this->base_url .  "/agent-authorisation-test-support/invitations/{$invitation_id}";

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json",
            "Content-Length:0"
        ];

        $response_array = $this->sendPutRequest($url, "", $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        var_dump($response_array);
        exit;
    }

    public function reject(string $invitation_id)
    {
        $url = $this->base_url . "/agent-authorisation-test-support/invitations/{id}";

        $headers = [
            "Accept: application/vnd.hmrc.1.0+json"
        ];

        $response_array = $this->sendDeleteRequest($url, $headers);

        $response_code = $response_array['response_code'];
        $response = $response_array['response'];
        $response_headers = $response_array['headers'];

        var_dump($response_array);
        exit;
    }
}
