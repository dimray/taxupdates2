<form class="generic-form" action="/self-employment/process-annual-submission" method="POST">

    <div>

        <h2>Adjustments</h2>

        <div class="form-input">
            <label for="includedNonTaxableProfits">Included Non Taxable Profit</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="includedNonTaxableProfits"
                id="includedNonTaxableProfits" class="money-field"
                value="<?= $adjustments['includedNonTaxableProfits'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="basisAdjustment">Basis Period Adjustment</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="basisAdjustment"
                id="basisAdjustment" class="money-field" value="<?= $adjustments['basisAdjustment'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="overlapReliefUsed">Overlap Relief Used</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="overlapReliefUsed"
                id="overlapReliefUsed" class="money-field" value="<?= $adjustments['overlapReliefUsed'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="accountingAdjustment">Accounting Adjustment</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="accountingAdjustment"
                id="accountingAdjustment" class="money-field" value="<?= $adjustments['accountingAdjustment'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="averagingAdjustment">Averaging Adjustment</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="averagingAdjustment"
                id="averagingAdjustment" class="money-field" value="<?= $adjustments['averagingAdjustment'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="outstandingBusinessIncome">Outstanding Business Income</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="outstandingBusinessIncome"
                id="outstandingBusinessIncome" class="money-field"
                value="<?= $adjustments['outstandingBusinessIncome'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="balancingChargeBpra">BPRA Balancing Charge</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="balancingChargeBpra"
                id="balancingChargeBpra" class="money-field" value="<?= $adjustments['balancingChargeBpra'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="balancingChargeOther">Other Balancing Charges</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="balancingChargeOther"
                id="balancingChargeOther" class="money-field" value="<?= $adjustments['balancingChargeOther'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="goodsAndServicesOwnUse">Goods And Services Used</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="goodsAndServicesOwnUse"
                id="goodsAndServicesOwnUse" class="money-field"
                value="<?= $adjustments['goodsAndServicesOwnUse'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="transitionProfitAmount">Transition Profit</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="transitionProfitAmount"
                id="transitionProfitAmount" class="money-field"
                value="<?= $adjustments['transitionProfitAmount'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="transitionProfitAccelerationAmount">Accelerated Transition Profit</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="transitionProfitAccelerationAmount"
                id="transitionProfitAccelerationAmount" class="money-field"
                value="<?= $adjustments['transitionProfitAccelerationAmount'] ?? '' ?>">
        </div>

        <h2>Allowances</h2>

        <div class="form-input">
            <label for="annualInvestmentAllowance">Annual Investment Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="annualInvestmentAllowance"
                id="annualInvestmentAllowance" class="money-field allowance"
                value="<?= $allowances['annualInvestmentAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="capitalAllowanceMainPool">Main Rate Capital Allowance Pool</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="capitalAllowanceMainPool"
                id="capitalAllowanceMainPool" class="money-field allowance"
                value="<?= $allowances['capitalAllowanceMainPool'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="capitalAllowanceSpecialRatePool">Special Rate Capital Allowance Pool</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="capitalAllowanceSpecialRatePool"
                id="capitalAllowanceSpecialRatePool" class="money-field allowance"
                value="<?= $allowances['capitalAllowanceSpecialRatePool'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="businessPremisesRenovationAllowance">Business Premises Renovation Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="businessPremisesRenovationAllowance"
                id="businessPremisesRenovationAllowance" class="money-field allowance"
                value="<?= $allowances['businessPremisesRenovationAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="enhancedCapitalAllowance">Enhanced Capital Allowances</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="enhancedCapitalAllowance"
                id="enhancedCapitalAllowance" class="money-field allowance"
                value="<?= $allowances['enhancedCapitalAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="allowanceOnSales">Capital Allowances On Sales</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="allowanceOnSales" id="allowanceOnSales"
                class="money-field allowance" value="<?= $allowances['allowanceOnSales'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="capitalAllowanceSingleAssetPool">Capital Allowances Single Asset Pools</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="capitalAllowanceSingleAssetPool"
                id="capitalAllowanceSingleAssetPool" class="money-field allowance"
                value="<?= $allowances['capitalAllowanceSingleAssetPool'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="zeroEmissionsCarAllowance">Zero Emissions Car Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="zeroEmissionsCarAllowance"
                id="zeroEmissionsCarAllowance" class="money-field allowance"
                value="<?= $allowances['zeroEmissionsCarAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="tradingIncomeAllowance">Trading Income Allowance</label>
            <input type="number" min="0" max="1000" step="0.01" name="tradingIncomeAllowance"
                id="tradingIncomeAllowance" class="money-field"
                value="<?= $allowances['tradingIncomeAllowance'] ?? '' ?>">
        </div>

        <h3>Structured Building Allowance</h3>

        <div class="form-input">
            <label for="sba_amount">Amount</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="sba_amount" id="sba_amount"
                value="<?= $sba['sba_amount'] ?? '' ?>" class="money-field allowance">
        </div>

        <div class="form-input">
            <label for="sba_qualifyingDate">First Year - Qualifying Date</label>
            <input type="date" name="sba_qualifyingDate" id="sba_qualifyingDate"
                value="<?= $sba['sba_qualifyingDate'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_qualifyingAmountExpenditure">First Year - Qualifying Amount</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="sba_qualifyingAmountExpenditure"
                id="sba_qualifyingAmountExpenditure" value="<?= $sba['sba_qualifyingAmountExpenditure'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_name">Building Name</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" name="sba_name" id="sba_name"
                value="<?= $sba['sba_name'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_number">Building Number</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" name="sba_number" id="sba_number"
                value="<?= $sba['sba_number'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_postcode">Postcode</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" name="sba_postcode" id="sba_postcode"
                value="<?= $sba['sba_postcode'] ?? '' ?>">
        </div>

        <h3>Enhanced Structured Building Allowance</h3>

        <div class="form-input">
            <label for="esba_amount">Amount</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="esba_amount" id="esba_amount"
                value="<?= $esba['esba_amount'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="esba_qualifyingDate">First Year - Qualifying Date</label>
            <input type="date" name="esba_qualifyingDate" id="esba_qualifyingDate"
                value="<?= $esba['esba_qualifyingDate'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="esba_qualifyingAmountExpenditure">First Year - Qualifying Amount</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="esba_qualifyingAmountExpenditure"
                id="esba_qualifyingAmountExpenditure" value="<?= $esba['esba_qualifyingAmountExpenditure'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="esba_name">Building Name</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" name="esba_name" id="esba_name"
                value="<?= $esba['esba_name'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="esba_number">Building Number</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" name="esba_number" id="esba_number"
                value="<?= $esba['esba_number'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="esba_postcode">Postcode</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" name="esba_postcode" id="esba_postcode"
                value="<?= $esba['esba_postcode'] ?? '' ?>">
        </div>

        <h2>Other</h2>


        <div class="checkbox-flex">
            <input type="checkbox" id="businessDetailsChangedRecently" name="businessDetailsChangedRecently"
                value="true"
                <?= (isset($non_financials['businessDetailsChangedRecently']) && $non_financials['businessDetailsChangedRecently']) ? 'checked' : '' ?>>
            <label for="businessDetailsChangedRecently">Tick if business name, description or address
                changed in the year.</label>
        </div>

        <br>

        <div class="form-input">
            <label for="class4NicsExemptionReason">Class 4 National Insurance Exemption:</label>

            <select name="class4NicsExemptionReason" id="class4NicsExemptionReason">
                <option value="" disabled
                    <?php if (empty($non_financials['class4NicsExemptionReason'])) echo 'selected'; ?>>
                    Select an option
                    if claiming exemption</option>
                <option value="not-exempt">I am not exempt</option>
                <option value="non-resident"
                    <?= isset($non_financials['class4NicsExemptionReason']) && $non_financials['class4NicsExemptionReason'] === 'non-resident' ? 'selected' : '' ?>>
                    Non-resident</option>
                <option value="trustee"
                    <?= isset($non_financials['class4NicsExemptionReason']) && $non_financials['class4NicsExemptionReason'] === 'trustee' ? 'selected' : '' ?>>
                    Trustee</option>
                <option value="diver"
                    <?= isset($non_financials['class4NicsExemptionReason']) && $non_financials['class4NicsExemptionReason'] === 'diver' ? 'selected' : '' ?>>
                    Diver
                </option>
                <option value="ITTOIA-2005"
                    <?= isset($non_financials['class4NicsExemptionReason']) && $non_financials['class4NicsExemptionReason'] === 'ITTOIA-2005' ? 'selected' : '' ?>>
                    ITTOIA
                    2005</option>
                <option value="over-state-pension-age"
                    <?= isset($non_financials['class4NicsExemptionReason']) && $non_financials['class4NicsExemptionReason'] === 'over-state-pension-age' ? 'selected' : '' ?>>
                    Over
                    State Pension Age</option>
                <option value="under-16"
                    <?= isset($non_financials['class4NicsExemptionReason']) && $non_financials['class4NicsExemptionReason'] === 'under-16' ? 'selected' : '' ?>>
                    Under
                    16</option>
            </select>
        </div>




    </div>


    <?php include ROOT_PATH . "/views/shared/errors.php"; ?>


    <button class="form-button" type="submit">Submit</button>

</form>


<p><a class="hmrc-connection" href="/business-details/retrieve-business-details">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>