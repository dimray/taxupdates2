<?php

declare(strict_types=1);

namespace App\Helpers;

class AnnualSubmissionHelper
{

    private const TRADING_ALLOWANCE = 1000;

    public static function validateSelfEmploymentAnnualSubmission(array $data): array
    {
        // dump any empty values
        foreach ($data as $key => $value) {
            if ($value === "") {
                unset($data[$key]);
            }
        }

        $annual_data = SubmissionsHelper::buildArrays($data, "self-employment", "annual");

        $adjustments = $annual_data['adjustments'] ?? [];
        $allowances = $annual_data['allowances'] ?? [];
        $sba = $annual_data['structuredBuildingAllowance'] ?? [];
        $esba = $annual_data['enhancedStructuredBuildingAllowance'] ?? [];
        $non_financials = $annual_data['nonFinancials'] ?? [];

        // sort non_financials before saving to session - no validation needed
        if (isset($non_financials['class4NicsExemptionReason']) && $non_financials['class4NicsExemptionReason'] === "not-exempt") {
            unset($non_financials['class4NicsExemptionReason']);
        }

        if (!empty($non_financials)) {
            $non_financials['businessDetailsChangedRecently'] = $non_financials['businessDetailsChangedRecently'] ?? false;

            $non_financials['businessDetailsChangedRecently'] = filter_var($non_financials['businessDetailsChangedRecently'], FILTER_VALIDATE_BOOL);
        }

        // session holds unvalidated data
        if (!empty($adjustments)) {

            $adjustments = SubmissionsHelper::formatArrayValuesAsFloat($adjustments);

            $_SESSION['annual_submission'][$_SESSION['business_id']]['adjustments'] = $adjustments;
        }

        // save data to session
        if (!empty($allowances)) {
            $allowances = SubmissionsHelper::formatArrayValuesAsFloat($allowances);
            $_SESSION['annual_submission'][$_SESSION['business_id']]['allowances'] = $allowances;
        }

        if (!empty($sba)) {
            if (isset($sba['sba_amount'])) {
                $sba['sba_amount'] = SubmissionsHelper::formatValueAsFloat($sba['sba_amount']);
            }

            if (isset($sba['sba_qualifyingAmountExpenditure'])) {
                $sba['sba_qualifyingAmountExpenditure'] = SubmissionsHelper::formatValueAsFloat($sba['sba_qualifyingAmountExpenditure']);
            }

            $_SESSION['annual_submission'][$_SESSION['business_id']]['sba'] = $sba;
        }

        if (!empty($esba)) {

            if (isset($esba['esba_amount'])) {
                $esba['esba_amount'] = SubmissionsHelper::formatValueAsFloat($esba['esba_amount']);
            }

            if (isset($esba['esba_qualifyingAmountExpenditure'])) {
                $esba['esba_qualifyingAmountExpenditure'] = SubmissionsHelper::formatValueAsFloat($esba['esba_qualifyingAmountExpenditure']);
            }

            $_SESSION['annual_submission'][$_SESSION['business_id']]['esba'] = $esba;
        }

        if (!empty($non_financials)) {
            $_SESSION['annual_submission'][$_SESSION['business_id']]['non_financials'] = $non_financials;
        }

        $errors = [];

        // validate data
        if (!empty($adjustments)) {

            $negative_allowed = ['basisAdjustment', 'averagingAdjustment'];

            foreach ($adjustments as $key => $value) {
                if (!in_array($key, $negative_allowed)) {
                    if (!SubmissionsHelper::validateAmount($value, 0, 99999999999.99)) {
                        $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between 0 and 99999999999.99";
                    }
                } else {
                    if (!SubmissionsHelper::validateAmount($value)) {
                        $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between -99999999999.99 and 99999999999.99";
                    }
                }
            }
        }

        $trading_allowance = "";

        if (isset($allowances['tradingIncomeAllowance'])) {
            $trading_allowance = $allowances['tradingIncomeAllowance'];
            unset($allowances['tradingIncomeAllowance']);
        }

        if (!empty($allowances || !empty($sba) || !empty($esba))) {

            if (!empty($trading_allowance)) {

                $errors[] = "If Trading Income Allowance is claimed, no other allowances can be claimed.";

                if ($trading_allowance > 1000 || $trading_allowance < 0) {
                    $errors[] = "Trading Income Allowance must be between 0 and " . self::TRADING_ALLOWANCE;
                }

                // put it back in the array if it's not empty
                $allowances['tradingIncomeAllowance'] = $trading_allowance;
            } else {
                foreach ($allowances as $key => $value) {
                    if (!SubmissionsHelper::validateAmount($value, 0, 99999999999.99)) {
                        $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between 0 and 99999999999.99";
                    }
                }
            }
        }

        if (!empty($sba)) {

            if (isset($sba['sba_amount'])) {
                $sba_errors =  self::validateBuildingAllowance($sba, "sba_");
                $errors = array_merge($errors, $sba_errors);
            } else {
                // set it to empty if there's no amount
                $sba = [];
            }
        }

        if (!empty($esba)) {

            if (isset($esba['esba_amount'])) {
                $esba_errors =  self::validateBuildingAllowance($esba, "esba_");
                $errors = array_merge($errors, $esba_errors);
            } else {
                // set it to empty if there's no amount
                $sba = [];
            }
        }

        return $errors;
    }

