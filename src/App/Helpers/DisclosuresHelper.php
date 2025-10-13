<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class DisclosuresHelper
{
    public static function validateCreateMarriageAllowance(array $marriage_allowance): array
    {

        if (
            empty($marriage_allowance['spouseOrCivilPartnerNino']) &&
            empty($marriage_allowance['spouseOrCivilPartnerFirstName']) &&
            empty($marriage_allowance['spouseOrCivilPartnerSurname']) &&
            empty($marriage_allowance['spouseOrCivilPartnerDateOfBirth'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");

            return [];
        }

        // required fields
        if (empty($marriage_allowance['spouseOrCivilPartnerNino'])) {
            self::saveError("National Insurance Number is required");
        }

        if (empty($marriage_allowance['spouseOrCivilPartnerSurname'])) {
            self::saveError("Surname is required");
        }

        // validate entries
        if (isset($marriage_allowance['spouseOrCivilPartnerNino'])) {
            if (!Validate::nino($marriage_allowance['spouseOrCivilPartnerNino'])) {
                self::saveError("National Insurance number is not valid");
            }
        }

        if (isset($marriage_allowance['spouseOrCivilPartnerFirstName'])) {
            if (!empty($marriage_allowance['spouseOrCivilPartnerFirstName'])) {
                if (!Validate::string($marriage_allowance['spouseOrCivilPartnerFirstName'], 1, 35)) {
                    self::saveError("First Name is not valid");
                }
            } else {
                unset($marriage_allowance['spouseOrCivilPartnerFirstName']);
            }
        }

        if (isset($marriage_allowance['spouseOrCivilPartnerSurname'])) {
            if (!Validate::string($marriage_allowance['spouseOrCivilPartnerSurname'], 1, 35)) {
                self::saveError("A valid surname not longer thann 35 characters is required");
            }
        }

        if (isset($marriage_allowance['spouseOrCivilPartnerDateOfBirth'])) {
            if (!empty($marriage_allowance['spouseOrCivilPartnerDateOfBirth'])) {
                if (!Validate::string($marriage_allowance['spouseOrCivilPartnerDateOfBirth'])) {
                    self::saveError("Date Of Birth is not in a valid format");
                }
            } else {
                unset($marriage_allowance['spouseOrCivilPartnerDateOfBirth']);
            }
        }

        return $marriage_allowance;
    }

    public static function validateAndFormatDisclosures(array $disclosures): array
    {
        // Remove completely empty arrays first
        $disclosures['taxAvoidance'] = self::removeEmptyRows($disclosures['taxAvoidance'] ?? []);

        // If all sections are now empty, bail early
        if (
            empty($disclosures['taxAvoidance']) &&
            empty($disclosures['class2Nics'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $disclosures;
        }

        // TAX AVOIDANCE
        if (!empty($disclosures['taxAvoidance'])) {
            foreach ($disclosures['taxAvoidance'] as $key => $entry) {

                $row_number = $key + 1;

                if (empty($entry['srn'])) {
                    self::saveError("Tax Avoidance Schemes item {$row_number} - Scheme Reference is required");
                } elseif (!preg_match('/^[0-9]{8}$/', $entry['srn'])) {
                    self::saveError("Tax Avoidance Schemes item {$row_number} - Scheme Reference format is not correct");
                }

                if (empty($entry['taxYear'])) {
                    self::saveError("Tax Avoidance Schemes item {$row_number} - Tax Year is required");
                } else {
                    $disclosures['taxAvoidance'][$key]['taxYear'] = self::validateTaxYear($entry['taxYear']);

                    if (!$disclosures['taxAvoidance'][$key]['taxYear']) {
                        self::saveError("Tax Avoidance Schemes item {$row_number} - Tax Year format is not correct");
                    }
                }
            }
        }

        // CLASS 2 NIC
        if (isset($disclosures['class2Nics'])) {

            if (!empty($disclosures['class2Nics'] && $disclosures['class2Nics'] === '1')) {
                $disclosures['class2Nics'] = ['class2VoluntaryContributions' => true];
            } else {
                unset($disclosures['class2Nics']);
            }
        }

        return $disclosures;
    }

    private static function removeEmptyRows(array $data): array
    {
        foreach ($data as $key => $entry) {
            if (is_array($entry) && Helper::recursiveArrayEmpty($entry)) {
                unset($data[$key]);
            }

            if (!is_array($entry) && trim((string)$entry) === '') {
                unset($data[$key]);
            }
        }
        return $data;
    }

    private static function validateTaxYear(string $value): ?string
    {
        $value = trim($value);

        // Normalize separators including non-breaking hyphen
        $value = str_replace(['/', 'to', '–', '—', '‑'], '-', $value);

        // Must be in format YYYY-YY
        if (preg_match('/^(\d{4})-(\d{2})$/', $value, $matches)) {
            $startYear = (int)$matches[1];
            $endYear   = (int)$matches[2];

            // End year must be consecutive
            if ($endYear === (($startYear + 1) % 100)) {
                return sprintf('%04d-%02d', $startYear, $endYear);
            }
            return null;
        }

        // Try "YYYYYY" without dash
        if (preg_match('/^(\d{4})(\d{2})$/', $value, $matches)) {
            $startYear = (int)$matches[1];
            $endYear   = (int)$matches[2];

            if ($endYear === (($startYear + 1) % 100)) {
                return sprintf('%04d-%02d', $startYear, $endYear);
            }
        }

        return null;
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
