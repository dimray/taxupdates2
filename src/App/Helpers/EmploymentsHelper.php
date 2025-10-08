<?php

declare(strict_types=1);

namespace App\Helpers;

use Framework\Controller;

class EmploymentsHelper extends Controller
{

    public static function validateAndFormatEmploymentFinancialDetails(array $data, bool $is_required = false, bool $is_root = true): array
    {
        $validated = [];

        if ($is_root) {
            $off_payroll_worker = isset($data['off_payroll_worker']) ? true : false;
            unset($data['off_payroll_worker']);
        }


        foreach ($data as $key => $value) {
            // Set required flag if this is the 'pay' section
            $current_is_required = $is_required || $key === 'pay';

            if (is_array($value)) {
                // Recursively validate nested arrays
                $nested = self::validateAndFormatEmploymentFinancialDetails($value, $current_is_required, false);

                if (!empty($nested)) {
                    $validated[$key] = $nested;
                }
                // Skip adding key if nested validation returned nothing
                continue;
            }

            // Set validation range
            $min = ($key === 'totalTaxToDate') ? -99999999999.99 : 0;
            $max = 99999999999.99;

            // Skip empty optional fields
            if ($value === '' || $value === null) {
                if ($current_is_required) {
                    self::saveError("$key is required and must be between $min and $max");
                }
                continue; // don't include in validated
            }

            // Validate value
            $validated_value = Helper::validateAndFormatAmount($value, $min, $max);

            if ($validated_value === null) {
                self::saveError("$key must be between $min and $max");
                continue; // skip invalid values
            }

            $validated[$key] = $validated_value;
        }

        if ($is_root) {
            $validated['offPayrollWorker'] = $off_payroll_worker;
        }

        return $validated;
    }

    // ADD OTHER EMPLOYMENT INCOME

    public static function formatOtherEmploymentsArrays(array $data): ?array
    {

        if (Helper::recursiveArrayEmpty($data)) {
            self::saveError("Please add data before submitting");
            return null;
        }

        $share_options = self::validateAndFormatShareOptions($data['shareOption'] ?? []);

        if (!Helper::recursiveArrayEmpty($share_options)) {
            $data['shareOption'] = $share_options;
        } else {
            unset($data['shareOption']);
        }

        $share_awards = self::validateAndFormatShareAwards($data['sharesAwardedOrReceived'] ?? []);

        if (!Helper::recursiveArrayEmpty($share_awards)) {
            $data['sharesAwardedOrReceived'] = $share_awards;
        } else {
            unset($data['sharesAwardedOrReceived']);
        }

        $lump_sums = self::validateAndFormatLumpSums($data['lumpSums'] ?? []);

        if (!Helper::recursiveArrayEmpty($lump_sums)) {
            $data['lumpSums'] = $lump_sums;
        } else {
            unset($data['lumpSums']);
        }

        $disability = self::validateAndFormatDisabilityOrForeignService($data['disability'] ?? []);

        if (empty($disability)) {
            unset($data['disability']);
        }

        $foreign_service = self::validateAndFormatDisabilityOrForeignService($data['foreignService'] ?? []);

        if (empty($foreign_service)) {
            unset($data['foreignService']);
        }

        return $data;
    }