    public static function finaliseSelfEmploymentAnnualSubmission(): array
    {

        $adjustments =  $_SESSION['annual_submission'][$_SESSION['business_id']]['adjustments'] ?? [];
        $allowances =  $_SESSION['annual_submission'][$_SESSION['business_id']]['allowances'] ?? [];
        $sba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['sba'] ?? [];
        $esba =  $_SESSION['annual_submission'][$_SESSION['business_id']]['esba'] ?? [];
        $non_financials =  $_SESSION['annual_submission'][$_SESSION['business_id']]['non_financials'] ?? [];

        $final_data = [];

        if (!empty($sba)) {
            $sba = self::finaliseStructuredBuildingData($sba);
            $allowances['structuredBuildingAllowance'] = [$sba];
        }

        if (!empty($esba)) {
            $esba = self::finaliseStructuredBuildingData($esba);
            $allowances['enhancedStructuredBuildingAllowance'] = [$esba];
        }

        if (!empty($adjustments)) {
            $final_data['adjustments'] = $adjustments;
        }

        if (!empty($allowances)) {
            $final_data['allowances'] = $allowances;
        }

        if (!empty($non_financials)) {
            $final_data['nonFinancials'] = $non_financials;
        }

        return $final_data;
    }

    public static function validatePropertyBusinessAnnualSubmission(array $data): array
    {
        // dump any empty values
        foreach ($data as $key => $value) {
            if ($value === "") {
                unset($data[$key]);
            }
        }

        $property_type = $_SESSION['type_of_business'] === "uk-property" ? "uk" : "foreign";

        if (isset($data['rentARoomClaimed']) && $data['rentARoomClaimed'] == "false") {
            unset($data['jointlyLet']);
        }

        if ($property_type === "uk") {
            $annual_data = SubmissionsHelper::buildArrays($data, "uk-property", "annual");
        } else {
            $annual_data = SubmissionsHelper::buildArrays($data, "foreign-property", "annual");

            if (isset($data['countryCode'])) {
                $annual_data['countryCode'] = $data['countryCode'];
            }
        }

        $country_code = $annual_data['countryCode'] ?? null;
        $adjustments = $annual_data['adjustments'] ?? [];
        $rentaroom = $annual_data['rentARoom'] ?? [];
        $allowances = $annual_data['allowances'] ?? [];
        $sba = $annual_data['structuredBuildingAllowance'] ?? [];
        $esba = $annual_data['enhancedStructuredBuildingAllowance'] ?? [];

        // add data to session
        if (!empty($adjustments)) {

            $adjustments = SubmissionsHelper::formatArrayValuesAsFloat($adjustments);

            if (isset($adjustments['nonResidentLandlord'])) {

                $adjustments['nonResidentLandlord'] = filter_var($adjustments['nonResidentLandlord'], FILTER_VALIDATE_BOOL) ?? false;
            }

            if ($property_type === "foreign" && $country_code) {
                $_SESSION['annual_submission'][$_SESSION['business_id']][$country_code]['adjustments'] = $adjustments;
            } else {
                $_SESSION['annual_submission'][$_SESSION['business_id']]['adjustments'] = $adjustments;
            }
        }

        if (!empty($rentaroom)) {

            $rentaroom['jointlyLet'] = filter_var($rentaroom['jointlyLet'], FILTER_VALIDATE_BOOL);

            $_SESSION['annual_submission'][$_SESSION['business_id']]['rentaroom'] = $rentaroom;
        }

        if (!empty($allowances)) {

            $allowances = SubmissionsHelper::formatArrayValuesAsFloat($allowances);

            if ($property_type === "foreign" && $country_code) {
                $_SESSION['annual_submission'][$_SESSION['business_id']][$country_code]['allowances'] = $allowances;
            } else {
                $_SESSION['annual_submission'][$_SESSION['business_id']]['allowances'] = $allowances;
            }
        }

        if (!empty($sba)) {

            if (isset($sba['sba_amount'])) {
                $sba['sba_amount'] = SubmissionsHelper::formatValueAsFloat($sba['sba_amount']);
            }

            if (isset($sba['sba_qualifyingAmountExpenditure'])) {
                $sba['sba_qualifyingAmountExpenditure'] = SubmissionsHelper::formatValueAsFloat($sba['sba_qualifyingAmountExpenditure']);
            }

            if ($property_type === "foreign" && $country_code) {
                $_SESSION['annual_submission'][$_SESSION['business_id']][$country_code]['sba'] = $sba;
            } else {
                $_SESSION['annual_submission'][$_SESSION['business_id']]['sba'] = $sba;
            }
        }

        if (!empty($esba)) {

            if (isset($esba['esba_amount'])) {
                $esba['esba_amount'] = SubmissionsHelper::formatValueAsFloat($esba['esba_amount']);
            }

            if (isset($esba['esba_qualifyingAmountExpenditure'])) {
                $esba['esba_qualifyingAmountExpenditure'] = SubmissionsHelper::formatValueAsFloat($esba['esba_qualifyingAmountExpenditure']);
            }

            $_SESSION['annual_submission'][$_SESSION['business_id']]['esba'] = $esba;
        }

        // validation
        $errors = [];

        if ($property_type === "foreign" && empty($country_code)) {
            $errors[] = "Country Code is required";
        }

        // adjustments
        if ($property_type === "uk") {

            $nonresident_landlord = $adjustments['nonResidentLandlord'] ?? false;
            unset($adjustments['nonResidentLandlord']);
        }


        if (!empty($adjustments)) {

            foreach ($adjustments as $key => $value) {

                if (!SubmissionsHelper::validateAmount($value, 0, 99999999999.99)) {
                    $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between 0 and 99999999999.99";
                }
            }
        }

        if ($property_type === "uk") {

            $adjustments = $adjustments + ['nonResidentLandlord' => $nonresident_landlord];
        }

        // allowances
        $property_allowance = "";

        if (isset($allowances['propertyIncomeAllowance'])) {
            $property_allowance = $allowances['propertyIncomeAllowance'];
            unset($allowances['propertyIncomeAllowance']);
        }

        if (!empty($allowances) || !empty($sba) || !empty($esba)) {

            if (!empty($property_allowance)) {

                if (!empty($allowances) || !empty($sba) || !empty($esba)) {
                    $errors[] = "If Property Income Allowance is claimed, no other allowances can be claimed.";
                }

                if ($property_allowance > 1000 || $property_allowance < 0) {
                    $errors[] = "Property Income Allowance must be between 0 and 1000.";
                }

                // put it back in the array if it's not empty
                $allowances['propertyIncomeAllowance'] = $property_allowance;
            } elseif (!empty($allowances)) {

                foreach ($allowances as $key => $value) {
                    if (!SubmissionsHelper::validateAmount($value, 0, 99999999999.99)) {
                        $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between 0 and 99999999999.99";
                    }
                }
            }
        }

        if (!empty($sba)) {

            if (isset($sba['sba_amount'])) {
                $sba_errors =  self::validateBuildingAllowance($sba, "sba_");
                $errors = array_merge($errors, $sba_errors);
            } else {
                // set it to empty if there's no amount
                $sba = [];
            }
        }

        if (!empty($esba)) {

            if (isset($esba['esba_amount'])) {
                $esba_errors =  self::validateBuildingAllowance($esba, "esba_");
                $errors = array_merge($errors, $esba_errors);
            } else {
                // set it to empty if there's no amount
                $esba = [];
            }
        }

        return $errors;
    }

