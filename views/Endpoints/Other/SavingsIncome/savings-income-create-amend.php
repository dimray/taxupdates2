<?php include ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/savings/process-create-amend-savings-income" method="POST" class="generic-form hmrc-connection">

    <h2>Securities</h2>

    <div class="nested-input">
        <label>Tax Deducted
            <input type="number" name="securities[taxTakenOff]" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($securities['taxTakenOff'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label><span>Gross Amount <span class="asterisk">*</span></span>
            <input type="number" name="securities[grossAmount]" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($securities['grossAmount'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Net Amount
            <input type="number" name="securities[netAmount]" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($securities['netAmount'] ?? '') ?>">
        </label>
    </div>

    <?php if (isset($foreign_interest)): ?>

        <hr>

        <h2>Foreign Interest</h2>

        <div id="foreign-interest-container">

            <?php foreach (($foreign_interest ?? []) as $interest): ?>


                <div class="foreign-interest-group field-container" data-group="foreignInterest">

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="countryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($interest['countryCode'] ?? '') === $code ? 'selected' : '' ?>>
                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>


                    <div class="nested-input">
                        <label>Amount Before Tax
                            <input type="number" data-name="amountBeforeTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($interest['amountBeforeTax'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Foreign Tax Taken Off
                            <input type="number" data-name="taxTakenOff" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($interest['taxTakenOff'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>UK Withholding Tax
                            <input type="number" data-name="specialWithholdingTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($interest['specialWithholdingTax'] ?? '') ?>">
                        </label>
                    </div>

                    <label class="inline-checkbox">
                        <input type="checkbox" data-name="foreignTaxCreditRelief" value="1"
                            <?= !empty($interest['foreignTaxCreditRelief']) ? "checked" : "" ?>>
                        <span>Tick If Claiming Foreign Tax Credit Relief</span>
                    </label>

                    <div class="nested-input">
                        <label><span>Taxable Amount<span class="asterisk">*</span></span>
                            <input type="number" data-name="taxableAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($interest['taxableAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>


<p><a class="hmrc-connection" href="/savings/retrieve-savings-income">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>