    private static function validateAndFormatShareOptions(array $share_options): array
    {


        $number_fields = [
            'amountOfConsiderationReceived',
            'exercisePrice',
            'amountPaidForOption',
            'marketValueOfSharesOnExcise',
            'profitOnOptionExercised',
            'employersNicPaid',
            'taxableAmount'
        ];

        $mandatory_fields = [
            'employerName',
            'schemePlanType',
            'dateOfOptionGrant',
            'dateOfEvent',
            'amountOfConsiderationReceived',
            'noOfSharesAcquired',
            'exercisePrice',
            'amountPaidForOption',
            'marketValueOfSharesOnExcise',
            'profitOnOptionExercised',
            'employersNicPaid',
            'taxableAmount'
        ];

        foreach ($share_options as $key => $option) {

            $option = self::removeEmptyRows($option);

            if (Helper::recursiveArrayEmpty($option)) {
                unset($share_options[$key]);
                continue;
            }

            if (empty($option['employerName']) && empty($option['taxableAmount'])) {
                unset($share_options[$key]);
                continue;
            }

            $rowNumber = $key + 1;

            foreach ($mandatory_fields as $field) {
                if (!isset($option[$field]) || trim((string)$option[$field]) === '') {

                    self::saveError("Share Options item {$rowNumber} - if Employer Name or Taxable Amount is entered, all required fields must be completed");
                    continue;
                }
            }

            if (isset($option['employerName'])) {
                if (!preg_match("/^[0-9a-zA-Z{À-˿’}\- _&`():.'^]{1,105}$/u", $option['employerName'])) {
                    self::saveError("Share Options item {$rowNumber} - Employer Name must be text between 1 and 105 characters");
                }
            }

            $allowedSchemes = ['emi', 'csop', 'saye', 'other'];
            if (empty($option['schemePlanType']) || !in_array($option['schemePlanType'], $allowedSchemes, true)) {
                self::saveError("Share Options item {$rowNumber} - select Plan Type from the dropdown list");
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $option['dateOfOptionGrant'])) {
                self::saveError("Share Options item {$rowNumber} - Grant Date is invalid or missing");
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $option['dateOfEvent'])) {
                self::saveError("Share Options item {$rowNumber} - Event Date is invalid or missing");
            }

            // validate number of shares and round to integer if necessary
            if (isset($option['noOfSharesAcquired'])) {
                $numShares = $option['noOfSharesAcquired'];

                if (!is_numeric($numShares) || $numShares < 0) {
                    self::saveError("Share Options item {$rowNumber} - Number Of Shares Acquired must be 0 or more and a whole number");
                } else {
                    // Force integer conversion (floor to get whole number)
                    $option['noOfSharesAcquired'] = (int) floor((float) $numShares);
                }
            } else {

                self::saveError("Share Options - Number Of Shares Acquired is required.");
            }

            foreach ($number_fields as $field) {
                if (isset($option[$field])) {
                    $value = $option[$field];
                    if (!is_numeric($value) || $value < 0 || $value > 99999999999.99) {
                        $field_label = Helper::formatCamelCase($field);
                        self::saveError("Share Options item {$rowNumber} - {$field_label} must be a number between 0 and 99999999999.99");
                    } else {
                        $option[$field] = round((float)$value, 2);
                    }
                }
            }

            // optional fields
            if (isset($option['employerRef'])) {
                if (!preg_match("/^[0-9]{3}\/[^ ].{0,9}$/u", $option['employerRef'])) {
                    self::saveError("Share Options item {$rowNumber} - Employer Reference is not in the correct format");
                }
            }

            if (isset($option['optionNotExercisedButConsiderationReceived'])) {
                $option['optionNotExercisedButConsiderationReceived'] = true;
            }

            if (isset($option['classOfSharesAcquired'])) {
                if (!preg_match("/^[0-9a-zA-Z{À-˿’}\- _&`():.'^]{1,90}$/u", $option['classOfSharesAcquired'])) {
                    self::saveError("Share Options item {$rowNumber} - Class Of Shares cannot be longer than 90 characters or contain invalid characters");
                }
            }

