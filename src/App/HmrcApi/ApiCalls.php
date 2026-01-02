<?php

declare(strict_types=1);

namespace App\HmrcApi;

use Exception;

class  ApiCalls extends ApiTokens
{
    public function __construct(
        protected ApiTokenStorage $tokenStorage,
        protected ApiFraudPreventionHeaders $apiFraudPreventionHeaders
    ) {
        parent::__construct($tokenStorage);
    }

    private function performRequest(string $method, string $url, array $headers, string $payload = '',  int $retry_count = 0): ?array
    {
        $ch = curl_init();

        // to get response headers
        $captured_headers = [];

        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            // to get response headers
            CURLOPT_HEADERFUNCTION => function ($curl, $header) use (&$captured_headers) {
                $len = strlen($header);
                $header = explode(':', $header, 2);
                if (count($header) < 2) { // ignore invalid headers
                    return $len;
                }
                $name = strtolower(trim($header[0]));
                $value = trim($header[1]);
                $captured_headers[$name] = $value;
                return $len;
            }
        ];

        if ($method === 'PUT' || $method === 'DELETE' || $method === 'POST') {
            $options[CURLOPT_CUSTOMREQUEST] = $method;
            if (!empty($payload)) {
                $options[CURLOPT_POSTFIELDS] = $payload;
            }
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception("cURL error: " . curl_error($ch));
        }

        if ($response === null) {
            throw new Exception("Request returned null");
        }

        $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        $response_data = !empty($response) ? json_decode($response, true) : null;

        if ($response_data === null && !empty($response) && json_last_error() !== JSON_ERROR_NONE) {

            throw new Exception("Failed to decode JSON response: " . json_last_error_msg());
        }

        if ($response_code >= 400 && $response_code < 500) {
            $this->logApiError($method, $url, $response_code, $response_data);
        }

        $max_retries = 1;

        // retry on 401 (unauthorized - try refresh token)
        if ($response_code === 401 && $retry_count < $max_retries) {

            if ($this->refreshToken()) {

                $access_token  = $this->tokenStorage->retrieveSavedAccessToken();

                $updated_headers = array_map(function ($header) use ($access_token) {
                    if (strpos($header, 'Authorization: Bearer') === 0) {
                        return "Authorization: Bearer " . $access_token;
                    }
                    return $header;
                }, $headers);

                return $this->performRequest($method, $url, $updated_headers, $payload, $retry_count + 1);
            }
            // or if refreshToken fails, the standard response array is returned
        }

        // Retry on 429 (rate limit)
        if ($response_code === 429 && $retry_count < 3) {
            $retry_after = $captured_headers['retry-after'] ?? null;
            $delay_seconds = $retry_after !== null ? (int)$retry_after : pow(2, $retry_count) + rand(0, 1);
            // exponential fallback
            sleep($delay_seconds);
            return $this->performRequest($method, $url, $headers, $payload, $retry_count + 1);
        }

        return [
            'response_code' => $response_code,
            'response' => $response_data,
            'headers' => $captured_headers
        ];
    }

    public function sendGetRequest(string $url, array $headers, int $retry_count = 0, $fraud_headers = true)
    {
        if ($fraud_headers) {

            if (!empty($_SERVER['REMOTE_ADDR'])) {
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
            }

            if (!empty($_SERVER['REMOTE_PORT'])) {
                $_SESSION['user_port'] = $_SERVER['REMOTE_PORT'];
            }

            $gov_headers = $this->apiFraudPreventionHeaders->setHeaders();

            $headers = array_merge($headers, $gov_headers);
        }


        $response = $this->performRequest('GET', $url, $headers, '', $retry_count);

        if (!$response) {
            return null;
        }

        return $response;
    }

    public function sendPostRequest(string $url, string $payload, array $headers, int $retry_count = 0, $fraud_headers = true)
    {
        if ($fraud_headers) {

            if (!empty($_SERVER['REMOTE_ADDR'])) {
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
            }

            if (!empty($_SERVER['REMOTE_PORT'])) {
                $_SESSION['user_port'] = $_SERVER['REMOTE_PORT'];
            }

            $gov_headers = $this->apiFraudPreventionHeaders->setHeaders();

            $headers = array_merge($headers, $gov_headers);
        }


        $response = $this->performRequest('POST', $url, $headers, $payload, $retry_count);

        if (!$response) {
            return null;
        }

        return $response;
    }

    public function sendPutRequest(string $url, string $payload, array $headers,  int $retry_count = 0, $fraud_headers = true)
    {
        if ($fraud_headers) {

            if (!empty($_SERVER['REMOTE_ADDR'])) {
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
            }

            if (!empty($_SERVER['REMOTE_PORT'])) {
                $_SESSION['user_port'] = $_SERVER['REMOTE_PORT'];
            }

            $gov_headers = $this->apiFraudPreventionHeaders->setHeaders();

            $headers = array_merge($headers, $gov_headers);
        }


        $response = $this->performRequest('PUT', $url, $headers, $payload, $retry_count);

        if (!$response) {
            return null;
        }

        return $response;
    }

    public function sendDeleteRequest(string $url, array $headers,  int $retry_count = 0, $fraud_headers = true)
    {
        if ($fraud_headers) {

            if (!empty($_SERVER['REMOTE_ADDR'])) {
                $_SESSION['user_ip'] = $_SERVER['REMOTE_ADDR'];
            }

            if (!empty($_SERVER['REMOTE_PORT'])) {
                $_SESSION['user_port'] = $_SERVER['REMOTE_PORT'];
            }

            $gov_headers = $this->apiFraudPreventionHeaders->setHeaders();

            $headers = array_merge($headers, $gov_headers);
        }

        $response = $this->performRequest('DELETE', $url, $headers, '',  $retry_count);

        if (!$response) {
            return null;
        }

        return $response;
    }

    private function logApiError(string $method, string $url, int $code, array|string|null $response): void
    {
        $log_message = sprintf(
            "[%s] %s %s - Status: %d - Response: %s\n",
            date('Y-m-d H:i:s'),
            $method,
            $url,
            $code,
            is_array($response) ? json_encode($response) : (string)$response
        );

        file_put_contents(ROOT_PATH . "logs/api_errors.log", $log_message, FILE_APPEND);
    }
}
