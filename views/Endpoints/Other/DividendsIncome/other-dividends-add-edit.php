<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>


<form class="generic-form" method="POST" action="/dividends-income/process-create-amend-dividends-income">

    <?php if (isset($foreign_dividends)): ?>

        <h2>Foreign Dividends</h2>

        <div id="foreign-dividend-container">

            <?php foreach ($foreign_dividends as $foreign_dividend): ?>

                <div class="foreign-dividend-group field-container" data-group="foreignDividend">

                    <?php $country_code = $foreign_dividend['countryCode'] ?? ''; ?>

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="countryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>" <?= $country_code === $code ? 'selected' : '' ?>>
                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>

                        </label>
                    </div>

                    <div class="nested-input">
                        <label>
                            Amount Before Tax
                            <input type="number" data-name="amountBeforeTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_dividend['amountBeforeTax'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>
                            Foreign Tax
                            <input type="number" data-name="taxTakenOff" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_dividend['taxTakenOff'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>
                            UK Tax Withheld
                            <input type="number" data-name="specialWithholdingTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_dividend['specialWithholdingTax'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>
                            <span>Taxable Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="taxableAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_dividend['taxableAmount'] ?? '' ?>">
                        </label>
                    </div>

                    <div>
                        <label class="checkbox-flex label-text">
                            <input type="checkbox" data-name="foreignTaxCreditRelief" value="1"
                                <?= (isset($foreign_dividend['foreignTaxCreditRelief']) && ($foreign_dividend['foreignTaxCreditRelief'] === true || $foreign_dividend['foreignTaxCreditRelief'] === 'true' || $foreign_dividend['foreignTaxCreditRelief'] === '1')) ? "checked" : "" ?>>
                            <span>Tick If Claiming Foreign Tax Credit Relief</span>
                        </label>
                    </div>
                    <br>



                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($dividends_abroad)): ?>

        <h2>Dividends Received While Abroad</h2>

        <div id="dividend-income-received-whilst-abroad-container">

            <?php foreach ($dividends_abroad as $dividend_abroad): ?>

                <div class="dividend-income-received-whilst-abroad-group field-container"
                    data-group="dividendIncomeReceivedWhilstAbroad">

                    <?php $country_code = $dividend_abroad['countryCode'] ?? ''; ?>

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="countryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>" <?= $country_code === $code ? 'selected' : '' ?>>
                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>


                    <div class="nested-input">
                        <label>
                            Amount Before Tax
                            <input type="number" data-name="amountBeforeTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= $dividend_abroad['amountBeforeTax'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>
                            Foreign Tax
                            <input type="number" data-name="taxTakenOff" min="0" max="99999999999.99" step="0.01"
                                value="<?= $dividend_abroad['taxTakenOff'] ?? '' ?>">
                        </label>
                    </div>

                    <div class=" nested-input">
                        <label>
                            UK Tax Withheld
                            <input type="number" data-name="specialWithholdingTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= $dividend_abroad['specialWithholdingTax'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>
                            <span>Taxable Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="taxableAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= $dividend_abroad['taxableAmount'] ?? '' ?>">
                        </label>
                    </div>

                    <div>
                        <label class="inline-checkbox label-text">
                            <input type="checkbox" data-name="foreignTaxCreditRelief" value="1"
                                <?= (isset($dividend_abroad['foreignTaxCreditRelief']) && ($dividend_abroad['foreignTaxCreditRelief'] === true || $dividend_abroad['foreignTaxCreditRelief'] === 'true' || $foreign_dividend['foreignTaxCreditRelief'] === '1')) ? "checked" : "" ?>>
                            <span>Tick If Claiming Foreign Tax Credit Relief</span>
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($stock_dividend)): ?>

        <h2>Stock Dividends</h2>

        <div class="nested-input">
            <label>
                <span>Your Reference <span class="asterisk">*</span></span>
                <input type="text" name="stockDividend[customerReference]" maxlength="90"
                    value="<?= $stock_dividend['customerReference'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>
                Gross Amount
                <input type="number" name="stockDividend[grossAmount]" min="0" max="99999999999.99" step="0.01"
                    value="<?= $stock_dividend['grossAmount'] ?? '' ?>">
            </label>
        </div>

        <hr>

    <?php endif; ?>

    <?php if (isset($redeemable_shares)): ?>

        <h2>Redeemable Shares</h2>

        <div class="nested-input">
            <label>
                <span>Your Reference <span class="asterisk">*</span></span>
                <input type="text" name="redeemableShares[customerReference]" maxlength="90"
                    value="<?= $redeemable_shares['customerReference'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>
                Gross Amount
                <input type="number" name="redeemableShares[grossAmount]" min="0" max="99999999999.99" step="0.01"
                    value="<?= $redeemable_shares['grossAmount'] ?? '' ?>">
            </label>
        </div>

        <hr>
    <?php endif; ?>

    <?php if (isset($bonus_issues)): ?>

        <h2>Bonus Issues Of Securities</h2>

        <div class="nested-input">
            <label>
                <span>Your Reference <span class="asterisk">*</span></span>
                <input type="text" name="bonusIssuesOfSecurities[customerReference]" maxlength="90"
                    value="<?= $bonus_issues['customerReference'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>
                Gross Amount
                <input type="number" name="bonusIssuesOfSecurities[grossAmount]" min="0" max="99999999999.99" step="0.01"
                    value="<?= $bonus_issues['grossAmount'] ?? '' ?>">
            </label>
        </div>

        <hr>
    <?php endif; ?>

    <?php if (isset($close_company_loans)): ?>

        <h2>Close Company Loans Written Off</h2>

        <div class="nested-input">
            <label>
                <span>Your Reference <span class="asterisk">*</span></span>
                <input type="text" name="closeCompanyLoansWrittenOff[customerReference]" maxlength="90"
                    value="<?= $close_company_loans['customerReference'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>
                Gross Amount
                <input type="number" name="closeCompanyLoansWrittenOff[grossAmount]" min="0" max="99999999999.99"
                    step="0.01" value="<?= $close_company_loans['grossAmount'] ?? '' ?>">
            </label>
        </div>

        <hr>

    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>


    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/dividends-income/retrieve-dividends-income">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_add_another_script = true; ?>