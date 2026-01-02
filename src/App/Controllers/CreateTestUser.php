<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\Controller;
use Exception;

class CreateTestUser extends Controller
{

    private string $client_id;
    private string $client_secret;
    private string $redirect_uri;

    public function __construct()
    {

        $this->client_id = $_ENV['HMRC_CLIENT_ID'];
        $this->client_secret = $_ENV['HMRC_CLIENT_SECRET'];
        $this->redirect_uri = $_ENV['HMRC_REDIRECT_URI'];
    }

    protected function sendPostRequest(string $url, string $payload, array $headers)
    {

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
        ]);

        $response = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            'responseCode' => $responseCode,
            'response' => json_decode($response, true)
        ];
    }

    protected function getToken(array $queryData, array $headers)
    {
        $url = "https://test-api.service.hmrc.gov.uk/oauth/token";

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($queryData)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("cURL error: " . curl_error($ch));
        }

        curl_close($ch);

        $responseData = json_decode($response, true);

        if (!isset($responseData['access_token'])) {
            throw new Exception("Failed to retrieve access token: " . json_encode($responseData));
        }

        return $responseData;
    }

    public function create(string $type, array $services)
    {

        // get token

        $queryData = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret
        ];

        $headers = ['Content-Type: application/x-www-form-urlencoded'];

        $responseData = $this->getToken($queryData, $headers);

        $token = [
            'access_token' => $responseData['access_token'],
            'scope' => $responseData['scope'] ?? '',
            'expires_in' => $responseData['expires_in'] ?? 0
        ];

        $accessToken = $token['access_token'];

        // send post request

        $url = "https://test-api.service.hmrc.gov.uk/create-test-user/$type";

        $headers = [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json",
            "Accept: application/json"
        ];

        $data = ["serviceNames" => $services];

        $responseArray = $this->sendPostRequest($url, json_encode($data), $headers);

        return $responseArray;
    }


    public function createIndividual()
    {

        $responseArray = $this->create("individuals", ["mtd-income-tax"]);

        var_dump($responseArray);
        exit;
    }

    public function createAgent()
    {
        $responseArray = $this->create("agents", ["agent-services"]);

        var_dump($responseArray);
        exit;
    }
}