    public static function finalisePropertyBusinessAnnualSubmission()
    {

        $business_id = $_SESSION['business_id'];

        $submission = $_SESSION['annual_submission'][$business_id] ?? [];

        $final_data = [];

        $type = $_SESSION['type_of_business'];


        if ($type === "uk-property") {

            $adjustments =  $submission['adjustments'] ?? [];
            $allowances =  $submission['allowances'] ?? [];
            $sba =  $submission['sba'] ?? [];
            $esba =  $submission['esba'] ?? [];
            $rentaroom =  $submission['rentaroom'] ?? [];

            if (!empty($rentaroom)) {
                $adjustments['rentARoom'] = $rentaroom;
            }

            if (!empty($adjustments)) {
                $final_data['adjustments'] = $adjustments;
            }

            if (!empty($sba)) {
                $sba = self::finaliseStructuredBuildingData($sba);
                $allowances['structuredBuildingAllowance'] = [$sba];
            }

            if (!empty($esba)) {
                $esba = self::finaliseStructuredBuildingData($esba);
                $allowances['enhancedStructuredBuildingAllowance'] = [$esba];
            }

            if (!empty($allowances)) {
                $final_data['allowances'] = $allowances;
            }

            $final_data = [
                'ukProperty' => $final_data
            ];
        }

        if ($type === "foreign-property") {

            $foreign_data = [];

            foreach ($submission as $countryCode => $data) {
                $adjustments = $data['adjustments'] ?? [];
                $allowances = $data['allowances'] ?? [];
                $sba = $data['sba'] ?? [];

                $country_block = ['countryCode' => $countryCode];

                if (!empty($adjustments)) {
                    $country_block['adjustments'] = $adjustments;
                }

                if (!empty($sba)) {
                    $allowances['structuredBuildingAllowance'] = [
                        self::finaliseStructuredBuildingData($sba)
                    ];
                }

                if (!empty($allowances)) {
                    $country_block['allowances'] = $allowances;
                }

                $foreign_data[] = $country_block;
            }

            $final_data = [
                'foreignProperty' => $foreign_data
            ];
        }

        return $final_data;
    }

