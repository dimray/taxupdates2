<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

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
}
