<?php

declare(strict_types=1);

namespace App\Helpers;

class CapitalGainsHelper
{

    public static function validateAndFormatCustomerAddedResidentialPropertyDisposals(array $customer_added_disposals): array
    {
        // Remove completely empty rows first
        $customer_added_disposals['disposals'] = self::removeEmptyRows($customer_added_disposals['disposals'] ?? []);

        // If all sections are now empty, bail early
        if (empty($customer_added_disposals['disposals'])) {
            self::saveError("Disposal details must be entered before submitting.");
            return $customer_added_disposals;
        }

        // DISPOSALS
        if (!empty($customer_added_disposals['disposals'])) {
            foreach ($customer_added_disposals['disposals'] as $key => $entry) {

                $row_number = $key + 1;

                //  customer reference
                if (!empty($entry['customerReference']))
                    self::validateReference("Disposals item {$row_number} - Your Reference", $entry['customerReference']);

                // dates required
                if ($entry['disposalDate'] === "" || $entry['disposalDate'] === null) {
                    self::saveError("Disposals item $row_number - Disposal Date is required");
                } else {
                    self::validateDate("Disposals item {$row_number} - Disposal Date", $entry['disposalDate']);
                }

                if ($entry['completionDate'] === "" || $entry['completionDate'] === null) {
                    self::saveError("Disposals item $row_number - Completion Date is required");
                } else {
                    self::validateDate("Disposals item {$row_number} - Completion Date", $entry['completionDate']);
                }

                if ($entry['acquisitionDate'] === "" || $entry['acquisitionDate'] === null) {
                    self::saveError("Disposals item $row_number - Acquisition Date is required");
                } else {
                    self::validateDate("Disposals item {$row_number} - Acquisition Date", $entry['acquisitionDate']);
                }

                // disposal proceeds and acquisition amount required

                if ($entry['disposalProceeds'] === "" || $entry['disposalProceeds'] === null) {
                    self::saveError("Disposals item $row_number - Disposal Proceeds is required");
                }

                if ($entry['acquisitionAmount'] === "" || $entry['acquisitionAmount'] === null) {
                    self::saveError("Disposals item $row_number - Acquisition Amount is required");
                }

                // number amounts
                foreach (['disposalProceeds', 'acquisitionAmount', 'improvementCosts', 'additionalCosts', 'prfAmount', 'otherReliefAmount', 'lossesFromThisYear', 'lossesFromPreviousYear', 'amountOfNetGain', 'amountOfNetLoss'] as $reference) {

                    $formatted_ref = Helper::formatCamelCase($reference);

                    if (!empty($customer_added_disposals['disposals'][$key][$reference])) {
                        $customer_added_disposals['disposals'][$key][$reference] = self::validateFloat("Disposals item {$row_number} - {$formatted_ref}", $entry[$reference]);
                    }
                }

                // either gain or loss is required but not both
                if (!empty($entry['amountOfNetGain']) && !empty($entry['amountOfNetLoss'])) {
                    self::saveError("Disposals item $row_number - Either Gain or Loss must be entered, not both.");
                }
                if (empty($entry['amountOfNetGain']) && empty($entry['amountOfNetLoss'])) {
                    self::saveError("Disposals item $row_number - Either Gain or Loss must be entered.");
                }
            }
        }

        $customer_added_disposals = self::removeEmptyValues($customer_added_disposals);

        return $customer_added_disposals;
    }

