<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/other-income/process-create-and-amend-other-income" method="POST" class="generic-form">

    <?php if (isset($post_cessation_receipts)): ?>

        <h2>Post Cessation Receipts</h2>

        <div id="post-cessation-receipts-container">

            <?php foreach (($post_cessation_receipts ?? []) as $p_c_receipt): ?>

                <div class="post-cessation-receipts-group field-container" data-group="postCessationReceipts">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($p_c_receipt['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Business Name
                            <input type="text" data-name="businessName" maxlength="90"
                                value="<?= esc($p_c_receipt['businessName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Date Business Ceased
                            <input type="date" data-name="dateBusinessCeased"
                                value="<?= esc($p_c_receipt['dateBusinessCeased'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Business Description
                            <input type="text" data-name="businessDescription" maxlength="90"
                                value="<?= esc($p_c_receipt['businessDescription'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Income Source
                            <input type="text" data-name="incomeSource" maxlength="90"
                                value="<?= esc($p_c_receipt['incomeSource'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="amount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($p_c_receipt['amount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Tax Year Receipt Is Taxable <span class="asterisk">*</span></span>
                            <input type="text" data-name="taxYearIncomeToBeTaxed" maxlength="7"
                                value="<?= esc($p_c_receipt['taxYearIncomeToBeTaxed'] ?? '') ?>" placeholder="YYYY-YY">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($business_receipts)): ?>

        <h2>Business Receipts</h2>

        <div id="business-receipts-container">

            <?php foreach ($business_receipts as $b_receipt): ?>

                <div class="business-receipts-group field-container" data-group="businessReceipts">

                    <div class="nested-input">
                        <label><span>Gross Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="grossAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($b_receipt['grossAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Tax Year Receipt Is Taxable <span class="asterisk">*</span></span>
                            <input type="text" data-name="taxYear" maxlength="7" value="<?= esc($b_receipt['taxYear'] ?? '') ?>"
                                placeholder="YYYY-YY">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($all_other_income_received_whilst_abroad)): ?>

        <h2>All Other Income Received Whilst Abroad</h2>

        <div id="all-other-income-received-whilst-abroad-container">

            <?php foreach ($all_other_income_received_whilst_abroad as $other_abroad): ?>

                <div class="all-other-income-received-whilst-abroad-group field-container"
                    data-group="allOtherIncomeReceivedWhilstAbroad">

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="countryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($other_abroad['countryCode'] ?? '') === $code ? 'selected' : '' ?>>
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
                                value="<?= $other_abroad['amountBeforeTax'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Foreign Tax
                            <input type="number" data-name="taxTakenOff" min="0" max="99999999999.99" step="0.01"
                                value="<?= $other_abroad['taxTakenOff'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>UK Tax Withheld
                            <input type="number" data-name="specialWithholdingTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= $other_abroad['specialWithholdingTax'] ?? '' ?>">
                        </label>
                    </div>

                    <label class="inline-checkbox label-text">
                        <input type="checkbox" data-name="foreignTaxCreditRelief" value="1"
                            <?= !empty($other_abroad['foreignTaxCreditRelief']) ? "checked" : "" ?>>
                        <span>Tick If Claiming Foreign Tax Credit Relief</span>
                    </label>

                    <div class="nested-input">
                        <label><span>Taxable Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="taxableAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= $other_abroad['taxableAmount'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Residential Finance Costs
                            <input type="number" data-name="residentialFinancialCostAmount" min="0" max="99999999999.99"
                                step="0.01" value="<?= $other_abroad['residentialFinancialCostAmount'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Carried Forward Residential Finance Costs
                            <input type="number" data-name="broughtFwdResidentialFinancialCostAmount" min="0"
                                max="99999999999.99" step="0.01"
                                value="<?= $other_abroad['broughtFwdResidentialFinancialCostAmount'] ?? '' ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($overseas_income_and_gains)): ?>

        <h2>Gains On Disposal Of Offshore Funds</h2>

        <div class="nested-input">
            <label><span>Gain Amount <span class="asterisk">*</span></span>
                <input type="number" name="overseasIncomeAndGains[gainAmount]" min="0" max="99999999999.99" step="0.01"
                    value="<?= $overseas_income_and_gains['gainAmount'] ?? '' ?>">
            </label>
        </div>

        <hr>

    <?php endif; ?>

    <?php if (isset($chargeable_foreign_benefits_and_gifts)): ?>

        <h2>Chargeable Foreign Benefits And Gifts</h2>

        <div class="nested-input">
            <label>Benefit From Overseas Transaction
                <input type="number" name="chargeableForeignBenefitsAndGifts[transactionBenefit]" min="0"
                    max="99999999999.99" step="0.01"
                    value="<?= $chargeable_foreign_benefits_and_gifts['transactionBenefit'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Benefit From Protected Foreign Income Source
                <input type="number" name="chargeableForeignBenefitsAndGifts[protectedForeignIncomeSourceBenefit]" min="0"
                    max="99999999999.99" step="0.01"
                    value="<?= $chargeable_foreign_benefits_and_gifts['protectedForeignIncomeSourceBenefit'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Protected Foreign Income Gifted
                <input type="number" name="chargeableForeignBenefitsAndGifts[protectedForeignIncomeOnwardGift]" min="0"
                    max="99999999999.99" step="0.01"
                    value="<?= $chargeable_foreign_benefits_and_gifts['protectedForeignIncomeOnwardGift'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Benefit Received As A Settlor
                <input type="number" name="chargeableForeignBenefitsAndGifts[benefitReceivedAsASettler]" min="0"
                    max="99999999999.99" step="0.01"
                    value="<?= $chargeable_foreign_benefits_and_gifts['benefitReceivedAsASettler'] ?? '' ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Gift Received As A Settlor
                <input type="number" name="chargeableForeignBenefitsAndGifts[onwardGiftReceivedAsASettler]" min="0"
                    max="99999999999.99" step="0.01"
                    value="<?= $chargeable_foreign_benefits_and_gifts['onwardGiftReceivedAsASettler'] ?? '' ?>">
            </label>
        </div>

        <hr>

    <?php endif; ?>

    <?php if (isset($omitted_foreign_income)): ?>

        <h2>Omitted Foreign Income</h2>

        <div class="nested-input">
            <label><span>Amount <span class="asterisk">*</span></span>
                <input type="number" name="omittedForeignIncome[amount]" min="0" max="99999999999.99" step="0.01"
                    value="<?= $omitted_foreign_income['amount'] ?? '' ?>">
            </label>

        </div>

        <hr>

    <?php endif; ?>


    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>



<p><a href="/other-income/retrieve-other-income">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>