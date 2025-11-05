<?php include ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/charges/process-create-and-amend-pension-charges" method="POST" class="generic-form hmrc-connection">

    <h2>Overseas Transfers</h2>

    <?php if (isset($pension_scheme_overseas_transfers_overseas_scheme_provider)): ?>

        <div id="pension-scheme-overseas-transfers-overseas-scheme-provider-container">

            <?php foreach ($pension_scheme_overseas_transfers_overseas_scheme_provider as $provider): ?>

                <div class="pension-scheme-overseas-transfers-overseas-scheme-provider-group field-container"
                    data-group="pensionSchemeOverseasTransfers[overseasSchemeProvider]">

                    <div class="nested-input">
                        <label><span>Provider Name <span class="asterisk">*</span></span>
                            <input type="text" data-name="providerName" maxlength="90"
                                value="<?= esc($provider['providerName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Provider Address <span class="asterisk">*</span></span>
                            <input type="text" data-name="providerAddress" maxlength="90"
                                value="<?= esc($provider['providerAddress'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="providerCountryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($provider['providerCountryCode'] ?? '') === $code ? 'selected' : '' ?>>
                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <!-- optional names and references commented out as hmrc don't give formatting but do give error responses -->

                    <?php
                    /*

                    <div class="nested-input">
                        <label>Qualifying Recognised Overseas Pension Scheme Reference <span class="small">Separate multiple
                                referencess with
                                commas</span>
                            <input type="text" data-name="qualifyingRecognisedOverseasPensionScheme" value="<?= esc(is_array($provider['qualifyingRecognisedOverseasPensionScheme'] ?? '')
                                                                                                                ? implode(',', $provider['qualifyingRecognisedOverseasPensionScheme'])
                                                                                                                : $provider['qualifyingRecognisedOverseasPensionScheme'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Pension Scheme Reference <span class="small">Separate multiple
                    referencess with
                    commas</span>
                <input type="text" data-name="pensionSchemeTaxReference"
                    value="<?= esc(is_array($provider['pensionSchemeTaxReference'] ?? '')
                                                                                                ? implode(',', $provider['pensionSchemeTaxReference'])
                                                                                                : $provider['pensionSchemeTaxReference'] ?? '') ?>">
            </label>
        </div>

        */
                    ?>



                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <div class="nested-input">
        <label><span>Transfer Charge <span class="asterisk">*</span></span>
            <input type="number" name="pensionSchemeOverseasTransfers[transferCharge]" min="0" max="99999999999.99"
                step="0.01" value="<?= esc($pension_scheme_overseas_transfers['transferCharge'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label><span>Tax Paid On Transfer Charge <span class="asterisk">*</span></span>
            <input type="number" name="pensionSchemeOverseasTransfers[transferChargeTaxPaid]" min="0"
                max="99999999999.99" step="0.01"
                value="<?= esc($pension_scheme_overseas_transfers['transferChargeTaxPaid'] ?? '') ?>">
        </label>
    </div>

    <hr>


    <h2>Unauthorised Payments</h2>

    <div class="nested-input">
        <label><span>Scheme Tax Reference <span class="asterisk">*</span></span><span class="small">Separate multiple
                referencess with
                commas</span>
            <input type="text" name="pensionSchemeUnauthorisedPayments[pensionSchemeTaxReference]"
                value="<?= esc(is_array($pension_scheme_unauthorised_payments['pensionSchemeTaxReference'] ?? '')
                            ? implode(',', $pension_scheme_unauthorised_payments['pensionSchemeTaxReference'])
                            : $pension_scheme_unauthorised_payments['pensionSchemeTaxReference'] ?? '') ?>">
        </label>
    </div>



    <h3>Surcharge</h3>

    <div class="nested-input">
        <label><span>Amount <span class="asterisk">*</span></span>
            <input type="number" name="pensionSchemeUnauthorisedPayments[surcharge][amount]" min="0"
                max="99999999999.99" step="0.01"
                value="<?= esc($pension_scheme_unauthorised_payments['surcharge']['amount'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Foreign Tax Paid (required)
            <input type="number" name="pensionSchemeUnauthorisedPayments[surcharge][foreignTaxPaid]" min="0"
                max="99999999999.99" step="0.01"
                value="<?= esc($pension_scheme_unauthorised_payments['surcharge']['foreignTaxPaid'] ?? '') ?>">
        </label>
    </div>

    <h3>No Surcharge</h3>

    <div class="nested-input">
        <label><span>Amount <span class="asterisk">*</span></span>
            <input type="number" name="pensionSchemeUnauthorisedPayments[noSurcharge][amount]" min="0"
                max="99999999999.99" step="0.01"
                value="<?= esc($pension_scheme_unauthorised_payments['noSurcharge']['amount'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label><span>Foreign Tax Paid <span class="asterisk">*</span></span>
            <input type="number" name="pensionSchemeUnauthorisedPayments[noSurcharge][foreignTaxPaid]" min="0"
                max="99999999999.99" step="0.01"
                value="<?= esc($pension_scheme_unauthorised_payments['noSurcharge']['foreignTaxPaid'] ?? '') ?>">
        </label>
    </div>

    <hr>

    <h2>Pension Contributions</h2>

    <div class="nested-input">
        <label><span>Scheme Tax Reference <span class="asterisk">*</span></span><span class="small">Separate multiple
                references with commas</span>
            <input type="text" name="pensionContributions[pensionSchemeTaxReference]" value="
            <?= esc(is_array($pension_contributions['pensionSchemeTaxReference'] ?? '')
                ? implode(',', $pension_contributions['pensionSchemeTaxReference'])
                : $pension_contributions['pensionSchemeTaxReference'] ?? '') ?>">
        </label>
    </div>


    <div class="nested-input">
        <label><span>Excess Over Annual Allowance <span class="asterisk">*</span></span>
            <input type="number" name="pensionContributions[inExcessOfTheAnnualAllowance]" min="0" max="99999999999.99"
                step="0.01" value="<?= esc($pension_contributions['inExcessOfTheAnnualAllowance'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label><span>Annual Allowance Tax Paid <span class="asterisk">*</span></span>
            <input type="number" name="pensionContributions[annualAllowanceTaxPaid]" min="0" max="99999999999.99"
                step="0.01" value="<?= esc($pension_contributions['annualAllowanceTaxPaid'] ?? '') ?>">
        </label>
    </div>

    <label class="inline-checkbox label-text">
        <input type="checkbox" name="pensionContributions[isAnnualAllowanceReduced]" value="1"
            <?= (!empty($pension_contributions['isAnnualAllowanceReduced'])) ? "checked" : "" ?>>
        <span>Tick If Annual Allowance Is Reduced</span>
    </label>

    <label class="inline-checkbox label-text">
        <input type="checkbox" name="pensionContributions[taperedAnnualAllowance]" value="1"
            <?= (!empty($pension_contributions['taperedAnnualAllowance'])) ? "checked" : "" ?>>
        <span>Tick If Annual Allowance Is Tapered</span>
    </label>

    <label class="inline-checkbox label-text">
        <input type="checkbox" name="pensionContributions[moneyPurchasedAllowance]" value="1"
            <?= (!empty($pension_contributions['moneyPurchasedAllowance'])) ? "checked" : "" ?>>
        <span>Tick If Money Purchase Annual Allowance Applies</span>
    </label>

    <hr>


    <h2>Overseas Pension Contributions</h2>

    <?php if (isset($overseas_pension_contributions_overseas_scheme_provider)): ?>

        <div id="overseas-pension-contributions-overseas-scheme-provider-container">

            <?php foreach ($overseas_pension_contributions_overseas_scheme_provider as $provider): ?>

                <div class="overseas-pension-contributions-overseas-scheme-provider-group field-container"
                    data-group="overseasPensionContributions[overseasSchemeProvider]">

                    <div class="nested-input">
                        <label><span>Provider Name <span class="asterisk">*</span></span>
                            <input type="text" data-name="providerName" value="<?= esc($provider['providerName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Provider Address <span class="asterisk">*</span></span>
                            <input type="text" data-name="providerAddress"
                                value="<?= esc($provider['providerAddress'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="providerCountryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($provider['providerCountryCode'] ?? '') === $code ? 'selected' : '' ?>>
                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <!-- optional names and references commented out as hmrc don't give formatting-->

                    <?php
                    /*
            <div class="nested-input">
                <label>Qualifying Recognised Overseas Pension Scheme Reference <span class="small">Separate multiple
                        referencess with
                        commas</span>
                    <input type="text" data-name="qualifyingRecognisedOverseasPensionScheme" value="<?= esc(is_array($provider['qualifyingRecognisedOverseasPensionScheme'] ?? '')
                                            ? implode(',', $provider['qualifyingRecognisedOverseasPensionScheme'])
                                            : $provider['qualifyingRecognisedOverseasPensionScheme'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>Pension Scheme Reference <span class="small">Separate multiple
                    referencess with
                    commas</span>
                <input type="text" data-name="pensionSchemeTaxReference" value="<?= esc(is_array($provider['pensionSchemeTaxReference'] ?? '')
                                            ? implode(',', $provider['pensionSchemeTaxReference'])
                                            : $provider['pensionSchemeTaxReference'] ?? '') ?>">
            </label>
        </div>

        */
                    ?>


                    <!--  scheme names and references left out as optional -->



                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <div class="nested-input">
        <label><span>Short Service Refund <span class="asterisk">*</span></span>
            <input type="number" name="overseasPensionContributions[shortServiceRefund]" min="0" max="99999999999.99"
                step="0.01" value="<?= esc($overseas_pension_contributions['shortServiceRefund'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label><span>Short Service Refund Tax Paid <span class="asterisk">*</span></span>
            <input type="number" name="overseasPensionContributions[shortServiceRefundTaxPaid]" min="0"
                max="99999999999.99" step="0.01"
                value="<?= esc($overseas_pension_contributions['shortServiceRefundTaxPaid'] ?? '') ?>">
        </label>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a class="hmrc-connection" href="/charges/retrieve-pension-charges">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>