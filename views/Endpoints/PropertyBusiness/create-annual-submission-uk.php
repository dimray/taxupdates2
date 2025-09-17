<form action="/property-business/process-annual-submission" method="POST" class="generic-form">

    <div>
        <h2>Adjustments</h2>

        <div class="form-input">
            <label for="nonResidentLandlord">Are You A Non Resident Landlord? </label>
            <select name="nonResidentLandlord" id="nonResidentLandlord">
                <option value="false" selected
                    <?= isset($adjustments['nonResidentLandlord']) && $adjustments['nonResidentLandlord'] === 'false' ? 'selected' : '' ?>>
                    No</option>
                <option value="true"
                    <?= isset($adjustments['nonResidentLandlord']) && $adjustments['nonResidentLandlord'] === 'true' ? 'selected' : '' ?>>
                    Yes</option>
            </select>
        </div>

        <div class="form-input">
            <label for="rentARoomClaimed">Have You Claimed the RentARoom Allowance?</label>
            <select name="rentARoomClaimed" id="rentARoomClaimed">
                <option value="false"
                    <?= (isset($rentaroom['rentARoomClaimed']) && $rentaroom['rentARoomClaimed'] === "false") ? 'selected' : '' ?>>
                    No
                </option>
                <option value="true"
                    <?= (isset($rentaroom['rentARoomClaimed']) && $rentaroom['rentARoomClaimed'] === "true") ? 'selected' : '' ?>>
                    Yes
                </option>
            </select>
        </div>

        <div id="jointlyLetContainer" class="hidden">
            <div class="form-input">
                <label for="jointlyLet">Is the RentARoom Property Jointly Let?</label>
                <select name="jointlyLet" id="jointlyLet">
                    <option value="false" <?= isset($rentaroom['jointlyLet'])
                                                && $rentaroom['jointlyLet'] === 'false' ? 'selected' : '' ?>>
                        No</option>
                    <option value="true" <?= isset($rentaroom['jointlyLet'])
                                                && $rentaroom['jointlyLet'] === 'true' ? 'selected' : '' ?>>
                        Yes</option>
                </select>
            </div>
        </div>

        <div class="form-input">
            <label for="balancingCharge">Balancing Charge</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="balancingCharge" id="balancingCharge"
                value="<?= $adjustments['balancingCharge'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="privateUseAdjustment">Private Use Adjustment</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="privateUseAdjustment"
                id="privateUseAdjustment" value="<?= $adjustments['privateUseAdjustment'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="businessPremisesRenovationAllowanceBalancingCharges">BPRA Balancing Charges</label>
            <input type="number" min="0" max="99999999999.99" step="0.01"
                name="businessPremisesRenovationAllowanceBalancingCharges"
                id="businessPremisesRenovationAllowanceBalancingCharges"
                value="<?= $adjustments['businessPremisesRenovationAllowanceBalancingCharges'] ?? '' ?>">
        </div>

        <h2>Allowances</h2>

        <div class="form-input">
            <label for="annualInvestmentAllowance">Annual Investment Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="annualInvestmentAllowance"
                id="annualInvestmentAllowance" value="<?= $allowances['annualInvestmentAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="businessPremisesRenovationAllowance">Business Premises Renovation Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="businessPremisesRenovationAllowance"
                id="businessPremisesRenovationAllowance"
                value="<?= $allowances['businessPremisesRenovationAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="otherCapitalAllowance">Other Capital Allowances</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="otherCapitalAllowance"
                id="otherCapitalAllowance" value="<?= $allowances['otherCapitalAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="costOfReplacingDomesticItems">Cost Of Replacing Domestic Items</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="costOfReplacingDomesticItems"
                id="costOfReplacingDomesticItems" value="<?= $allowances['costOfReplacingDomesticItems'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="zeroEmissionsCarAllowance">Zero Emissions Car Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="zeroEmissionsCarAllowance"
                id="zeroEmissionsCarAllowance" value="<?= $allowances['zeroEmissionsCarAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="propertyIncomeAllowance">Property Income Allowance</label>
            <input type="number" min="0" max="1000" step="0.01" name="propertyIncomeAllowance"
                id="propertyIncomeAllowance" value="<?= $allowances['propertyIncomeAllowance'] ?? '' ?>">
        </div>

        <h3>Structured Building Allowance</h3>

        <div class="form-input">
            <label for="sba_amount">Amount</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" name="sba_amount" id="sba_amount"
                value="<?= $sba['sba_amount'] ?? '' ?>">
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
                value="<?= $esba['esba_amount'] ?? '' ?>" class="money-field allowance">
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


    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>


</form>

<p><a href="/business-details/retrieve-business-details">Cancel</a></p>

<?php $include_rentaroom_toggle_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>