    private static function validateBuildingAllowance(array $data, string $prefix): array
    {
        $errors = [];

        $amount = $data[$prefix . 'amount'] ?? null;
        $qualifying_date = $data[$prefix . 'qualifyingDate'] ?? null;
        $qualifying_amount_expenditure = $data[$prefix . 'qualifyingAmountExpenditure'] ?? null;
        $name = $data[$prefix . 'name'] ?? null;
        $number = $data[$prefix . 'number'] ?? null;
        $postcode = $data[$prefix . 'postcode'] ?? null;

        $allowance_name = $prefix === "sba_" ? "Structured Building Allowance" : "Enhanced Structured Building Allowance";

        $validation_pattern = "^[0-9a-zA-Z{À-˿’}\- _&`():.'^]{1,90}$";

        if (!SubmissionsHelper::validateAmount($amount, 0, 99999999999.99)) {
            $errors[] = $allowance_name . " amount must be between 0 and 99999999999.99";
        }

        // check if first_year data is included:
        $date_present = !empty($qualifying_date);
        $qualifying_exp_present = !empty($qualifying_amount_expenditure);

        if ($date_present xor $qualifying_exp_present) {
            $errors[] = "Both qualifying date and expenditure must be provided in first year of " . $allowance_name;
        }

        if ($date_present && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $qualifying_date)) {
            $errors[] = "Date format is not correct in " . $allowance_name;
        }

        if ($qualifying_exp_present && !SubmissionsHelper::validateAmount($qualifying_amount_expenditure, 0, 99999999999.99)) {
            $errors[] = $allowance_name . " Qualifying Expenses must be between 0 and 99999999999.99";
        }

        // validate building
        $name_present = !empty($name);
        $number_present = !empty($number);
        $postcode_present = !empty($postcode);

        if (!empty($amount)) {
            if (!$postcode_present) {
                $errors[] = "Postcode must be provided if " . $allowance_name . " is claimed";
            }

            if (!$name_present && !$number_present) {
                $errors[] = "Either building name or number must be provided if " . $allowance_name . " is claimed";;
            }

            $building_fields = [
                'name' => $name,
                'number' => $number,
                'postcode' => $postcode,
            ];

            foreach ($building_fields as $key => $value) {
                if (!empty($value) && !preg_match("/$validation_pattern/", $value)) {
                    $errors[] = $allowance_name . SubmissionsHelper::camelCaseToWords($key) . " format is invalid.";
                }
            }
        }

