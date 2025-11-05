<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/foreign-income/process-create-and-amend-foreign-income" method="POST"
    class="generic-form hmrc-connection">


    <?php if (isset($foreign_earnings)): ?>

        <h2>Foreign Earnings Not Taxable In UK</h2>

        <div class="form-input">
            <label for="customerReference">Your Reference</label>
            <input type="text" name="foreignEarnings[customerReference]" id="customerReference"
                value="<?= $foreign_earnings['customerReference'] ?? '' ?>">

        </div>

        <div class="form-input">
            <label for="earningsNotTaxableUk">Amount <span class="asterisk">*</span></label>
            <input type="text" name="foreignEarnings[earningsNotTaxableUK]" id="earningsNotTaxableUk"
                value="<?= $foreign_earnings['earningsNotTaxableUK'] ?? '' ?>">
            <span class="small">GBP amount of foreign income that could not be transferred to the UK because of exchange
                controls</span>
        </div>
        <hr>

    <?php endif; ?>


    <?php if (isset($unremittable_foreign_income)): ?>


        <h2>Unremittable Foreign Income</h2>

        <div id="unremittable-foreign-income-container">

            <?php foreach ($unremittable_foreign_income as $foreign_income): ?>

                <div class="unremittable-foreign-income-group field-container" data-group="unremittableForeignIncome">

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="countryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($foreign_income['countryCode'] ?? '') === $code ? 'selected' : '' ?>>

                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Amount<span class="asterisk">*</span></span>
                            <input type="number" data-name="amountInForeignCurrency" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_income['amountInForeignCurrency'] ?? '' ?>">
                        </label>
                        <span class="small">In local currency of the country the income arises in.</span>
                    </div>

                    <div class="nested-input">
                        <label>Tax Paid
                            <input type="number" data-name="amountTaxPaid" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_income['amountTaxPaid'] ?? '' ?>">
                        </label>
                        <span class="small">In local currency of the country the income arises in.</span>
                    </div>


                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button">Submit</button>
</form>



<p><a class="hmrc-connection" href="/foreign-income/retrieve-foreign-income">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_add_another_script = true; ?>