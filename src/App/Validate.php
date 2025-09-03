<?php

declare(strict_types=1);

namespace App;

class Validate
{

    public static function string(string $value, int $min = 1, float $max = INF): bool
    {
        $value = trim($value);

        return strlen($value) >= $min && strlen($value) <= $max;
    }

    public static function email(string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function password_confirmation(string $password, string $password_confirmation): bool
    {
        return $password === $password_confirmation;
    }

    public static function nino(string $value): bool
    {
        $pattern = '/^((?!(BG|GB|KN|NK|NT|TN|ZZ)|(D|F|I|Q|U|V)[A-Z]|[A-Z](D|F|I|O|Q|U|V))[A-Z]{2})[0-9]{6}[A-D]$/';
        return preg_match($pattern, strtoupper($value)) === 1;
    }

    public static function postcode(string $value): bool
    {
        $pattern = '/^([A-Za-z][A-Za-z]\d\d|[A-Za-z][A-Za-z]\d|[A-Za-z]\d|[A-Za-z]\d\d|[A-Za-z]\d[A-Za-z]|[A-Za-z]{2}\d[A-Za-z]) {0,1}\d[A-Za-z]{2}$/';
        return preg_match($pattern, $value) === 1;
    }

    public static function arn(string $value): bool
    {
        $pattern = '/^[A-Z]ARN[0-9]{7}$/';
        return preg_match($pattern, $value) === 1;
    }

    public static function tax_year($tax_year)
    {
        return preg_match('/^\d{4}-\d{2}$/', $tax_year);
    }

    public static function uuidV4(string $uuid): bool
    {
        return (bool) preg_match(
            '/^[a-f0-9]{8}-[a-f0-9]{4}-4[a-f0-9]{3}-[89ab][a-f0-9]{3}-[a-f0-9]{12}$/i',
            $uuid
        );
    }

    public static function payeRef(string $paye_ref): bool
    {

        return (bool) preg_match(
            '/^[0-9]{3}\/[^ ].{0,9}$/u',
            $paye_ref
        );
    }

    public static function countryCode(string $country_code): bool
    {
        return preg_match('/^[A-Z]{3}$/', strtoupper(trim($country_code))) === 1;
    }

    public static function date(string $value): bool
    {
        $date = \DateTime::createFromFormat('Y-m-d', $value);

        // Ensure both format matches and the parsed date is valid
        return $date && $date->format('Y-m-d') === $value;
    }
}