        return $errors;
    }

    private static function finaliseStructuredBuildingData(array $input): array
    {
        $output = [];

        foreach ($input as $key => $value) {
            $parts = explode('_', $key, 2);
            $newKey = $parts[1] ?? $key;

            switch ($newKey) {
                case 'qualifyingDate':
                case 'qualifyingAmountExpenditure':
                    $output['firstYear'][$newKey] = $value;
                    break;

                case 'name':
                case 'number':
                case 'postcode':
                    $output['building'][$newKey] = $value;
                    break;

                case 'amount':
                    $output['amount'] = $value;
                    break;

                default:
                    break;
            }
        }

        return $output;
    }

    // used in retrieve Annual Submission
    public static function flattenSba(array $structuredArray, string $prefix): array
    {
        if (empty($structuredArray[0])) {
            return [];
        }

        $entry = $structuredArray[0];

        return [
            "{$prefix}_amount" => $entry['amount'] ?? null,
            "{$prefix}_qualifyingDate" => $entry['firstYear']['qualifyingDate'] ?? null,
            "{$prefix}_qualifyingAmountExpenditure" => $entry['firstYear']['qualifyingAmountExpenditure'] ?? null,
            "{$prefix}_name" => $entry['building']['name'] ?? null,
            "{$prefix}_number" => $entry['building']['number'] ?? null,
            "{$prefix}_postcode" => $entry['building']['postcode'] ?? null,
        ];
    }

    // used to build foreign property annual submission
    public static function getForeignPropertyAdjustmentFields()
    {
        return [
            'privateUseAdjustment' => [
                'label' => 'Private Use Adjustment',
                'type' => 'number',
                'min' => -99999999999.99,
                'max' => 99999999999.99,
                'step' => 0.01,
            ],
            'balancingCharge' => [
                'label' => 'Balancing Charge',
                'type' => 'number',
                'min' => -99999999999.99,
                'max' => 99999999999.99,
                'step' => 0.01,
            ],
        ];
    }

    // used to build foreign property annual submission
    public static function getForeignPropertyAllowanceFields()
    {
        return [
            'annualInvestmentAllowance' => [
                'label' => 'Annual Investment Allowance',
                'type' => 'number',
                'min' => 0,
                'max' => 99999999999.99,
                'step' => 0.01,
            ],
            'costOfReplacingDomesticItems' => [
                'label' => 'Cost Of Replacing Domestic Items',
                'type' => 'number',
                'min' => 0,
                'max' => 99999999999.99,
                'step' => 0.01,
            ],
            'otherCapitalAllowance' => [
                'label' => 'Other Capital Allowance',
                'type' => 'number',
                'min' => 0,
                'max' => 99999999999.99,
                'step' => 0.01,
            ],
            'zeroEmissionsCarAllowance' => [
                'label' => 'Zero Emissions Car Allowance',
                'type' => 'number',
                'min' => 0,
                'max' => 99999999999.99,
                'step' => 0.01,
            ],
            'propertyIncomeAllowance' => [
                'label' => 'Property Income Allowance',
                'type' => 'number',
                'min' => 0,
                'max' => 1000.00,
                'step' => 0.01,
            ]

        ];
    }

    // used to build foreign property annual submission
    public static function getForeignPropertySbaFields()
    {
        return [
            'sba_amount' => ['label' => 'Amount', 'type' => 'number', 'min' => 0, 'max' => 99999999999.99, 'step' => 0.01],
            'sba_qualifyingDate' => ['label' => 'Qualifying Date', 'type' => 'date'],
            'sba_qualifyingAmountExpenditure' => ['label' => 'Qualifying Expenditure', 'type' => 'number', 'min' => 0, 'max' => 99999999999.99, 'step' => 0.01],
            'sba_name' => ['label' => 'Building Name', 'type' => 'text'],
            'sba_number' => ['label' => 'Building Number', 'type' => 'text'],
            'sba_postcode' => ['label' => 'Postcode', 'type' => 'text'],
        ];
    }
}
