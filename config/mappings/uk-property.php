<?php return [
    'cumulative' => [
        'income' => [
            'premiumsOfLeaseGrant',
            'reversePremiums',
            'periodAmount',
            'taxDeducted',
            'otherIncome'
        ],
        'expenses' => [
            'premisesRunningCosts',
            'repairsAndMaintenance',
            'financialCosts',
            'professionalFees',
            'costOfServices',
            'other',
            'travelCosts'
        ],
        'rentARoom' => [
            'rentARoomRentsReceived',
            'rentARoomAmountClaimed'
        ],
        'residentialFinance' => [
            'residentialFinancialCost',
            'residentialFinancialCostsCarriedForward'
        ],
    ],
    'annual' => [
        'adjustments' => [
            'balancingCharge',
            'privateUseAdjustment',
            'businessPremisesRenovationAllowanceBalancingCharges',
            'nonResidentLandlord'
        ],
        'rentARoom' => [
            'jointlyLet'
        ],
        'allowances' => [
            'annualInvestmentAllowance',
            'businessPremisesRenovationAllowance',
            'otherCapitalAllowance',
            'costOfReplacingDomesticItems',
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
        'enhancedStructuredBuildingAllowance' => [
            'esba_amount',
            'esba_qualifyingDate',
            'esba_qualifyingAmountExpenditure',
            'esba_name',
            'esba_number',
            'esba_postcode'
        ],
    ],
    'bsas' => [
        'income' => [
            'totalRentsReceived',
            'premiumsOfLeaseGrant',
            'reversePremiums',
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
