<?php

declare(strict_types=1);

namespace App\HmrcApi;

use App\HmrcApi\ApiCalls;

class ApiAuthenticate extends ApiCalls
{

    public function getAuthorisationUrl(): string
    {
        return $this->base_url . "/oauth/authorize";
    }

    public function getQueryParams(string $scope, string $redirect_uri, string $state): string
    {
        $query_params = http_build_query([
            'response_type' => 'code',
            'client_id' => $this->client_id,
            'scope' => $scope,
            'redirect_uri' => $redirect_uri,
            'state' => $state
        ]);

        return $query_params;
    }

    public function getAccessToken($authorisation_code, $redirect_uri)
    {
        $query_data = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $redirect_uri,
            'code' => $authorisation_code
        ];

        return $this->getToken($query_data);
    }
}
