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

        // check if there is anything other than code and message:
        $extra_data = $response;
        unset($extra_data['code'], $extra_data['message']);

        $msg = "HMRC Message: ";

        if ($response_message !== '') {
            $msg .= $response_message;
        } elseif ($response_code !== '') {
            $msg .= $response_code;
        } else {
            $msg .= "An error has occurred. Please try again";
        }

        // add extra data (missing paths or fields)
        if (!empty($extra_data)) {
            foreach ($extra_data as $key => $value) {
                if (is_array($value)) {
                    $value = implode(", ", $value);
                }
                $msg .= " | {$key}: {$value}";
            }
        }

        Flash::addMessage($msg, Flash::WARNING);



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

        // code 401 is unauthorized
        if ($code === 401) {
            return [
                'type' => 'redirect',
                'location' => '/authenticate/new'
            ];
        }




        return ['type' => 'error'];
    }
}