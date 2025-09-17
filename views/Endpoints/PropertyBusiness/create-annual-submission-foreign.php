<p>Enter adjustment and allowance claims for each country in which you have property. Adjustments should only be
    submitted for countries which have been included in submitted Cumulative
    Updates.</p>
<p>If you need to enter data for more than one country, add countries using the 'Add Another Country' button at the end
    of the form. </p>

<br>


<form action="/property-business/process-annual-submission" method="POST" class="generic-form">

    <div id="foreign-property-annual-submission-container" class="grid">

        <?php foreach ($countries as $country_code => $country): ?>

            <div class="foreign-property-annual-submission-group field-container"
                data-group="foreignPropertyAnnualSubmission">

                <div class="form-input">
                    <label for="countryCode-<?= $country_code ?>">Select Country</label>
                    <select id="countryCode-<?= $country_code ?>" data-name="countryCode" required>
                        <option value="" hidden>Please select a country
                        </option>

                        <?php foreach ($country_codes as $continent => $countries_in_continent): ?>
                            <optgroup label="<?= esc($continent) ?>">
                                <?php foreach ($countries_in_continent as $code => $name): ?>
                                    <option value="<?= esc($code) ?>" <?= $country_code === $code ? 'selected' : '' ?>>
                                        <?= esc($name) ?> - <?= esc($code) ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>


                <h2>Adjustments</h2>

                <div class="form-input">
                    <label for="privateUseAdjustment">Private Use Adjustment</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01" data-name="privateUseAdjustment"
                        id="privateUseAdjustment" value="<?= $country['adjustments']['privateUseAdjustment'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="balancingCharge">Balancing Charge</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01" data-name="balancingCharge"
                        id="balancingCharge" value="<?= $country['adjustments']['balancingCharge'] ?? '' ?>">
                </div>

                <h2>Allowances</h2>

                <div class="form-input">
                    <label for="annualInvestmentAllowance">Annual Investment Allowance</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01" data-name="annualInvestmentAllowance"
                        id="annualInvestmentAllowance"
                        value="<?= $country['allowances']['annualInvestmentAllowance'] ?? '' ?>">
                </div>


                <div class="form-input">
                    <label for="costOfReplacingDomesticItems">Cost Of Replacing Domestic Items</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01" data-name="costOfReplacingDomesticItems"
                        id="costOfReplacingDomesticItems"
                        value="<?= $country['allowances']['costOfReplacingDomesticItems'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="otherCapitalAllowance">Other Capital Allowances</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01" data-name="otherCapitalAllowance"
                        id="otherCapitalAllowance" value="<?= $country['allowances']['otherCapitalAllowance'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="zeroEmissionsCarAllowance">Zero Emissions Car Allowance</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01" data-name="zeroEmissionsCarAllowance"
                        id="zeroEmissionsCarAllowance"
                        value="<?= $country['allowances']['zeroEmissionsCarAllowance'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="propertyIncomeAllowance">Property Income Allowance</label>
                    <input type="number" min="0" max="1000" step="0.01" data-name="propertyIncomeAllowance"
                        id="propertyIncomeAllowance" value="<?= $country['allowances']['propertyIncomeAllowance'] ?? '' ?>">
                </div>


                <h3>Structured Building Allowance</h3>

                <div class="form-input">
                    <label for="sba_amount">Amount</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01" data-name="sba_amount" id="sba_amount"
                        value="<?= $country['sba']['sba_amount'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="sba_qualifyingDate">First Year - Qualifying Date</label>
                    <input type="date" data-name="sba_qualifyingDate" id="sba_qualifyingDate"
                        value="<?= $country['sba']['sba_qualifyingDate'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="sba_qualifyingAmountExpenditure">First Year - Qualifying Amount</label>
                    <input type="number" min="0" max="99999999999.99" step="0.01"
                        data-name="sba_qualifyingAmountExpenditure" id="sba_qualifyingAmountExpenditure"
                        value="<?= $country['sba']['sba_qualifyingAmountExpenditure'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="sba_name">Building Name</label>
                    <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" data-name="sba_name" id="sba_name"
                        value="<?= $country['sba']['sba_name'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="sba_number">Building Number</label>
                    <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" data-name="sba_number" id="sba_number"
                        value="<?= $country['sba']['sba_number'] ?? '' ?>">
                </div>

                <div class="form-input">
                    <label for="sba_postcode">Postcode</label>
                    <input type="text" pattern="[a-zA-Z0-9 ]{1,90}" data-name="sba_postcode" id="sba_postcode"
                        value="<?= $country['sba']['sba_postcode'] ?? '' ?>">
                </div>

                <br>

            </div>


        <?php endforeach; ?>

    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>

</form>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>