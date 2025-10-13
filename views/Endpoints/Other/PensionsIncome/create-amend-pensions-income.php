<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/pensions-income/process-create-and-amend-pensions-income" method="POST" class="generic-form">


    <?php if (isset($foreign_pensions)): ?>

        <h2>Foreign Pensions</h2>

        <div id="foreign-pensions-container">

            <?php foreach ($foreign_pensions as $foreign_pension): ?>

                <div class="foreign-pensions-group field-container" data-group="foreignPensions">

                    <div class="nested-input">
                        <label><span>Country <span class="asterisk">*</span></span>
                            <select data-name="countryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($foreign_pension['countryCode'] ?? '') === $code ? 'selected' : '' ?>>

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
                                value="<?= $foreign_pension['amountBeforeTax'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Foreign Tax
                            <input type="number" data-name="taxTakenOff" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_pension['taxTakenOff'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>UK Tax Withheld
                            <input type="number" data-name="specialWithholdingTax" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_pension['specialWithholdingTax'] ?? '' ?>">
                        </label>
                    </div>

                    <label class="inline-checkbox label-text">
                        <input type="checkbox" data-name="foreignTaxCreditRelief" value="1"
                            <?= (isset($foreign_pension['foreignTaxCreditRelief']) && ($foreign_pension['foreignTaxCreditRelief'] === true || $foreign_pension['foreignTaxCreditRelief'] === 'true' || $foreign_pension['foreignTaxCreditRelief'] === '1')) ? "checked" : "" ?>>
                        <span>Tick If Claiming Foreign Tax Credit Relief</span>
                    </label>

                    <div class="nested-input">
                        <label><span>Taxable Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="taxableAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= $foreign_pension['taxableAmount'] ?? '' ?>">
                        </label>
                    </div>



                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($overseas_contributions)): ?>

        <h2>Overseas Pension Contributions</h2>

        <div id="overseas-pension-contributions-container">

            <?php foreach ($overseas_contributions as $contribution): ?>

                <div class="overseas-pension-contributions-group field-container" data-group="overseasPensionContributions">

                    <div class="nested-input">
                        <label>Country
                            <select data-name="dblTaxationCountryCode">
                                <option value="">Select country</option>
                                <?php foreach ($countries as $continent => $country_list): ?>
                                    <optgroup label="<?= esc($continent) ?>">
                                        <?php foreach ($country_list as $code => $name): ?>
                                            <option value="<?= esc($code) ?>"
                                                <?= ($contribution['dblTaxationCountryCode'] ?? '') === $code ? 'selected' : '' ?>>

                                                <?= esc($name) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= $contribution['customerReference'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Exempt Employer Contributions <span class="asterisk">*</span></span>
                            <input type="number" data-name="exemptEmployersPensionContribs" min="0" max="99999999999.99"
                                step="0.01" value="<?= $contribution['exemptEmployersPensionContribs'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Qualifying Overseas Pension Scheme Reference
                            <input type="text" data-name="migrantMemReliefQopsRefNo" maxlength="90"
                                value="<?= $contribution['migrantMemReliefQopsRefNo'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Double Tax Relief Amount
                            <input type="number" data-name="dblTaxationRelief" min="0" max="99999999999.99" step="0.01"
                                value="<?= $contribution['dblTaxationRelief'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Double Tax Treaty
                            <input type="text" data-name="dblTaxationTreaty" maxlength="90"
                                value="<?= $contribution['dblTaxationTreaty'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Double Tax Treaty Article
                            <input type="text" data-name="dblTaxationArticle" maxlength="90"
                                value="<?= $contribution['dblTaxationArticle'] ?? '' ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>SF74 Reference
                            <input type="text" data-name="sf74reference" maxlength="90"
                                value="<?= $contribution['sf74reference'] ?? '' ?>">
                        </label>
                    </div>



                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button">Submit</button>
</form>

<p><a href="/pensions-income/retrieve-pensions-income">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_add_another_script = true; ?>