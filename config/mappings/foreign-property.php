<?php return [
    'cumulative' => [
        'income' => [
            'rentAmount',
            'premiumsOfLeaseGrant',
            'otherPropertyIncome',
            'foreignTaxPaidOrDeducted',
            'specialWithholdingTaxOrUkTaxPaid'
        ],
        'expenses' => [
            'premisesRunningCosts',
            'repairsAndMaintenance',
            'financialCosts',
            'professionalFees',
            'travelCosts',
            'costOfServices',
            'other',
        ],
        'residentialFinance' => [
            'residentialFinancialCost',
            'broughtFwdResidentialFinancialCost'
        ]
    ],
    'annual' => [
        'adjustments' => [
            'privateUseAdjustment',
            'balancingCharge'
        ],
        'allowances' => [
            'annualInvestmentAllowance',
            'costOfReplacingDomesticItems',
            'zeroEmissionsGoodsVehicleAllowance',
            'otherCapitalAllowance',
            'zeroEmissionsCarAllowance',
            'propertyIncomeAllowance'
        ],
        'structuredBuildingAllowance' => [
            'sba_amount',
            'sba_qualifyingDate',
            'sba_qualifyingAmountExpenditure',
            'sba_name',
            'sba_number',
            'sba_postcode'
        ],
    ],
    'bsas' => [
        'income' => [
            'totalRentsReceived',
            'premiumsOfLeaseGrant',
            'otherPropertyIncome'
        ],
        'expenses' => [
            'premisesRunningCosts',
            'repairsAndMaintenance',
            'financialCosts',
            'professionalFees',
            'costOfServices',
            'residentialFinancialCost',
            'other',
            'travelCosts'
        ]
    ]
];
