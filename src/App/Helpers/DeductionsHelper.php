<?php

declare(strict_types=1);

namespace App\Helpers;

use Framework\Controller;

class DeductionsHelper extends Controller
{

    public function validateAndFormatDeductions($deductions)
    {

        $deductions['seafarers'] = $this->removeEmptySections($deductions['seafarers'] ?? []);

        // If empty, bail early
        if (
            empty($deductions['seafarers'])
        ) {
            $this->addError("Enter information before submitting.");
            return $deductions;
        }

        // SEAFARERS
        if (!empty($deductions['seafarers'])) {
            foreach ($deductions['seafarers'] as $key => $entry) {
                $row_number = $key + 1;

                // strings
                if (!empty($entry['customerReference'])) {

                    if (!$this->validateRef($entry['customerReference'])) {
                        $this->addError("Seafarers Deduction #{$row_number} - Reference format is invalid");
                    }
                }

                if (empty($entry['nameOfShip'])) {
                    $this->addError("Seafarers Deduction #{$row_number} - Name Of Ship is required");
                } else {
                    if (!$this->validateRef($entry['nameOfShip'])) {
                        $this->addError("Seafarers Deduction #{$row_number} - Name Of Ship format is invalid");
                    }
                }

                // amount deducted
                if (empty($entry['amountDeducted'])) {
                    $this->addError("Seafarers Deduction #{$row_number} - Amount Deducted is required");
                } else {

                    $validated = $this->validateFloat($entry['amountDeducted']);

                    if ($validated === null) {
                        $this->addError("Seafarers Deduction #{$row_number} - Amount Deducted must be a number between 0 and 99999999999.99");
                    } else {
                        $deductions['seafarers'][$key]['amountDeducted'] = $validated;
                    }
                }

                // dates

                if (empty($entry['fromDate'])) {
                    $this->addError("Seafarers Deduction #{$row_number} - From Date is required");
                } else {

                    $validated = $this->validateDate($entry['fromDate']);

                    if (!$validated) {
                        $this->addError("Seafarers Deduction #{$row_number} - From Date must be a valid date");
                    } else {
                        $deductions['seafarers'][$key]['fromDate'] = $validated;
                    }
                }


                if (empty($entry['toDate'])) {
                    $this->addError("Seafarers Deduction #{$row_number} - To Date is required");
                } else {

                    $validated = $this->validateDate($entry['toDate']);

                    if (!$validated) {
                        $this->addError("Seafarers Deduction #{$row_number} - To Date must be a valid date");
                    } else {
                        $deductions['seafarers'][$key]['toDate'] = $validated;
                    }
                }
            }
        }

        $deductions = $this->removeEmptyValues($deductions);

        return $deductions;
    }

    private function removeEmptySections(array $data): array
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


    private function validateRef(string $ref): bool
    {
        if ($ref === "") {
            return true;
        }
        // Allowed chars and length 1–90
        return (bool) preg_match(
            "/^[0-9a-zA-Z{À-˿’}\- _&`():.'^]{1,90}$/u",
            $ref
        );
    }

    private function validateDate($date, $format = 'Y-m-d')
    {
        if ($date === "") {
            return true;
        }

        $d = \DateTime::createFromFormat($format, $date);
        if (!($d && $d->format($format) === $date)) {
            return false;
        }
        return $date;
    }

    private function validateFloat($number, $min = 0, $max = 99999999999.99)
    {
        if (!is_numeric($number) || $number < $min || $number > $max) {

            return null;
        } else {
            return round((float)$number, 2);
        }
    }

    private function removeEmptyValues(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->removeEmptyValues($value);
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
}
