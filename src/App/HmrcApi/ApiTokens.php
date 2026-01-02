<?php

declare(strict_types=1);

namespace App\HmrcApi;

class ApiTokens
{
    public string $base_url;
    public string $test_url;
    protected string $client_id;
    protected string $client_secret;

    public function __construct(protected ApiTokenStorage $tokenStorage)
    {
        $this->base_url = $_ENV['HMRC_API_BASE_URL'];
        $this->test_url = $_ENV['HMRC_API_TEST_URL'];
        $this->client_id = $_ENV['HMRC_CLIENT_ID'];
        $this->client_secret = $_ENV['HMRC_CLIENT_SECRET'];
    }

    public function getToken(array $query_data): ?array
    {
        $url = $this->base_url . "/oauth/token";

        $headers = ['Content-Type: application/x-www-form-urlencoded'];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($query_data)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return null;
        }

        curl_close($ch);

        $response_data = json_decode($response, true);

        if (!is_array($response_data) || !isset($response_data['access_token'])) {
            return null;
        }

        if (! $this->tokenStorage->saveTokens($response_data)) {
            return null;
        }

        return $response_data;
    }

    public function refreshToken(): bool
    {
        $refresh_token = $this->tokenStorage->retrieveSavedRefreshToken();

        if (!$refresh_token) {
            return false;
        }

        $url = $this->base_url . "/oauth/token";

        $query_data  = http_build_query([
            'grant_type' => 'refresh_token',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'refresh_token' => $refresh_token
        ]);

        $headers = ['Content-Type: application/x-www-form-urlencoded'];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $query_data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers
        ]);

        $response = curl_exec($ch);

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            // Optional: Log the error
            error_log("cURL error during token refresh: " . curl_error($ch));
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $data = json_decode($response, true);

        // returns code 400 if refresh token is no longer valid
        if ($http_code === 400) {
            // re-authenticate if can't refresh token
            return false;
        }

        if ($http_code !== 200 || !isset($data['access_token'])) {

            return false;
        }

        if ($this->tokenStorage->saveTokens($data)) {

            return true;
        };

        return false;
    }
}
