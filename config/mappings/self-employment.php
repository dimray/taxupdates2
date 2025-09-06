<?php

return [
    'cumulative' => [
        'periodIncome' => [
            'turnover',
            'other',
            'taxTakenOffTradingIncome'
        ],
        'periodExpenses' => [
            'costOfGoods',
            'paymentsToSubcontractors',
            'wagesAndStaffCosts',
            'carVanTravelExpenses',
            'premisesRunningCosts',
            'maintenanceCosts',
            'adminCosts',
            'businessEntertainmentCosts',
            'advertisingCosts',
            'interestOnBankOtherLoans',
            'financeCharges',
            'irrecoverableDebts',
            'professionalFees',
            'depreciation',
            'otherExpenses'
        ],
        'periodDisallowableExpenses' => [
            'costOfGoodsDisallowable',
            'paymentsToSubcontractorsDisallowable',
            'wagesAndStaffCostsDisallowable',
            'carVanTravelExpensesDisallowable',
            'premisesRunningCostsDisallowable',
            'maintenanceCostsDisallowable',
            'adminCostsDisallowable',
            'businessEntertainmentCostsDisallowable',
            'advertisingCostsDisallowable',
            'interestOnBankOtherLoansDisallowable',
            'financeChargesDisallowable',
            'irrecoverableDebtsDisallowable',
            'professionalFeesDisallowable',
            'depreciationDisallowable',
            'otherExpensesDisallowable'
        ]
    ],
    'annual' => [
        'adjustments' => [
            'includedNonTaxableProfits',
            'basisAdjustment',
            'overlapReliefUsed',
            'accountingAdjustment',
            'averagingAdjustment',
            'outstandingBusinessIncome',
            'balancingChargeBpra',
            'balancingChargeOther',
            'goodsAndServicesOwnUse',
            'transitionProfitAmount',
            'transitionProfitAccelerationAmount'
        ],
        'allowances' => [
            'annualInvestmentAllowance',
            'capitalAllowanceMainPool',
            'capitalAllowanceSpecialRatePool',
            'zeroEmissionsGoodsVehicleAllowance',
            'businessPremisesRenovationAllowance',
            'enhancedCapitalAllowance',
            'allowanceOnSales',
            'capitalAllowanceSingleAssetPool',
            'zeroEmissionsCarAllowance',
            'tradingIncomeAllowance'
        ],
        'structuredBuildingAllowance' => [
            'sba_amount',
            'sba_qualifyingDate',
            'sba_qualifyingAmountExpenditure',
            'sba_name',
            'sba_number',
            'sba_postcode'
        ],
        'enhancedStructuredBuildingAllowance' => [
            'esba_amount',
            'esba_qualifyingDate',
            'esba_qualifyingAmountExpenditure',
            'esba_name',
            'esba_number',
            'esba_postcode'
        ],
        'nonFinancials' => [
            'businessDetailsChangedRecently',
            'class4NicsExemptionReason'
        ]
    ],
    'bsas' => [
        'income' => [
            'turnover',
            'other'
        ],
        'expenses' => [
            'costOfGoods',
            'paymentsToSubcontractors',
            'wagesAndStaffCosts',
            'carVanTravelExpenses',
            'premisesRunningCosts',
            'maintenanceCosts',
            'adminCosts',
            'interestOnBankOtherLoans',
            'financeCharges',
            'irrecoverableDebts',
            'professionalFees',
            'depreciation',
            'otherExpenses',
            'advertisingCosts',
            'businessEntertainmentCosts',
        ],
        'additions' => [
            'costOfGoodsDisallowable',
            'paymentsToSubcontractorsDisallowable',
            'wagesAndStaffCostsDisallowable',
            'carVanTravelExpensesDisallowable',
            'premisesRunningCostsDisallowable',
            'maintenanceCostsDisallowable',
            'adminCostsDisallowable',
            'interestOnBankOtherLoansDisallowable',
            'financeChargesDisallowable',
            'irrecoverableDebtsDisallowable',
            'professionalFeesDisallowable',
            'depreciationDisallowable',
            'otherExpensesDisallowable',
            'advertisingCostsDisallowable',
            'businessEntertainmentCostsDisallowable',
        ]
    ],
];
