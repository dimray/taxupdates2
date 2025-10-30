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

    // for header menu
    public static function isSectionActive(string $current_path, array $paths): bool
    {
        foreach ($paths as $path) {
            if (str_starts_with($current_path, $path)) {
                return true;
            }
        }
        return false;
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


    // used in individual losses - change sequence
    public static function validateSequence(array $claims)
    {
        // Extract the sequence numbers into an array
        $sequences = array_map(function ($claim) {
            return (int)$claim['sequence'];
        }, $claims);

        // Sort the sequences and check for gaps
        sort($sequences);

        $valid = true;

        // Check if the sequence starts from 1 and has no gaps
        if ($sequences[0] !== 1) {
            $valid = false;
        } else {
            for ($i = 1; $i < count($sequences); $i++) {
                if ($sequences[$i] !== $sequences[$i - 1] + 1) {
                    $valid = false;
                    break;
                }
            }
        }

        return $valid;
    }

    // used in bsas
    public static function removeZerosAndEmptyValuesFromArray(array &$data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::removeZerosAndEmptyValuesFromArray($data[$key]);
                // Remove empty arrays
                if (empty($data[$key])) {
                    unset($data[$key]);
                }
            } elseif ($value === '' || $value === '0') {
                unset($data[$key]);
            }
        }
    }


    public static function removeEmptyValuesFromArray(array &$data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                self::removeEmptyValuesFromArray($data[$key]);
                // Remove empty arrays
                if (empty($data[$key])) {
                    unset($data[$key]);
                }
            } elseif ($value === '') {
                unset($data[$key]);
            }
        }
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



    public static function unsetBusinessSessionInfo()
    {
        unset($_SESSION['business_id']);
        unset($_SESSION['type_of_business']);
        unset($_SESSION['trading_name']);
    }

    public static function clearUpSession()
    {
        unset($_SESSION['cumulative_summary']);
        unset($_SESSION['annual_submission']);
        unset($_SESSION['bsas']);
    }

    public static function validateAndFormatAmount(string $value, float $min = 0.0, float $max = PHP_FLOAT_MAX): ?float
    {
        // Remove commas, whitespace, etc.
        $clean = trim(str_replace(',', '', $value));

        // Check if it's numeric
        if (!is_numeric($clean)) {
            return null;
        }

        $float = (float) $clean;

        if ($float < $min || $float > $max) {
            return null;
        }

        return $float;
    }

    public static function cleanupEmptySubArrays(array $array): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value) && self::recursiveArrayEmpty($value)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    public static function recursiveArrayEmpty(array $array): bool
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                if (!self::recursiveArrayEmpty($value)) {
                    return false;
                }
            } else {
                if (trim((string)$value) !== '') {
                    return false;
                }
            }
        }

        return true;
    }

    public static function formatCamelCase(string $string)
    {

        // Insert space before each uppercase letter, except the first character
        $withSpaces = preg_replace('/(?<!^)([A-Z])/', ' $1', $string);

        // Capitalize first letter of each word
        return ucwords($withSpaces);
    }

    public static function getCountry($country_code): string
    {
        $country_data = require ROOT_PATH . "config/mappings/country-codes.php";

        $country_string = "";
        foreach ($country_data as $continent) {
            foreach ($continent as $code => $country) {
                if (strtoupper($code) === strtoupper($country_code)) {
                    $country_string = $country;
                    break;
                }
            }
        }

        return $country_string;
    }

    public static function validateAmount(string $value, float $min = 0.0, float $max = PHP_FLOAT_MAX): ?float
    {
        // Remove commas, whitespace, etc.
        $clean = trim(str_replace(',', '', $value));

        // Check if it's numeric
        if (!is_numeric($clean)) {
            return null; // Or throw new Exception("Invalid number: $value");
        }

        $float = (float) $clean;

        if ($float < $min || $float > $max) {
            return null; // Or throw/return error message
        }

        return $float;
    }
}