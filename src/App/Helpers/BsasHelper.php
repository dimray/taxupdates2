<?php

declare(strict_types=1);

namespace App\Helpers;

class BsasHelper
{

    public static function validateBsas(array $data): array
    {

        $business_type = $_SESSION['type_of_business'];

        $bsas_data = SubmissionsHelper::buildArrays($data, $business_type, "bsas");

        // zeros not allowed, empty values not wanted for bsas
        Helper::removeZerosAndEmptyValuesFromArray($bsas_data);

        $income = $bsas_data['income'] ?? [];
        $expenses = $bsas_data['expenses'] ?? [];
        $additions = $bsas_data['additions'] ?? [];

        $errors = [];

        if ($business_type === "foreign-property") {

            if ($_SESSION['tax_year'] === "2025-26") {

                $country_code = $data['country_code'] ?? '';

                if (empty($country_code)) {
                    $errors[] = "Country is required";
                }

                // this is to display the right country's data if there are errors, and is overwritten as each country is saved.
                $_SESSION['bsas'][$_SESSION['business_id']]['countryCode'] = $country_code;

                // this is for the final array if no errors
                $_SESSION['bsas'][$_SESSION['business_id']][$country_code]['countryCode'] = $country_code;

                // save to session
                if (!empty($income)) {
                    $income = SubmissionsHelper::formatArrayValuesAsFloat($income);
                    $_SESSION['bsas'][$_SESSION['business_id']][$country_code]['income'] = $income;
                }

                if (!empty($expenses)) {
                    $expenses = SubmissionsHelper::formatArrayValuesAsFloat($expenses);
                    $_SESSION['bsas'][$_SESSION['business_id']][$country_code]['expenses'] = $expenses;
                }
            } else {

                $property_id = $data['hmrc_property_id'] ?? '';
                if (empty($property_id)) {
                    $errors[] = "Property is required";
                }

                // this is to display the right country's data if there are errors, and is overwritten as each country is saved.
                $_SESSION['bsas'][$_SESSION['business_id']]['propertyId'] = $property_id;

                // this is for the final array if no errors
                $_SESSION['bsas'][$_SESSION['business_id']][$property_id]['propertyId'] = $property_id;

                // save to session
                if (!empty($income)) {
                    $income = SubmissionsHelper::formatArrayValuesAsFloat($income);
                    $_SESSION['bsas'][$_SESSION['business_id']][$property_id]['income'] = $income;
                }

                if (!empty($expenses)) {
                    $expenses = SubmissionsHelper::formatArrayValuesAsFloat($expenses);
                    $_SESSION['bsas'][$_SESSION['business_id']][$property_id]['expenses'] = $expenses;
                }
            }
        } else {

            // save to session
            if (!empty($income)) {
                $income = SubmissionsHelper::formatArrayValuesAsFloat($income);
                $_SESSION['bsas'][$_SESSION['business_id']]['income'] = $income;
            }

            if (!empty($expenses)) {
                $expenses = SubmissionsHelper::formatArrayValuesAsFloat($expenses);
                $_SESSION['bsas'][$_SESSION['business_id']]['expenses'] = $expenses;
            }

            if (!empty($additions)) {
                $additions = SubmissionsHelper::formatArrayValuesAsFloat($additions);
                $_SESSION['bsas'][$_SESSION['business_id']]['additions'] = $additions;
            }
        }

        // validate
        if (!empty($income)) {

            foreach ($income as $key => $value) {

                if (!SubmissionsHelper::validateAmount($value, -99999999999.99, 99999999999.99)) {
                    $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between -99999999999.99 and 99999999999.99";
                }

                if ($value == 0) {
                    $errors[] = SubmissionsHelper::camelCaseToWords($key) . " cannot be 0";
                }
            }
        }

        if (!empty($expenses)) {

            if (isset($expenses['consolidatedExpenses'])) {
                $consolidated_expenses = $expenses['consolidatedExpenses'];
                unset($expenses['consolidatedExpenses']);

                if (!empty($expenses) || !empty($additions)) {
                    $errors[] = "Both consolidated and other expenses cannot be present together";
                }

                $expenses['consolidatedExpenses'] = $consolidated_expenses;
            }

            foreach ($expenses as $key => $value) {

                if ($key === "residentialFinancialCost") {
                    if (!SubmissionsHelper::validateAmount($value, 0, 99999999999.99)) {
                        $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between 0 and 99999999999.99";
                    }
                } elseif (!SubmissionsHelper::validateAmount($value, -99999999999.99, 99999999999.99)) {
                    $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between -99999999999.99 and 99999999999.99";
                }

                if ($value == 0) {
                    $errors[] = SubmissionsHelper::camelCaseToWords($key) . " cannot be 0";
                }
            }
        }

        if (!empty($additions)) {

            foreach ($additions as $key => $value) {

                if (!SubmissionsHelper::validateAmount($value, -99999999999.99, 99999999999.99)) {
                    $errors[] = SubmissionsHelper::camelCaseToWords($key) . " must be between -99999999999.99 and 99999999999.99";
                }

                if ($value == 0) {
                    $errors[] = SubmissionsHelper::camelCaseToWords($key) . " cannot be 0";
                }
            }
        }

        return $errors;
    }
}
