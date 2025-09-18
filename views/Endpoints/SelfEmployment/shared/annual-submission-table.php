<table class="number-table ">



    <tbody>


        <tr>
            <th colspan="2" class="subheading">Adjustments</th>
        </tr>

        <tr>
            <td>Included Non Taxable Profit</td>
            <td><?= esc(formatNumber($adjustments['includedNonTaxableProfits'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Basis Period Adjustment</td>
            <td><?= esc(formatNumber($adjustments['basisAdjustment'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Overlap Relief Used</td>
            <td><?= esc(formatNumber($adjustments['overlapReliefUsed'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Accounting Adjustment</td>
            <td><?= esc(formatNumber($adjustments['accountingAdjustment'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Averaging Adjustment</td>
            <td><?= esc(formatNumber($adjustments['averagingAdjustment'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Outstanding Business Income</td>
            <td><?= esc(formatNumber($adjustments['outstandingBusinessIncome'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>BPRA Balancing Charge</td>
            <td><?= esc(formatNumber($adjustments['balancingChargeBpra'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Other Balancing Charges</td>
            <td><?= esc(formatNumber($adjustments['balancingChargeOther'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Goods And Services Used</td>
            <td><?= esc(formatNumber($adjustments['goodsAndServicesOwnUse'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Transition Profit</td>
            <td><?= esc(formatNumber($adjustments['transitionProfitAmount'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Accelerated Transition Profit</td>
            <td><?= esc(formatNumber($adjustments['transitionProfitAccelerationAmount'] ?? '-')) ?></td>
        </tr>


        <tr>
            <th colspan="2" class="subheading">Allowances</th>
        </tr>

        <tr>
            <td>Annual Investment Allowance</td>
            <td><?= esc(formatNumber($allowances['annualInvestmentAllowance'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Main Rate Capital Allowance Pool</td>
            <td><?= esc(formatNumber($allowances['capitalAllowanceMainPool'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Special Rate Capital Allowance Pool</td>
            <td><?= esc(formatNumber($allowances['capitalAllowanceSpecialRatePool'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Business Premises Renovation Allowance</td>
            <td><?= esc(formatNumber($allowances['businessPremisesRenovationAllowance'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Enhanced Capital Allowances</td>
            <td><?= esc(formatNumber($allowances['enhancedCapitalAllowance'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Capital Allowances On Sales</td>
            <td><?= esc(formatNumber($allowances['allowanceOnSales'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Capital Allowances Single Asset Pools</td>
            <td><?= esc(formatNumber($allowances['capitalAllowanceSingleAssetPool'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Zero Emissions Car Allowance</td>
            <td><?= esc(formatNumber($allowances['zeroEmissionsCarAllowance'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Trading Income Allowance</td>
            <td><?= esc(formatNumber($allowances['tradingIncomeAllowance'] ?? '-')) ?></td>
        </tr>


        <tr>
            <th colspan="2" class="subheading">Structured Building Allowance</th>
        </tr>

        <tr>
            <td>Amount</td>
            <td><?= esc(formatNumber($sba['sba_amount'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>First Year - Qualifying Date</td>
            <td><?= esc(formatDate($sba['sba_qualifyingDate'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>First Year - Qualifying Amount</td>
            <td><?= esc(formatNumber($sba['sba_qualifyingAmountExpenditure'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Building Name</td>
            <td><?= esc($sba['sba_name'] ?? '-') ?></td>
        </tr>

        <tr>
            <td>Building Number</td>
            <td><?= esc($sba['sba_number'] ?? '-') ?></td>
        </tr>

        <tr>
            <td>Postcode</td>
            <td><?= esc($sba['sba_postcode'] ?? '-') ?></td>
        </tr>



        <tr>
            <th colspan="2" class="subheading">Enhanced Structured Building Allowance</th>
        </tr>

        <tr>
            <td>Amount</td>
            <td><?= esc(formatNumber($esba['esba_amount'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>First Year - Qualifying Date</td>
            <td><?= esc(formatDate($esba['esba_qualifyingDate'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>First Year - Qualifying Amount</td>
            <td><?= esc(formatNumber($esba['esba_qualifyingAmountExpenditure'] ?? '-')) ?></td>
        </tr>

        <tr>
            <td>Building Name</td>
            <td><?= esc($esba['esba_name'] ?? '-') ?></td>
        </tr>

        <tr>
            <td>Building Number</td>
            <td><?= esc($esba['esba_number'] ?? '-') ?></td>
        </tr>

        <tr>
            <td>Postcode</td>
            <td><?= esc($esba['esba_postcode'] ?? '-') ?></td>
        </tr>


        <tr>
            <th colspan="2" class="subheading">Other</th>
        </tr>

        <tr>
            <td>Business Details Changed</td>
            <td>
                <?= (
                    ($non_financials['businessDetailsChangedRecently'] ?? "false") === true ||
                    ($non_financials['businessDetailsChangedRecently'] ?? "false") === 1 ||
                    strtolower($non_financials['businessDetailsChangedRecently'] ?? "false") === 'true'
                ) ? 'true' : 'false' ?>
            </td>
        </tr>

        <tr>
            <td>Class 4 National Insurance Exemption</td>
            <td><?= esc($non_financials['class4NicsExemptionReason'] ?? 'none') ?></td>
        </tr>

    </tbody>



</table>