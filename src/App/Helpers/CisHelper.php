<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class CisHelper
{
    public static function formatCreateCisDeductionsArray(array $cis_deductions): array
    {
        if (!Validate::string($cis_deductions['contractorName'])) {
            self::saveError("Contractor Name is required");
        }

        if (!Validate::PayeRef($cis_deductions['employerRef'])) {
            self::saveError("Contractor's Employer Reference is required. It must start with 3 numbers followed by a slash and up to 9 characters");
        }

        $period_data = [];

        foreach ($cis_deductions['cisDeductions'] as $i => $deduction) {
            $amount = Helper::validateAmount($deduction['deductionAmount'] ?? '');

            if ($amount === null) {
                continue;
            }

            $period = [
                'deductionAmount'    => $amount,
                'deductionFromDate'  => $deduction['deductionFromDate'],
                'deductionToDate'    => $deduction['deductionToDate'],
            ];

            $materials = Helper::validateAmount($deduction['costOfMaterials'] ?? '', 0, 99999999999.99);
            if ($materials !== null) {
                $period['costOfMaterials'] = $materials;
            }

            $gross = Helper::validateAmount($deduction['grossAmountPaid'] ?? '', 0, 99999999999.99);
            if ($gross !== null) {
                $period['grossAmountPaid'] = $gross;
            }

            $period_data[] = $period;
        }

        if (empty($period_data)) {
            self::saveError("At least one valid deduction is required");
        }

        $result['fromDate']       = TaxYearHelper::getTaxYearStartDate($_SESSION['tax_year']);
        $result['toDate']         = TaxYearHelper::getTaxYearEndDate($_SESSION['tax_year']);
        $result['contractorName'] = $cis_deductions['contractorName'] ?? '';
        $result['employerRef']    = $cis_deductions['employerRef'] ?? '';
        if (isset($cis_deductions['submissionId'])) {
            $result['submissionId'] = $cis_deductions['submissionId'];
        }
        $result['periodData']     = $period_data;
        return $result;
    }

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