    public static function validateAndFormatCgtOnResidentialPropertyOverrides(array $reported_disposals): array
    {

        // var_dump($reported_disposals);
        // exit;

        // Remove completely empty rows first
        $reported_disposals['multiplePropertyDisposals'] = self::removeEmptyRows($reported_disposals['multiplePropertyDisposals']);
        $reported_disposals['singlePropertyDisposals'] = self::removeEmptyRows($reported_disposals['singlePropertyDisposals']);

        // If all sections are now empty, bail early
        if (
            empty($reported_disposals['multiplePropertyDisposals']) &&
            empty($reported_disposals['singlePropertyDisposals'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $reported_disposals;
        }

        // MULTIPLE DISPOSALS

        if (!empty($reported_disposals['multiplePropertyDisposals'])) {
            foreach ($reported_disposals['multiplePropertyDisposals'] as $key => $entry) {

                $row_number = $key + 1;

                // submission id required
                if ($entry['ppdSubmissionId'] === "" || $entry['ppdSubmissionId'] === null) {
                    self::saveError("Multiple Property Disposals item $row_number - Submission Reference is required");
                } else {
                    self::validateSubmissionId("Multiple Property Disposals item {$row_number} - Submission Reference", $entry['ppdSubmissionId']);
                }

                // either gain or loss is required but not both
                if (!empty($entry['amountOfNetGain']) && !empty($entry['amountOfNetLoss'])) {
                    self::saveError("Multiple Property Disposals item $row_number - Either Gain or Loss must be entered, not both.");
                }
                if (empty($entry['amountOfNetGain']) && empty($entry['amountOfNetLoss'])) {
                    self::saveError("Multiple Property Disposals item $row_number - Either Gain or Loss must be entered.");
                }

                // validate gain and loss
                foreach (['amountOfNetGain', 'amountOfNetLoss'] as $reference) {

                    $formatted_ref = Helper::formatCamelCase($reference);

                    if (!empty($reported_disposals['multiplePropertyDisposals'][$key][$reference])) {
                        $other_capital_gains['multiplePropertyDisposals'][$key][$reference] = self::validateFloat("Multiple Property Disposals item {$row_number} - {$formatted_ref}", $entry[$reference]);
                    }
                }
            }
        }

        // SINGLE DISPOSALS

        if (!empty($reported_disposals['singlePropertyDisposals'])) {
            foreach ($reported_disposals['singlePropertyDisposals'] as $key => $entry) {

                $row_number = $key + 1;

                // submission id required
                if ($entry['ppdSubmissionId'] === "" || $entry['ppdSubmissionId'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Submission Reference is required");
                } else {
                    self::validateSubmissionId("Single Property Disposals item {$row_number} - Submission Reference", $entry['ppdSubmissionId']);
                }

                // completion date required
                if ($entry['completionDate'] === "" || $entry['completionDate'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Completion Date is required");
                } else {
                    self::validateDate("Single Property Disposals item {$row_number} - Completion Date", $entry['completionDate']);
                }

                // acquisition date optioal
                self::validateDate("Single Property Disposals item {$row_number} - Acquisition Date", $entry['acquisitionDate']);

                // disposal proceeds, acquisition amount, improvement costs, additional costs, relief amount, other relief amount required

                if ($entry['disposalProceeds'] === "" || $entry['disposalProceeds'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Disposal Proceeds is required");
                }

                if ($entry['acquisitionAmount'] === "" || $entry['acquisitionAmount'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Acquisition Amount is required");
                }

                if ($entry['improvementCosts'] === "" || $entry['improvementCosts'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Improvement Costs is required");
                }

                if ($entry['additionalCosts'] === "" || $entry['additionalCosts'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Additional Costs is required");
                }

                if ($entry['prfAmount'] === "" || $entry['prfAmount'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Residence Relief Amount is required");
                }

                if ($entry['otherReliefAmount'] === "" || $entry['otherReliefAmount'] === null) {
                    self::saveError("Single Property Disposals item $row_number - Other Relief Amount is required");
                }

                // number amounts
                foreach (['disposalProceeds', 'acquisitionAmount', 'improvementCosts', 'additionalCosts', 'prfAmount', 'otherReliefAmount', 'lossesFromThisYear', 'lossesFromPreviousYear', 'amountOfNetGain', 'amountOfNetLoss'] as $reference) {

                    $formatted_ref = Helper::formatCamelCase($reference);

                    if (!empty($reported_disposals['singlePropertyDisposals'][$key][$reference])) {
                        $reported_disposals['singlePropertyDisposals'][$key][$reference] = self::validateFloat("Single Property Disposals item {$row_number} - {$formatted_ref}", $entry[$reference]);
                    }
                }

                // either gain or loss is required but not both
                if (!empty($entry['amountOfNetGain']) && !empty($entry['amountOfNetLoss'])) {
                    self::saveError("Single Property Disposals item $row_number - Either Gain or Loss must be entered, not both.");
                }
                if (empty($entry['amountOfNetGain']) && empty($entry['amountOfNetLoss'])) {
                    self::saveError("Single Property Disposals item $row_number - Either Gain or Loss must be entered.");
                }
            }
        }


        $reported_disposals = self::removeEmptyValues($reported_disposals);

        return $reported_disposals;
    }

    public static function validateAndFormatOtherCapitalGains(array $other_capital_gains): array
    {
        // var_dump($other_capital_gains);
        // exit;

        // Remove completely empty rows first
        $other_capital_gains['disposals'] = self::removeEmptyRows($other_capital_gains['disposals'] ?? []);
        $other_capital_gains['nonStandardGains'] = self::removeEmptyRows($other_capital_gains['nonStandardGains'] ?? []);
        if (($other_capital_gains['adjustments'] === "")) unset($other_capital_gains['adjustments']);

        // If all sections are now empty, bail early
        if (
            empty($other_capital_gains['disposals']) &&
            empty($other_capital_gains['nonStandardGains']) &&
            empty($other_capital_gains['adjustments'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $other_capital_gains;
        }

        // DISPOSALS

        if (!empty($other_capital_gains['disposals'])) {
            foreach ($other_capital_gains['disposals'] as $key => $entry) {

                $row_number = $key + 1;

                // asset type required
                if (!in_array($entry['assetType'], ['other-property', 'unlisted-shares', 'listed-shares', 'other-asset'])) {
                    self::saveError("Disposals #$row_number - please select Asset Type from the drop-down list");
                }

                // description required
                if ($entry['assetDescription'] === "" || $entry['assetDescription'] === null) {
                    self::saveError("Disposals #$row_number - Description Of Asset is required");
                } else {
                    self::validateReference("Disposals #{$row_number} - Asset Description", $entry['assetDescription']);
                }

                // acquisition and disposal dates required
                if ($entry['acquisitionDate'] === "" || $entry['acquisitionDate'] === null) {
                    self::saveError("Disposals #$row_number - Acquisition Date is required");
                } else {
                    self::validateDate("Disposals #{$row_number} - Acquisition Date", $entry['acquisitionDate']);
                }

                if ($entry['disposalDate'] === "" || $entry['disposalDate'] === null) {
                    self::saveError("Disposals #$row_number - Disposal Date is required");
                } else {
                    self::validateDate("Disposals #{$row_number} - Disposal Date", $entry['disposalDate']);
                }

                // disposal proceeds and allowable costs required
                if ($entry['disposalProceeds'] === "" || $entry['disposalProceeds'] === null) {
                    self::saveError("Disposals #$row_number - Disposal Proceeds is required");
                }
                if ($entry['allowableCosts'] === "" || $entry['allowableCosts'] === null) {
                    self::saveError("Disposals #$row_number - Allowable Costs is required");
                }

                // number fields
                foreach (['disposalProceeds', 'allowableCosts', 'gain', 'loss', 'gainAfterRelief', 'lossAfterRelief', 'rttTaxPaid'] as $reference) {

                    $formatted_ref = Helper::formatCamelCase($reference);

                    if (!empty($other_capital_gains['disposals'][$key][$reference])) {
                        $other_capital_gains['disposals'][$key][$reference] = self::validateFloat("Disposals #{$row_number} - {$formatted_ref}", $entry[$reference]);
                    }
                }
            }
        }

        // NON STANDARD GAINS
        if (!empty($other_capital_gains['nonStandardGains'])) {

            $entry = $other_capital_gains['nonStandardGains'];

            if (!empty($entry['carriedInterestRttTaxPaid']) && empty($entry['carriedInterestGain'])) {
                self::saveError("Carried Interest Gain is required if Carried Interest Tax Paid is given.");
            }

            if (!empty($entry['attributedGainsRttTaxPaid']) && empty($entry['attributedGains'])) {
                self::saveError("Attributed Gains is required if Attributed Gains Tax Paid is given.");
            }

            if (!empty($entry['otherGainsRttTaxPaid']) && empty($entry['otherGains'])) {
                self::saveError("Other Gains is required if Other Gains Tax Paid is given.");
            }

            foreach (['carriedInterestGain', 'carriedInterestRttTaxPaid', 'attributedGains', 'attributedGainsRttTaxPaid', 'otherGains', 'otherGainsRttTaxPaid'] as $reference) {

                $formatted_ref = Helper::formatCamelCase($reference);

                if (!empty($entry[$reference])) {
                    $other_capital_gains['nonStandardGains'][$reference] = self::validateFloat("Non Standard Gains - {$formatted_ref}", $entry[$reference]);
                }
            }
        }

        // LOSSES

        if (!empty($other_capital_gains['losses'])) {

            // all optional
            foreach (['broughtForwardLossesUsedInCurrentYear', 'setAgainstInYearGains', 'setAgainstInYearGeneralIncome', 'setAgainstEarlierYear'] as $reference) {

                $formatted_ref = Helper::formatCamelCase($reference);

                if (isset($other_capital_gains['losses'][$reference])) {

                    if (!empty($other_capital_gains['losses'][$reference])) {
                        $other_capital_gains['losses'][$reference] = self::validateFloat("Losses - {$formatted_ref}", $other_capital_gains['losses'][$reference]);
                    }
                }
            }
        }

        // ADJUSTMENTS
        if (!empty($other_capital_gains['adjustments'])) {
            $other_capital_gains['adjustments'] = self::validateFloat("Adjustments", $other_capital_gains['adjustments'], -99999999999.99, 99999999999.99);
        }

        $other_capital_gains = self::removeEmptyValues($other_capital_gains);

        return $other_capital_gains;
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

    private static function removeEmptyValues(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = self::removeEmptyValues($value);
                // If the array ends up empty after recursion, remove it entirely
                if ($data[$key] === []) {
                    unset($data[$key]);
                }
            } elseif ($value === '' || $value === null) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    private static function validateDate($field, $date, $format = 'Y-m-d')
    {
        $d = \DateTime::createFromFormat($format, $date);
        if (!($d && $d->format($format) === $date)) {
            self::saveError("{$field} must be a valid date in the format {$format}.");
            return null;
        }
        return $date;
    }

    private static function validateReference($field, $reference)
    {

        // If the reference is not provided or is an empty string
        if (empty($reference)) {
            self::saveError("{$field} - A customer reference is required.");
            return null;
        }

        // Check for maximum length (using mb_strlen for multi-byte character support)
        if (mb_strlen($reference) > 90) {
            self::saveError("{$field} - The customer reference must not exceed 90 characters.");
            return null;
        }

        // Check against the HMRC regular expression
        // The 'u' modifier at the end makes it compatible with UTF-8 characters
        // The regex validates for a specific set of characters and a length of 1 to 90
        $pattern = '/^[0-9a-zA-Z{À-˿’}\- _&`():.\'^]{1,90}$/u';
        if (!preg_match($pattern, $reference)) {
            self::saveError("{$field} - The customer reference contains invalid characters.");
            return null;
        }

        // If all checks pass, return the validated reference
        return $reference;
    }

    private static function validateFloat($field, $number, $min = 0, $max = 99999999999.99)
    {
        if (!is_numeric($number) || $number < $min || $number > $max) {
            self::saveError("{$field} must be a number between 0 and 99999999999.99");
            return null;
        } else {
            return round((float)$number, 2);
        }
    }

    private static function validateSubmissionId($field, $id)
    {

        // If the reference is not provided or is an empty string
        if (empty($id)) {
            self::saveError("{$field} - A submission reference is required.");
            return null;
        }

        // Check against the HMRC regular expression
        // The 'u' modifier at the end makes it compatible with UTF-8 characters
        // The regex validates for a specific set of characters and a length of 1 to 90
        $pattern = '/^[A-Za-z0-9]{12}$/u';
        if (!preg_match($pattern, $id)) {
            self::saveError("{$field} - The submission reference is not valid.");
            return null;
        }

        // If all checks pass, return the validated reference
        return $id;
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}