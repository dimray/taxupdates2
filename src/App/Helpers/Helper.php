<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;
use DateTime;
use DateTimeZone;
use Exception;

class Helper
{
    // for registration and login
    public static function standardiseInputs(array $data): array
    {
        if (isset($data['name'])) {
            $data['name'] = ucwords((string) ($data['name']));
        }

        if (isset($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        if (isset($data['nino'])) {
            $data['nino'] = trim(strtoupper((string) ($data['nino'])));
        }
        if (isset($data['arn'])) {
            $data['arn'] = trim(strtoupper($data['arn']));
        }

        if (isset($data['password'])) {
            $data['password'] = trim(($data['password']));
        }

        if (isset($data['confirm_password'])) {
            $data['confirm_password'] = trim(($data['confirm_password']));
        }

        return $data;
    }

    // used to create hash of nino and arn
    public static function getHash(string $data): string
    {
        $key = $_ENV['HASH_KEY'];
        return hash_hmac('sha256', $data, $key);
    }

    // used in Registration and Session
    public static function isDeviceDataValid(string $raw_device_data): bool
    {
        if (empty($raw_device_data)) {
            return false;
        }

        $decoded_data = json_decode($raw_device_data, true);

        if (!is_array($decoded_data) || !isset($decoded_data['deviceID'])) {

            return false;
        }

        $device_id = $decoded_data['deviceID'];

        if (!Validate::uuidV4($device_id)) {

            return false;
        }

        return true;
    }

    public static function getNino(): string
    {
        $nino = $_SESSION['nino'] ?? $_SESSION['client']['nino'] ?? "";

        return $nino;
    }

    public static function setBusinessDetails()
    {
        $details = [];

        if (isset($_SESSION['business_id'])) {
            $details["businessId"] = $_SESSION['business_id'];
        }

        if (isset($_SESSION['type_of_business'])) {
            $details["typeOfBusiness"] = $_SESSION['type_of_business'];
        }

        if (isset($_SESSION['trading_name'])) {
            $details["tradingName"] = $_SESSION['trading_name'];
        }

        if (isset($_SESSION['tax_year'])) {
            $details["taxYear"] = $_SESSION['tax_year'];
        }

        return $details;
    }

    // used by Firm and Clients
    public static function paginate(int $total_items, int $per_page, array $get): array
    {
        $current_page = isset($get['page']) ? (int) $get['page'] : 1;
        $current_page = max($current_page, 1);

        $total_pages = (int) ceil($total_items / $per_page);
        $offset = ($current_page - 1) * $per_page;

        return [

            'per_page' => $per_page,
            'offset' => $offset,
            'total_pages' => $total_pages,
            'total_items' => $total_items,
            'current_page' => $current_page,
            'has_prev_page' => $current_page > 1,
            'has_next_page' => $current_page < $total_pages,
            'next_page' => $current_page < $total_pages ? $current_page + 1 : null,
            'prev_page' => $current_page > 1 ? $current_page - 1 : null,
        ];
    }

    public static function formatDateTime(string $input): string
    {
        try {
            // Parse input as UTC datetime string
            $utcDate = new DateTime($input, new DateTimeZone('UTC'));
            // Convert to Europe/London timezone
            $utcDate->setTimezone(new DateTimeZone('Europe/London'));

            return $utcDate->format('F j Y, g:i A');
        } catch (Exception $e) {
            // If input invalid, just return input as fallback
            return $input;
        }
    }
}