            // Save modified option back to main array
            $share_options[$key] = $option;
        }

        return $share_options;
    }

    private static function validateAndFormatShareAwards(array $share_awards): array
    {
        $number_fields = [
            'actualMarketValueOfSharesOnAward',
            'unrestrictedMarketValueOfSharesOnAward',
            'amountPaidForSharesOnAward',
            'marketValueAfterRestrictionsLifted',
            'taxableAmount'
        ];

        $boolean_fields = [
            'sharesSubjectToRestrictions',
            'electionEnteredIgnoreRestrictions'
        ];

        $mandatory_fields = [
            'employerName',
            'schemePlanType',
            'dateSharesCeasedToBeSubjectToPlan',
            'noOfShareSecuritiesAwarded',
            'classOfShareAwarded',
            'dateSharesAwarded',
            'sharesSubjectToRestrictions',
            'electionEnteredIgnoreRestrictions',
            'actualMarketValueOfSharesOnAward',
            'unrestrictedMarketValueOfSharesOnAward',
            'amountPaidForSharesOnAward',
            'marketValueAfterRestrictionsLifted',
            'taxableAmount'
        ];


        foreach ($share_awards as $key => $award) {

            $award = self::removeEmptyRows($award);

            $rowNumber = $key + 1;
            if (Helper::recursiveArrayEmpty($award)) {
                unset($share_awards[$key]);
                continue;
            }

            if (empty($award['employerName']) && empty($award['taxableAmount'])) {
                unset($share_awards[$key]);
                continue;
            }

            foreach ($mandatory_fields as $field) {
                if (!isset($award[$field]) || trim((string)$award[$field]) === '') {

                    self::saveError("Share Awards item {$rowNumber} - if Employer Name or Taxable Amount is entered, all required fields must be completed");
                    continue;
                }
            }


            if (!preg_match("/^[0-9a-zA-Z{À-˿’}\- _&`():.'^]{1,105}$/u", $award['employerName'])) {
                self::saveError("Share Awards item {$rowNumber} - Employer Name must be text between 1 and 105 characters");
            }

            if (!preg_match("/^[0-9a-zA-Z{À-˿’}\- _&`():.'^]{1,90}$/u", $award['classOfShareAwarded'])) {
                self::saveError("Share Awards item {$rowNumber} - Class Of Shares must be text between 1 and 90 characters");
            }

            // validate number of shares and round to integer if necessary
            if (isset($award['noOfShareSecuritiesAwarded'])) {
                $numShares = $award['noOfShareSecuritiesAwarded'];

                if (!is_numeric($numShares) || $numShares < 0) {
                    self::saveError("Share Awards item {$rowNumber} - Number Of Shares Acquired must be 0 or more and a whole number.");
                } else {
                    // Force integer conversion (floor to get whole number)
                    $award['noOfShareSecuritiesAwarded'] = (int) floor((float)$numShares);
                }
            } else {
                self::saveError("Share Awards item {$rowNumber} - Number Of Shares Awarded is required.");
            }

            // allowed schemes
            $allowedSchemes = ['sip', 'other'];
            if (empty($award['schemePlanType']) || !in_array($award['schemePlanType'], $allowedSchemes, true)) {
                self::saveError("Share Awards item {$rowNumber} - select Plan Type from the dropdown list");
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $award['dateSharesCeasedToBeSubjectToPlan'])) {
                self::saveError("Share Awards item {$rowNumber} - Date Shares Ceased To Be Subject To Plan is invalid or missing");
            }

            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $award['dateSharesAwarded'])) {
                self::saveError("Share Awards item {$rowNumber} - Date Shares Awarded is invalid or missing");
            }

            foreach ($boolean_fields as $field) {
                if (isset($award[$field])) {
                    $value = $award[$field];
                    if ($value !== "0" && $value !== "1") {
                        self::saveError("Share Awards item {$rowNumber} - {$field} value must be 'yes' or 'no'");
                    } else {
                        $award[$field] = filter_var($value, FILTER_VALIDATE_BOOL);
                    }
                }
            }

            foreach ($number_fields as $field) {
                if (isset($award[$field])) {
                    $value = $award[$field];
                    if (!is_numeric($value) || $value < 0 || $value > 99999999999.99) {
                        $field_label = Helper::formatCamelCase($field);
                        self::saveError("Share Awards item {$rowNumber} - {$field_label} must be a number between 0 and 99999999999.99");
                    } else {
                        $award[$field] = round((float)$value, 2);
                    }
                }
            }

            // optional fields
            if (isset($award['employerRef'])) {
                if (!preg_match("/^[0-9]{3}\/[^ ].{0,9}$/u", $award['employerRef'])) {
                    self::saveError("Share Awards item {$rowNumber} - Employer Reference is not in the correct format");
                }
            }

            $share_awards[$key] = $award;
        }

        return $share_awards;
    }

    private static function validateAndFormatLumpSums(array $lump_sums): array
    {

        $nested_fields = [
            'taxableLumpSumsAndCertainIncome',
            'benefitFromEmployerFinancedRetirementScheme',
            'redundancyCompensationPaymentsOverExemption',
            'redundancyCompensationPaymentsUnderExemption'
        ];

        foreach ($lump_sums as $key => $lump_sum) {
            $rowNumber = $key + 1;
            if (Helper::recursiveArrayEmpty($lump_sum)) {
                unset($lump_sums[$key]);
                continue;
            }

            if (empty($lump_sum['employerName']) && empty($lump_sum['employerRef'])) {
                unset($lump_sums[$key]);
                continue;
            }

            $employer = "";
            if (!empty($lump_sum['employerName'])) {
                $employer = ucwords($lump_sum['employerName']);
            }

            if (!isset($lump_sum['employerName']) || !preg_match("/^[0-9a-zA-Z{À-˿’}\- _&`():.'^]{1,105}$/u", $lump_sum['employerName'])) {
                self::saveError("Lump Sums item {$rowNumber} - Employer Name must be between 1 and 105 characters");
            }

            if (!isset($lump_sum['employerRef']) || !preg_match("/^[0-9]{3}\/[^ ].{0,9}$/u", $lump_sum['employerRef'])) {

                self::saveError("Lump Sums $employer - Employer PAYE Reference must be in the correct format - 3 digits followed by a slash followed by up to 9 characters");
            }

            // ensure at least one amount is present
            $has_amount = false;

            foreach ($nested_fields as $field) {
                if (!isset($lump_sum[$field]) || !is_array($lump_sum[$field])) {
                    continue;
                }

                $value = $lump_sum[$field]['amount'] ?? null;

                // If the amount is an empty string, null, or not numeric — remove the subarray
                if ($value === null || trim($value) === '' || !is_numeric($value)) {
                    unset($lump_sum[$field]);
                    continue;
                }

                $has_amount = true;

                $value = (float) $value;

                // Check bounds
                if (!is_numeric($value) || $value < 0 || $value > 99999999999.99) {
                    self::saveError("Lump Sums - $employer: amount must be a number between 0 and 99999999999.99");
                    continue;
                }

                // Format to 2 decimal places
                $lump_sum[$field]['amount'] = round($value, 2);

                // optional fields
                if (isset($lump_sum[$field]['taxPaid'])) {
                    $value = $lump_sum[$field]['taxPaid'];

                    if ($value === '') {
                        unset($lump_sum[$field]['taxPaid']);
                    } else {

                        if (!is_numeric($value) || $value < 0 || $value > 99999999999.99) {
                            self::saveError("Lump Sums - $employer: tax paid must be a number between 0 and 99999999999.99");
                            continue;
                        }
                    }
                }

                if (isset($lump_sum[$field]['exemptAmount'])) {
                    $value = $lump_sum[$field]['exemptAmount'];

                    if ($value === '') {
                        unset($lump_sum[$field]['exemptAmount']);
                    } else {

                        if (!is_numeric($value) || $value < 0 || $value > 99999999999.99) {
                            self::saveError("Lump Sums - $employer: exempt amount must be a number between 0 and 99999999999.99");
                            continue;
                        }
                    }
                }

                if (isset($lump_sum[$field]['taxTakenOffInEmployment'])) {
                    $lump_sum[$field]['taxTakenOffInEmployment'] = true;
                }
            }

            if (!$has_amount) {
                self::saveError("$employer - At least one lump sum amount is required if an employer is entered.");
            }

            $lump_sums[$key] = $lump_sum;
        }

        return $lump_sums;
    }

    private static function validateAndFormatDisabilityOrForeignService(array $amountArray): array
    {
        if (isset($amountArray['amountDeducted'])) {
            $value  = $amountArray['amountDeducted'];

            if ($value === null || $value === '') {
                return [];
            } elseif (!is_numeric($value) || $value < 0 || $value > 99999999999.99) {
                self::saveError("Disability or Foreign Service Amount Deducted must be a number between 0 and 99999999999.99");
            } else {
                $amountArray['amountDeducted'] = round((float)$value, 2);
            }
        }

        return $amountArray;
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

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
