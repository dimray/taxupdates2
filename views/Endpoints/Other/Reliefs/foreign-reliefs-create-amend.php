<?php include ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/reliefs/process-create-and-amend-foreign-reliefs" method="POST" class="generic-form">

    <?php if (isset($foreign_tax_credit_relief)): ?>

        <h2>Foreign Tax Credit Relief</h2>

        <div class="nested-input">
            <label><span>Amount <span class="asterisk">*</span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01" name="foreignTaxCreditRelief[amount]"
                    value="<?= esc($foreign_tax_credit_relief['amount'] ?? '') ?>">
            </label>
        </div>

        <hr>

    <?php endif; ?>

    <?php if (isset($foreign_income_tax_credit_relief)): ?>

        <h2>Foreign Tax Credit Relief Details</h2>

        <div id="foreign-income-tax-credit-relief-container">

            <?php foreach (($foreign_income_tax_credit_relief ?? []) as $ftcr): ?>

                <div class="foreign-income-tax-credit-relief-group field-container" data-group="foreignIncomeTaxCreditRelief">

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="countryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($ftcr['countryCode'] ?? '') === $code ? 'selected' : '' ?>>
                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Foreign Tax Paid
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="foreignTaxPaid"
                                value="<?= esc($ftcr['foreignTaxPaid'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Taxable Amount <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="taxableAmount"
                                value="<?= esc($ftcr['taxableAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <label class="checkbox-flex label-text">
                        <input type="checkbox" data-name="employmentLumpSum" value="1"
                            <?= !empty($ftcr['employmentLumpSum']) ? "checked" : "" ?>>
                        <span>Tick If Claim Relates To An Employment Lump Sum</span>
                    </label>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>



    <?php if (isset($ftcr_not_claimed)): ?>

        <h2>Foreign Tax Credit Relief Not Claimed</h2>

        <div class="nested-input">
            <label><span>Amount <span class="asterisk"></span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01" name="foreignTaxForFtcrNotClaimed[amount]"
                    value="<?= esc($ftcr_not_claimed['amount'] ?? '') ?>">
            </label>
        </div>

    <?php endif; ?>


    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>



<p><a href="/reliefs/retrieve-foreign-reliefs">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>