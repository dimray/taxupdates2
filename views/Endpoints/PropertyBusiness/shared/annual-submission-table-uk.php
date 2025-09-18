<table class="number-table">


    <tbody>


        <tr>
            <th colspan="2" class="subheading">Adjustments</th>
        </tr>

        <tr>
            <td>Balancing Charge</td>
            <td><?= esc(formatNumber($adjustments['balancingCharge'] ?? "-")) ?></td>
        </tr>

        <tr>
            <td>Private Use Adjustment</td>
            <td><?= esc(formatNumber($adjustments['privateUseAdjustment'] ?? "-")) ?></td>
        </tr>

        <tr>
            <td>BPRA Balancing Charges</td>
            <td><?= esc(formatNumber($adjustments['businessPremisesRenovationAllowanceBalancingCharges'] ?? "-")) ?>
            </td>
        </tr>

        <tr>
            <td>Non Resident Landlord</td>
            <td><?= isset($adjustments['nonResidentLandlord']) && $adjustments['nonResidentLandlord'] ? 'Yes' : 'No' ?>
            </td>
        </tr>

        <?php if (!empty($rentaroom)): ?>

            <tr>
                <td>Rent-A-Room Jointly Let</td>
                <td><?= isset($rentaroom['jointlyLet']) && $rentaroom['jointlyLet'] ? 'Yes' : 'No' ?></td>
            </tr>

        <?php endif; ?>





        <tr>
            <th colspan="2" class="subheading">Allowances</th>
        </tr>

        <tr>
            <td>Annual Investment Allowance</td>
            <td><?= esc(formatNumber($allowances['annualInvestmentAllowance'] ?? "-")) ?></td>
        </tr>

        <tr>
            <td>Business Premises Renovation Allowance</td>
            <td><?= esc(formatNumber($allowances['businessPremisesRenovationAllowance'] ?? "-")) ?></td>
        </tr>

        <tr>
            <td>Other Capital Allowances</td>
            <td><?= esc(formatNumber($allowances['otherCapitalAllowance'] ?? "-")) ?></td>
        </tr>

        <tr>
            <td>Cost Of Replacing Domestic Items</td>
            <td><?= esc(formatNumber($allowances['costOfReplacingDomesticItems'] ?? "-")) ?></td>
        </tr>

        <tr>
            <td>Zero Emissions Car Allowance</td>
            <td><?= esc(formatNumber($allowances['zeroEmissionsCarAllowance'] ?? "-")) ?></td>
        </tr>

        <tr>
            <td>Property Income Allowance</td>
            <td><?= esc(formatNumber($allowances['propertyIncomeAllowance'] ?? "-")) ?></td>
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


    </tbody>



</table>