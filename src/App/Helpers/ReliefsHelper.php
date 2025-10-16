<?php

declare(strict_types=1);

namespace App\Helpers;

use App\Validate;

class ReliefsHelper
{

    // INVESTMENT RELIEFS

    public static function validateAndFormatInvestmentReliefs(array $investment_reliefs): array
    {
        // Remove completely empty rows first
        $investment_reliefs['vctSubscription'] = self::removeEmptySections($investment_reliefs['vctSubscription'] ?? []);
        $investment_reliefs['eisSubscription'] = self::removeEmptySections($investment_reliefs['eisSubscription'] ?? []);
        $investment_reliefs['communityInvestment'] = self::removeEmptySections($investment_reliefs['communityInvestment'] ?? []);
        $investment_reliefs['seedEnterpriseInvestment'] = self::removeEmptySections($investment_reliefs['seedEnterpriseInvestment'] ?? []);

        // If all sections are now empty, bail early
        if (
            empty($investment_reliefs['vctSubscription']) &&
            empty($investment_reliefs['eisSubscription']) &&
            empty($investment_reliefs['communityInvestment']) &&
            empty($investment_reliefs['seedEnterpriseInvestment'])

        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $investment_reliefs;
        }

        // VCT
        if (!empty($investment_reliefs['vctSubscription'])) {

            foreach ($investment_reliefs['vctSubscription'] as $key => $entry) {

                $row_number = $key + 1;

                if (!empty($entry['uniqueInvestmentRef'])) {

                    if (!self::validateRef($entry['uniqueInvestmentRef'])) {
                        self::saveError("VCT Subscription item {$row_number} - Reference format is invalid");
                    }
                }

                if (!empty($entry['name'])) {

                    if (!self::validateName($entry['name'])) {
                        self::saveError("VCT Subscription item {$row_number} - Name format is invalid");
                    }
                } else {
                    self::saveError("VCT Subscription item {$row_number} - Name Of Investment Or Fund is required");
                }

                if (!empty($entry['dateOfInvestment'])) {

                    if (!self::validateDate($entry['dateOfInvestment'])) {
                        self::saveError("VCT Subscription item {$row_number} - Date must be in the format YYYY-MM-DD");
                    }
                } else {
                    self::saveError("VCT Subscription item {$row_number} - Date Of Investment is required");
                }

                if (!empty($entry['amountInvested'])) {

                    $validated = self::validateFloat($entry['amountInvested']);

                    if ($validated === null) {
                        self::saveError("VCT Subscription item {$row_number} - Amount Invested must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['vctSubscription'][$key]['amountInvested'] = $validated;
                    }
                }

                if (empty($entry['reliefClaimed'])) {
                    self::saveError("VCT Subscription item {$row_number} - Relief Claimed Amount is required");
                } else {

                    $validated = self::validateFloat($entry['reliefClaimed']);

                    if ($validated === null) {
                        self::saveError("VCT Subscription item {$row_number} - Relief Claimed must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['vctSubscription'][$key]['reliefClaimed'] = $validated;
                    }
                }
            }
        }

        // EIS
        if (!empty($investment_reliefs['eisSubscription'])) {

            foreach ($investment_reliefs['eisSubscription'] as $key => $entry) {

                $row_number = $key + 1;

                if (empty($entry['uniqueInvestmentRef'])) {
                    self::saveError("EIS Subscription item {$row_number} - Reference is required");
                } else {

                    if (!self::validateRef($entry['uniqueInvestmentRef'])) {
                        self::saveError("EIS Subscription item {$row_number} - Reference format is invalid");
                    }
                }

                if (!empty($entry['name'])) {

                    if (!self::validateName($entry['name'])) {
                        self::saveError("EIS Subscription item {$row_number} - Name format is invalid");
                    }
                } else {
                    self::saveError("EIS Subscription item {$row_number} - Name Of Investment is required");
                }

                if (!empty($entry['dateOfInvestment'])) {

                    if (!self::validateDate($entry['dateOfInvestment'])) {
                        self::saveError("EIS Subscription item {$row_number} - Date must be in the format YYYY-MM-DD");
                    }
                } else {
                    self::saveError("EIS Subscription item {$row_number} - Date Of Investment is required");
                }

                if (!empty($entry['amountInvested'])) {

                    $validated = self::validateFloat($entry['amountInvested']);

                    if ($validated === null) {
                        self::saveError("EIS Subscription item {$row_number} - Amount Invested must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['eisSubscription'][$key]['amountInvested'] = $validated;
                    }
                }

                if (empty($entry['reliefClaimed'])) {
                    self::saveError("EIS Subscription item {$row_number} - Relief Claimed Amount is required");
                } else {

                    $validated = self::validateFloat($entry['reliefClaimed']);

                    if ($validated === null) {
                        self::saveError("EIS Subscription item {$row_number} - Relief Claimed must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['eisSubscription'][$key]['reliefClaimed'] = $validated;
                    }
                }

                if (isset($entry['knowledgeIntensive'])) {
                    $investment_reliefs['eisSubscription'][$key]['knowledgeIntensive'] = true;
                }
            }
        }

        // COMMUNITY
        if (!empty($investment_reliefs['communityInvestment'])) {

            foreach ($investment_reliefs['communityInvestment'] as $key => $entry) {

                $row_number = $key + 1;

                if (empty($entry['uniqueInvestmentRef'])) {
                    self::saveError("Community Investment Relief item {$row_number} - Reference is required");
                } else {

                    if (!self::validateRef($entry['uniqueInvestmentRef'])) {
                        self::saveError("Community Investment Relief item {$row_number} - Reference format is invalid");
                    }
                }

                if (!empty($entry['name'])) {

                    if (!self::validateName($entry['name'])) {
                        self::saveError("Community Investment Relief item {$row_number} - Name format is invalid");
                    }
                }

                if (!empty($entry['dateOfInvestment'])) {

                    if (!self::validateDate($entry['dateOfInvestment'])) {
                        self::saveError("Community Investment Relief item {$row_number} - Date must be in the format YYYY-MM-DD");
                    }
                }

                if (!empty($entry['amountInvested'])) {

                    $validated = self::validateFloat($entry['amountInvested']);

                    if ($validated === null) {
                        self::saveError("Community Investment item {$row_number} - Amount Invested must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['communityInvestment'][$key]['amountInvested'] = $validated;
                    }
                }

                if (empty($entry['reliefClaimed'])) {
                    self::saveError("Community Investment item {$row_number} - Relief Claimed Amount is required");
                } else {

                    $validated = self::validateFloat($entry['reliefClaimed']);

                    if ($validated === null) {
                        self::saveError("Community Investment item {$row_number} - Relief Claimed must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['communityInvestment'][$key]['reliefClaimed'] = $validated;
                    }
                }
            }
        }

        // SEED EIS
        if (!empty($investment_reliefs['seedEnterpriseInvestment'])) {

            foreach ($investment_reliefs['seedEnterpriseInvestment'] as $key => $entry) {

                $row_number = $key + 1;

                if (empty($entry['uniqueInvestmentRef'])) {
                    self::saveError("Seed EIS item {$row_number} - Reference is required");
                } else {

                    if (!self::validateRef($entry['uniqueInvestmentRef'])) {
                        self::saveError("Seed EIS item {$row_number} - Reference format is invalid");
                    }
                }

                if (!empty($entry['companyName'])) {

                    if (!self::validateName($entry['companyName'])) {
                        self::saveError("Seed EIS item {$row_number} - Name Of Company format is invalid");
                    }
                } else {
                    self::saveError("Seed EIS item {$row_number} - Name Of Company is required");
                }

                if (!empty($entry['dateOfInvestment'])) {

                    if (!self::validateDate($entry['dateOfInvestment'])) {
                        self::saveError("Seed EIS item {$row_number} - Date must be in the format YYYY-MM-DD");
                    }
                } else {
                    self::saveError("Seed EIS item {$row_number} - Date Of Investment is required");
                }

                if (!empty($entry['amountInvested'])) {

                    $validated = self::validateFloat($entry['amountInvested']);

                    if ($validated === null) {
                        self::saveError("Seed EIS item {$row_number} - Amount Invested must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['seedEnterpriseInvestment'][$key]['amountInvested'] = $validated;
                    }
                }

                if (empty($entry['reliefClaimed'])) {
                    self::saveError("Seed EIS item {$row_number} - Relief Claimed amount is required");
                } else {

                    $validated = self::validateFloat($entry['reliefClaimed']);

                    if ($validated === null) {
                        self::saveError("Seed EIS item {$row_number} - Relief Claimed must be a number between 0 and 99999999999.99");
                    } else {
                        $investment_reliefs['seedEnterpriseInvestment'][$key]['reliefClaimed'] = $validated;
                    }
                }
            }
        }

        $investment_reliefs = self::removeEmptyValues($investment_reliefs);

        return $investment_reliefs;
    }

    // OTHER RELIEFS

    public static function validateAndFormatOtherReliefs(array $other_reliefs): array
    {
        // Remove completely empty rows first
        $other_reliefs['nonDeductibleLoanInterest'] = self::removeEmptySections($other_reliefs['nonDeductibleLoanInterest'] ?? []);
        $other_reliefs['payrollGiving'] = self::removeEmptySections($other_reliefs['payrollGiving'] ?? []);
        $other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities'] = self::removeEmptySections($other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities'] ?? []);
        $other_reliefs['maintenancePayments'] = self::removeEmptySections($other_reliefs['maintenancePayments'] ?? []);
        $other_reliefs['postCessationTradeReliefAndCertainOtherLosses'] = self::removeEmptySections($other_reliefs['postCessationTradeReliefAndCertainOtherLosses'] ?? []);
        $other_reliefs['annualPaymentsMade'] = self::removeEmptySections($other_reliefs['annualPaymentsMade'] ?? []);
        $other_reliefs['qualifyingLoanInterestPayments'] = self::removeEmptySections($other_reliefs['qualifyingLoanInterestPayments'] ?? []);

        // If all sections are now empty, bail early
        if (
            empty($other_reliefs['nonDeductibleLoanInterest']) &&
            empty($other_reliefs['payrollGiving']) &&
            empty($other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities']) &&
            empty($other_reliefs['maintenancePayments']) &&
            empty($other_reliefs['postCessationTradeReliefAndCertainOtherLosses']) &&
            empty($other_reliefs['annualPaymentsMade']) &&
            empty($other_reliefs['qualifyingLoanInterestPayments'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $other_reliefs;
        }

        // Non deductible loan interest
        if (!empty($other_reliefs['nonDeductibleLoanInterest'])) {

            if (!self::validateRef($other_reliefs['nonDeductibleLoanInterest']['customerReference'])) {
                self::saveError("Loan Interest - Reference format is invalid");
            }

            if (empty($other_reliefs['nonDeductibleLoanInterest']['reliefClaimed'])) {
                self::saveError("Loan Interest - Amount is required");
            } else {

                $validated = self::validateFloat($other_reliefs['nonDeductibleLoanInterest']['reliefClaimed']);

                if ($validated === null) {
                    self::saveError("Loan Interest - Amount must be a number between 0 and 99999999999.99");
                } else {
                    $other_reliefs['nonDeductibleLoanInterest']['reliefClaimed'] = $validated;
                }
            }
        }

        // payroll giving
        if (!empty($other_reliefs['payrollGiving'])) {

            if (!self::validateRef($other_reliefs['payrollGiving']['customerReference'])) {
                self::saveError("Payroll Giving - Reference format is invalid");
            }

            if (empty($other_reliefs['payrollGiving']['reliefClaimed'])) {
                self::saveError("Payroll Giving - Amount is required");
            } else {

                $validated = self::validateFloat($other_reliefs['payrollGiving']['reliefClaimed']);

                if ($validated === null) {
                    self::saveError("Payroll Giving - Amount must be a number between 0 and 99999999999.99");
                } else {
                    $other_reliefs['payrollGiving']['reliefClaimed'] = $validated;
                }
            }
        }

        // qualifying distributions
        if (!empty($other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities'])) {

            if (!self::validateRef($other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities']['customerReference'])) {
                self::saveError("Qualifying Distributions - Reference format is invalid");
            }

            if (empty($other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities']['amount'])) {
                self::saveError("Qualifying Distributions - Amount is required");
            } else {

                $validated = self::validateFloat($other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities']['amount']);

                if ($validated === null) {
                    self::saveError("Qualifying Distributions - Amount must be a number between 0 and 99999999999.99");
                } else {
                    $other_reliefs['qualifyingDistributionRedemptionOfSharesAndSecurities']['amount'] = $validated;
                }
            }
        }

        // maintenance payments
        if (!empty($other_reliefs['maintenancePayments'])) {

            foreach ($other_reliefs['maintenancePayments'] as $key => $entry) {

                $row_number = $key + 1;

                if (!self::validateRef($entry['customerReference'])) {
                    self::saveError("Maintenance Payments - Reference format is invalid");
                }

                if (empty($entry['amount'])) {
                    self::saveError("Maintenance Payments item {$row_number} - Amount is required");
                } else {

                    $validated = self::validateFloat($entry['amount']);

                    if ($validated === null) {
                        self::saveError("Maintenance Payments item {$row_number} - Amount must be a number between 0 and 99999999999.99");
                    } else {
                        $other_reliefs['maintenancePayments'][$key]['amount'] = $validated;
                    }
                }
            }
        }

        // post cessation trade relief
        if (!empty($other_reliefs['postCessationTradeReliefAndCertainOtherLosses'])) {

            foreach ($other_reliefs['postCessationTradeReliefAndCertainOtherLosses'] as $key => $entry) {

                $row_number = $key + 1;

                if (!self::validateRef($entry['customerReference'])) {
                    self::saveError("Post Cessation Trade Relief item {$row_number} - Reference format is invalid");
                }

                if (!self::validateName($entry['businessName'])) {
                    self::saveError("Post Cessation Trade Relief item {$row_number} - Business Name format is invalid");
                }

                if (!self::validateDate($entry['dateBusinessCeased'])) {
                    self::saveError("Post Cessation Trade Relief item {$row_number} - Date Business Ceased format is invalid");
                }

                if (empty($entry['amount'])) {
                    self::saveError("Post Cessation Trade Relief item {$row_number} - Amount is required");
                } else {

                    $validated = self::validateFloat($entry['amount']);

                    if ($validated === null) {
                        self::saveError("Post Cessation Trade Relief item {$row_number} - Amount must be a number between 0 and 99999999999.99");
                    } else {
                        $other_reliefs['postCessationTradeReliefAndCertainOtherLosses'][$key]['amount'] = $validated;
                    }
                }
            }
        }

        // annual payments
        if (!empty($other_reliefs['annualPaymentsMade'])) {

            if (isset($other_reliefs['annualPaymentsMade']['customerReference'])) {
                if (!self::validateRef($other_reliefs['annualPaymentsMade']['customerReference'])) {
                    self::saveError("Annual Payments - Reference format is invalid");
                }
            }

            if (empty($other_reliefs['annualPaymentsMade']['reliefClaimed'])) {
                self::saveError("Annual Payments - Amount is required");
            } else {

                $validated = self::validateFloat($other_reliefs['annualPaymentsMade']['reliefClaimed']);

                if ($validated === null) {
                    self::saveError("Annual Payments - Amount must be a number between 0 and 99999999999.99");
                } else {
                    $other_reliefs['annualPaymentsMade']['reliefClaimed'] = $validated;
                }
            }
        }

        // qualifying loan interest
        if (!empty($other_reliefs['qualifyingLoanInterestPayments'])) {

            foreach ($other_reliefs['qualifyingLoanInterestPayments'] as $key => $entry) {

                $row_number = $key + 1;

                if (!self::validateRef($entry['customerReference'])) {
                    self::saveError("Qualifying Loan Interest item {$row_number} - Reference format is invalid");
                }

                if (!self::validateName($entry['lenderName'])) {
                    self::saveError("Qualifying Loan Interest item {$row_number} - Lender format is invalid");
                }

                if (empty($entry['reliefClaimed'])) {
                    self::saveError("Qualifying Loan Interest item {$row_number} - Amount is required");
                } else {

                    $validated = self::validateFloat($entry['reliefClaimed']);

                    if ($validated === null) {
                        self::saveError("Qualifying Loan Interest item {$row_number} - Amount must be a number between 0 and 99999999999.99");
                    } else {
                        $other_reliefs['qualifyingLoanInterestPayments'][$key]['reliefClaimed'] = $validated;
                    }
                }
            }
        }

        $other_reliefs = self::removeEmptyValues($other_reliefs);

        return $other_reliefs;
    }

    // FOREIGN RELIEFS 

    public static function validateAndFormatForeignReliefs(array $foreign_reliefs): array
    {
        // Remove completely empty rows first
        $foreign_reliefs['foreignTaxCreditRelief'] = self::removeEmptySections($foreign_reliefs['foreignTaxCreditRelief'] ?? []);
        $foreign_reliefs['foreignIncomeTaxCreditRelief'] = self::removeEmptySections($foreign_reliefs['foreignIncomeTaxCreditRelief'] ?? []);
        $foreign_reliefs['foreignTaxForFtcrNotClaimed'] = self::removeEmptySections($foreign_reliefs['foreignTaxForFtcrNotClaimed'] ?? []);

        // If all sections are now empty, bail early
        if (
            empty($foreign_reliefs['foreignTaxCreditRelief']) &&
            empty($foreign_reliefs['foreignIncomeTaxCreditRelief']) &&
            empty($foreign_reliefs['foreignTaxForFtcrNotClaimed'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $foreign_reliefs;
        }

        // foreign tax credit relief

        if (!empty($foreign_reliefs['foreignTaxCreditRelief']['amount'])) {

            $validated = self::validateFloat($foreign_reliefs['foreignTaxCreditRelief']['amount']);

            if ($validated === null) {
                self::saveError("Foreign Tax Credit Relief - Amount must be a number between 0 and 99999999999.99");
            } else {
                $foreign_reliefs['foreignTaxCreditRelief']['amount'] = $validated;
            }
        }

        if (!empty($foreign_reliefs['foreignIncomeTaxCreditRelief'])) {

            foreach ($foreign_reliefs['foreignIncomeTaxCreditRelief'] as $key => $entry) {

                $row_number = $key + 1;

                // country code required
                if (!Validate::countryCode($entry['countryCode'] ?? '')) {
                    self::saveError("Foreign Tax Credit Relief Details item {$row_number} - A valid Country Code is required.");
                }

                // foreignTaxPaid
                if (!empty($entry['foreignTaxPaid'])) {

                    $validated = self::validateFloat($entry['foreignTaxPaid']);

                    if ($validated === null) {
                        self::saveError("Foreign Tax Credit Relief Details item {$row_number} - Foreign Tax Paid must be a number between 0 and 99999999999.99");
                    } else {
                        $foreign_reliefs['foreignIncomeTaxCreditRelief'][$key]['foreignTaxPaid'] = $validated;
                    }
                }

                // taxableAmount
                if (empty($entry['taxableAmount'])) {
                    self::saveError("Foreign Tax Credit Relief Details item {$row_number} - Amount is required");
                } else {

                    $validated = self::validateFloat($entry['taxableAmount']);

                    if ($validated === null) {
                        self::saveError("Foreign Tax Credit Relief Details item {$row_number} - Amount must be a number between 0 and 99999999999.99");
                    } else {
                        $foreign_reliefs['foreignIncomeTaxCreditRelief'][$key]['taxableAmount'] = $validated;
                    }
                }

                // employmentLumpSum
                if (isset($entry['employmentLumpSum'])) {
                    $foreign_reliefs['foreignIncomeTaxCreditRelief'][$key]['employmentLumpSum'] = true;
                } else {
                    $foreign_reliefs['foreignIncomeTaxCreditRelief'][$key]['employmentLumpSum'] = false;
                }
            }
        }

        // foreign tax no ftcr
        if (!empty($foreign_reliefs['foreignTaxForFtcrNotClaimed']['amount'])) {

            $validated = self::validateFloat($foreign_reliefs['foreignTaxForFtcrNotClaimed']['amount']);

            if ($validated === null) {
                self::saveError("Foreign Tax Credit Relief Not Claimed - Amount must be a number between 0 and 99999999999.99");
            } else {
                $foreign_reliefs['foreignTaxForFtcrNotClaimed']['amount'] = $validated;
            }
        }

        $foreign_reliefs = self::removeEmptyValues($foreign_reliefs);

        return $foreign_reliefs;
    }

    // PENSIONS
    public static function validateAndFormatPensionsReliefs(array $pensions_reliefs): array
    {
        // If all sections are empty, bail 
        if (
            empty($pensions_reliefs['pensionReliefs'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $pensions_reliefs;
        } else {

            foreach (
                [
                    'regularPensionContributions' => 'Regular Pension Contributions',
                    'oneOffPensionContributionsPaid' => 'One Off Contributions',
                    'retirementAnnuityPayments' => 'Annuity Payments',
                    'paymentToEmployersSchemeNoTaxRelief' => 'Payments To Employer Scheme',
                    'overseasPensionSchemeContributions' => 'Overseas Contributions'
                ] as $category => $name
            ) {

                if (!empty($pensions_reliefs[$category])) {
                    $validated = self::validateFloat($pensions_reliefs[$category]);

                    if ($validated === null) {
                        self::saveError("$name - Amount must be a number between 0 and 99999999999.99");
                    } else {
                        $pensions_reliefs[$category] = $validated;
                    }
                }
            }
        }

        $pensions_reliefs = self::removeEmptyValues($pensions_reliefs);

        return $pensions_reliefs;
    }

    // CHARITABLE GIVING

    public static function validateAndFormatCharitableGiving($charitable_giving): array
    {
        // Remove completely empty rows first
        $charitable_giving['giftAidPayments'] = self::removeEmptySections($charitable_giving['giftAidPayments'] ?? []);
        $charitable_giving['gifts'] = self::removeEmptySections($charitable_giving['gifts'] ?? []);

        // and remove non-uk charities if empty
        $charitable_giving['giftAidPayments']['nonUkCharities'] = self::removeEmptySections($charitable_giving['giftAidPayments']['nonUkCharities'] ?? []);
        $charitable_giving['gifts']['nonUkCharities'] = self::removeEmptySections($charitable_giving['gifts']['nonUkCharities'] ?? []);


        // If all sections are now empty, bail early
        if (
            empty($charitable_giving['giftAidPayments']) &&
            empty($charitable_giving['gifts'])
        ) {
            self::saveError("Information must be entered in at least one section before submitting.");
            return $charitable_giving;
        }

        // gift aid - non-uk
        if (!empty($charitable_giving['giftAidPayments']['nonUkCharities'])) {

            if (empty($charitable_giving['giftAidPayments']['nonUkCharities']['charityNames'])) {
                self::saveError("Gift Aid - Non-UK Charities: Charity Names are required if Amount is given");
            } else {

                $names = array_map('trim', explode(",", $charitable_giving['giftAidPayments']['nonUkCharities']['charityNames']));

                foreach ($names as $name) {
                    $validated = self::validateCharityNames($name);
                    if (!$validated) {
                        self::saveError("Gift Aid - Non-UK Charities: Charity Names cannot be longer than 75 characters each");
                        break;
                    }
                }

                $charitable_giving['giftAidPayments']['nonUkCharities']['charityNames'] = $names;
            }

            if (empty($charitable_giving['giftAidPayments']['nonUkCharities']['totalAmount'])) {
                self::saveError("Gift Aid - Non-UK Charities: Amount is required if Charity Names are given");
            } else {
                $validated = self::validateFloat($charitable_giving['giftAidPayments']['nonUkCharities']['totalAmount']);

                if ($validated === null) {
                    self::saveError("Gift Aid - Non-UK Charities: Amount format is invalid");
                } else {
                    $charitable_giving['giftAidPayments']['nonUkCharities']['totalAmount'] = $validated;
                }
            }
        }

        // gifts - non-uk
        if (!empty($charitable_giving['gifts']['nonUkCharities'])) {

            if (empty($charitable_giving['gifts']['nonUkCharities']['charityNames'])) {
                self::saveError("Gifts - Non-UK Charities: Charity Names are required if Amount is given");
            } else {

                $names = array_map('trim', explode(",", $charitable_giving['gifts']['nonUkCharities']['charityNames']));

                foreach ($names as $name) {
                    $validated = self::validateCharityNames($name);
                    if (!$validated) {
                        self::saveError("Gifts - Non-UK Charities: Charity Names cannot be longer than 75 characters each");
                        break;
                    }
                }

                $charitable_giving['gifts']['nonUkCharities']['charityNames'] = $names;
            }

            if (empty($charitable_giving['gifts']['nonUkCharities']['totalAmount'])) {
                self::saveError("Gifts - Non-UK Charities: Amount is required if Charity Names are given");
            } else {
                $validated = self::validateFloat($charitable_giving['gifts']['nonUkCharities']['totalAmount']);

                if ($validated === null) {
                    self::saveError("Gifts - Non-UK Charities: Amount format is invalid");
                } else {
                    $charitable_giving['gifts']['nonUkCharities']['totalAmount'] = $validated;
                }
            }
        }

        // gift-aid - uk
        foreach (
            [
                'totalAmount' => 'Total Regular Payments',
                'oneOffAmount' => 'One Off Payments',
                'amountTreatedAsPreviousTaxYear' => 'Payments Treated As Made In Previous Tax Year',
                'amountTreatedAsSpecifiedTaxYear' => 'Payments In Following Tax Year Treated As Made This Tax Year'

            ] as $category => $name
        ) {

            if (!empty($charitable_giving['giftAidPayments'][$category])) {
                $validated = self::validateFloat($charitable_giving['giftAidPayments'][$category]);

                if ($validated === null) {
                    self::saveError("Gift Aid - $name: Amount must be a number between 0 and 99999999999.99");
                } else {
                    $charitable_giving['giftAidPayments'][$category] = $validated;
                }
            }
        }

        // gifts - uk
        foreach (
            [
                'landAndBuildings' => 'Land And Buildings',
                'sharesOrSecurities' => 'Shares And Securities'
            ] as $category => $name
        ) {

            if (!empty($charitable_giving['gifts'][$category])) {
                $validated = self::validateFloat($charitable_giving['gifts'][$category]);

                if ($validated === null) {
                    self::saveError("Gifts - $name: Amount must be a number between 0 and 99999999999.99");
                } else {
                    $charitable_giving['gifts'][$category] = $validated;
                }
            }
        }

        $charitable_giving = self::removeEmptyValues($charitable_giving);

        return $charitable_giving;
    }

    private static function removeEmptySections(array $data): array
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

    private static function validateCharityNames(string $name): bool
    {
        if ($name === "") {
            return true;
        }
        // Allowed chars and length 1–75
        return (bool) preg_match(
            "/^[A-Za-z0-9 &'\(\)\*,\-\.\@£]{1,75}$/u",
            $name
        );
    }

    private static function validateRef(string $ref): bool
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

    private static function validateName(string $name): bool
    {
        if ($name === "") {
            return true;
        }
        // Allowed chars and length 1–105
        return (bool) preg_match(
            "/^[0-9a-zA-Z{À-˿'\- _&`():.'^]{1,105}$/u",
            $name
        );
    }

    private static function validateDate($date, $format = 'Y-m-d')
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

    private static function validateFloat($number, $min = 0, $max = 99999999999.99)
    {
        if (!is_numeric($number) || $number < $min || $number > $max) {

            return null;
        } else {
            return round((float)$number, 2);
        }
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

    private static function saveError(string $message): void
    {
        $_SESSION['errors'] = $_SESSION['errors'] ?? [];
        $_SESSION['errors'][] = $message;
    }
}
