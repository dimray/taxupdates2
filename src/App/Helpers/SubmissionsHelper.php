<?php

declare(strict_types=1);

namespace App\Helpers;

use Framework\Encryption;

class SubmissionsHelper
{

    public static function camelCaseArrayKeys(array $input): array
    {
        $result = [];

        foreach ($input as $key => $value) {
            // Normalize whitespace, then convert to camelCase
            $normalizedKey = preg_replace('/\s+/', ' ', trim($key));
            $words = explode(' ', $normalizedKey);

            // If the key is already camelCase, this will return the same key
            $camelKey = lcfirst(array_shift($words));
            foreach ($words as $word) {
                $camelKey .= ucfirst($word);
            }

            $result[$camelKey] = $value;
        }

        return $result;
    }

    // replace empty or string values with zero and format as float
    public static function formatArrayValuesAsFloat(array $data): array
    {
        foreach ($data as $key => $value) {

            if (is_bool($value)) {
                $data[$key] = $value;
                continue;
            }

            if (is_array($value)) {
                $data[$key] = self::formatArrayValuesAsFloat($value); // recurse
            } else {
                $trimmed = trim((string)$value);

                if ($trimmed === '') {
                    $data[$key] = '';
                } elseif (is_numeric($trimmed)) {
                    $data[$key] = round((float) $trimmed, 2);
                } else {
                    // Try to normalize comma-formatted numbers
                    $normalized = str_replace(',', '', $trimmed);

                    if (is_numeric($normalized)) {
                        $data[$key] = round((float) $normalized, 2);
                    } else {
                        // Leave non-numeric strings unchanged
                        $data[$key] = $value;
                    }
                }
            }
        }

        return $data;
    }

    public static function validatePositiveNegativeCumulativeArrays(array $data, string $business_type): array
    {
        $errors = [];

        $rules = match ($business_type) {
            'self-employment' => [
                ['periodIncome', 0, 99999999999.99],
                ['periodExpenses', -99999999999.99, 99999999999.99],
                ['periodDisallowableExpenses', -99999999999.99, 99999999999.99],
            ],
            'property' => [
                ['income', 0, 99999999999.99],
                ['expenses', -99999999999.99, 99999999999.99],
                ['rentARoom', 0, 99999999999.99],
                ['residentialFinance', 0, 99999999999.99],
            ],
            default => [],
        };

        foreach ($rules as [$key, $min, $max]) {
            $section = $data[$key] ?? null;
            $errors = array_merge($errors, self::validateSection($section, $min, $max));
        }

        return $errors;
    }

    private static function validateSection(?array $section, float $min, float $max): array
    {
        $errors = [];

        if (is_array($section)) {
            foreach ($section as $key => $value) {
                if (!self::validateAmount($value, $min, $max)) {
                    $errors[] = "'" . self::camelCaseToWords($key) . "'" . " must be a number between " . number_format($min, 2) . " and " .  number_format($max, 2);
                }
            }
        }

        return $errors;
    }

    public static function validateAmount($value, $min = -99999999999.99, $max = 99999999999.99): bool
    {

        if (!is_numeric($value)) {
            return false;
        }

        // ensure 2 decimal places
        if (!preg_match('/^-?\d+(\.\d{1,2})?$/', (string) $value)) {

            return false;
        }

        return $value >= $min && $value <= $max;
    }

    public static function camelCaseToWords(string $camelCaseString): string
    {

        $spaced_string =  preg_replace('/(?<=\\w)(?=[A-Z])/', ' ', $camelCaseString);

        return ucwords($spaced_string);
    }



    public static function buildArrays(array $data, string $business_type, string $submission_type, $country_code = "")
    {
        $result = [];

        // this is the allowed categories in config
        $mappings = require ROOT_PATH . "config/mappings/{$business_type}.php";

        foreach ($mappings[$submission_type] as $array_name => $keys) {

            $result[$array_name] = [];

            foreach ($keys as $key) {
                // Only add data if the key exists in the input $data array
                if (isset($data[$key])) {

                    $result[$array_name][$key] = $data[$key];
                }
            }
        }

        return $result;
    }

    // data to save in Submissions table
    public static function createSubmission($submission_type, $submission_id): array
    {

        $period_start_date = $_SESSION['period_start_date'] ?? TaxYearHelper::getTaxYearStartDate($_SESSION['tax_year']);

        if ($submission_type !== "cumulative") {
            $period_end_date =  TaxYearHelper::getTaxYearEndDate($_SESSION['tax_year']);
        } else {
            $period_end_date = $_SESSION['period_end_date'];
        }

        $nino = Helper::getNino();
        $encrypted_nino = Encryption::encrypt($nino);
        $nino_hash = Helper::getHash($nino);

        $business_id = $_SESSION['business_id'] ?? null;

        $submission_stats = [
            "nino" => $encrypted_nino,
            "nino_hash" => $nino_hash,
            "business_id" => $business_id,
            "period_start" => $period_start_date,
            "period_end" => $period_end_date,
            "tax_year" => $_SESSION['tax_year'],
            "submitted_at" => date('Y-m-d H:i:s'),
            "submission_type" => $submission_type,
            "submission_reference" => $submission_id
        ];

        $role = $_SESSION['user_role'] ?? 'individual';

        if ($role === 'individual') {
            $submission_stats['submitted_by_user_id'] = $_SESSION['user_id'];
            $submission_stats['submitted_by_firm_id'] = null;
            $submission_stats['submitted_by_type'] = 'individual';
        } elseif ($role === 'agent') {
            $submission_stats['submitted_by_user_id'] = null;
            $submission_stats['submitted_by_firm_id'] = $_SESSION['firm_id'] ?? null;
            $submission_stats['submitted_by_type'] = 'agent';
        }

        return $submission_stats;
    }

    public static function finaliseUkPropertyCumulativeSummaryArray(): void
    {
        $cumulative_data = $_SESSION['cumulative_data'][$_SESSION['business_id']];

        if (!empty($cumulative_data['rentARoom'])) {

            if (isset($cumulative_data['rentARoom']['rentARoomRentsReceived'])) {
                $cumulative_data['income']['rentARoom']['rentsReceived'] = $cumulative_data['rentARoom']['rentARoomRentsReceived'];
            }

            if (isset($cumulative_data['rentARoom']['rentARoomAmountClaimed'])) {
                $cumulative_data['expenses']['rentARoom']['amountClaimed'] = $cumulative_data['rentARoom']['rentARoomAmountClaimed'];
            }

            unset($cumulative_data['rentARoom']);
        }

        if (!empty($cumulative_data['residentialFinance'])) {

            if (isset($cumulative_data['residentialFinance']['residentialFinancialCost'])) {
                $cumulative_data['expenses']['residentialFinancialCost'] = $cumulative_data['residentialFinance']['residentialFinancialCost'];
            }

            if (isset($cumulative_data['residentialFinance']['residentialFinancialCostsCarriedForward'])) {
                $cumulative_data['expenses']['residentialFinancialCostsCarriedForward'] = $cumulative_data['residentialFinance']['residentialFinancialCostsCarriedForward'];
            }

            unset($cumulative_data['residentialFinance']);
        }

        $final_array['fromDate'] = $_SESSION['period_start_date'];
        $final_array['toDate'] = $_SESSION['period_end_date'];
        $final_array['ukProperty'] = $cumulative_data;

        $_SESSION['cumulative_data'][$_SESSION['business_id']] = $final_array;
    }
}
