<p>Enter adjustment and allowance claims for each country in which you have property. Adjustments should only be
    submitted for countries which have been included in submitted Cumulative
    Updates.</p>
<p>If you need to enter claims for more than one country, submit your data for one country and you
    will then be given the option to add other countries.</p>

<form action="/property-business/process-annual-submission" method="POST" class="generic-form">

    <div>
        <?php include ROOT_PATH . "views/shared/select-country.php"; ?>


        <h2>Adjustments</h2>

        <div class="form-input">
            <label for="privateUseAdjustment">Private Use Adjustment</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="privateUseAdjustment"
                name="privateUseAdjustment" value="<?= $country_data['adjustments']['privateUseAdjustment'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="balancingCharge">Balancing Charge</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="balancingCharge" name="balancingCharge"
                value="<?= $country_data['adjustments']['balancingCharge'] ?? '' ?>">
        </div>

        <h2>Allowances</h2>

        <div class="form-input">
            <label for="annualInvestmentAllowance">Annual Investment Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="annualInvestmentAllowance"
                name="annualInvestmentAllowance"
                value="<?= $country_data['allowances']['annualInvestmentAllowance'] ?? '' ?>">
        </div>


        <div class="form-input">
            <label for="costOfReplacingDomesticItems">Cost Of Replacing Domestic Items</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="costOfReplacingDomesticItems"
                name="costOfReplacingDomesticItems"
                value="<?= $country_data['allowances']['costOfReplacingDomesticItems'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="otherCapitalAllowance">Other Capital Allowances</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="otherCapitalAllowance"
                name="otherCapitalAllowance" value="<?= $country_data['allowances']['otherCapitalAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="zeroEmissionsCarAllowance">Zero Emissions Car Allowance</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="zeroEmissionsCarAllowance"
                name="zeroEmissionsCarAllowance"
                value="<?= $country_data['allowances']['zeroEmissionsCarAllowance'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="propertyIncomeAllowance">Property Income Allowance</label>
            <input type="number" min="0" max="1000" step="0.01" id="propertyIncomeAllowance"
                name="propertyIncomeAllowance"
                value="<?= $country_data['allowances']['propertyIncomeAllowance'] ?? '' ?>">
        </div>


        <h3>Structured Building Allowance</h3>

        <div class="form-input">
            <label for="sba_amount">Amount</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="sba_amount" name="sba_amount"
                value="<?= $country_data['sba']['sba_amount'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_qualifyingDate">First Year - Qualifying Date</label>
            <input type="date" id="sba_qualifyingDate" name="sba_qualifyingDate"
                value="<?= $country_data['sba']['sba_qualifyingDate'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_qualifyingAmountExpenditure">First Year - Qualifying Amount</label>
            <input type="number" min="0" max="99999999999.99" step="0.01" id="sba_qualifyingAmountExpenditure"
                name="sba_qualifyingAmountExpenditure"
                value="<?= $country_data['sba']['sba_qualifyingAmountExpenditure'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_name">Building Name</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" id="sba_name" name="sba_name"
                value="<?= $country_data['sba']['sba_name'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_number">Building Number</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" id="sba_number" name="sba_number"
                value="<?= $country_data['sba']['sba_number'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="sba_postcode">Postcode</label>
            <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" id="sba_postcode" name="sba_postcode"
                value="<?= $country_data['sba']['sba_postcode'] ?? '' ?>">
        </div>

    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>

</form>


<?php $include_scroll_to_errors_script = true; ?>