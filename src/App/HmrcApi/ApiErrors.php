<?php

declare(strict_types=1);

namespace App\HmrcApi;

use App\Flash;

class ApiErrors
{

    public static function dealWithError(int $code = 0, array $response = [], string $api = "", string $endpoint = ""): array
    {
        $response_code = $response['code'] ?? '';
        $response_message = $response['message'] ?? '';

        if (!empty($response_message)) {
            Flash::addMessage("HMRC Message: " . $response_message, Flash::WARNING);
        } elseif (!empty($response_code)) {
            Flash::addMessage("HMRC Message: " . $response_code, Flash::WARNING);
        } else {
            Flash::addMessage("An error has occurred. Please try again", Flash::WARNING);
        }

        // if agent and not authorised
        if ($code === 403 && strtoupper($response_code) === "CLIENT_OR_AGENT_NOT_AUTHORISED") {
            if ($_SESSION['user_role'] === "agent") {
                return [
                    'type' => 'redirect',
                    'location' => '/agent-authorisation/update-authorisation-on-error'
                ];
            } else {
                return [
                    'type' => 'redirect',
                    'location' => '/authenticate/new'
                ];
            }
        }

        if ($code === 403 && $response_code === "INVALID_SCOPE") {
            return [
                'type' => 'redirect',
                'location' => '/authenticate/new'
            ];
        }

        return ['type' => 'error'];
    }
}
