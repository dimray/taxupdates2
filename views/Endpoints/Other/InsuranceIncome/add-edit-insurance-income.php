<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/insurance-income/process-create-and-amend-insurance-policies-income" method="POST" class="generic-form">

    <?php if (isset($life_insurance)): ?>

        <h2>Life Insurance</h2>

        <div id="life-insurance-container">

            <?php foreach (($life_insurance ?? []) as $life_ins): ?>

                <div class="life-insurance-group field-container" data-group="lifeInsurance">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($life_ins['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Event Details (e.g. maturity, part surrender)
                            <input type="text" data-name="event" maxlength="90" value="<?= esc($life_ins['event'] ?? '') ?>">
                        </label>

                    </div>

                    <div class="nested-input">
                        <label><span>Gain Amount<span class="asterisk">*</span></span>
                            <input type="number" data-name="gainAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($life_ins['gainAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">

                        <label>
                            <div class="inline-checkbox">
                                <input type="checkbox" data-name="taxPaid" value="true"
                                    <?= (!empty($life_ins['taxPaid']) && $life_ins['taxPaid'] == true) ? 'checked' : '' ?>>
                                <span>Tick box if tax has been deducted <span class="asterisk">*</span></span>
                            </div>
                        </label>

                    </div>

                    <div class="nested-input">
                        <label>Years Held
                            <input type="number" data-name="yearsHeld" min="0" max="99" step="1"
                                value="<?= esc($life_ins['yearsHeld'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Years Held Since Last Gain
                            <input type="number" data-name="yearsHeldSinceLastGain" min="0" max="99" step="1"
                                value="<?= esc($life_ins['yearsHeldSinceLastGain'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Deficiency Relief
                            <input type="number" data-name="deficiencyRelief" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($life_ins['deficiencyRelief'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($capital_redemption)): ?>

        <h2>Capital Redemption</h2>

        <div id="capital-redemption-container">

            <?php foreach ($capital_redemption as $redemption): ?>

                <div class="capital-redemption-group field-container" data-group="capitalRedemption">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($redemption['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Event Details
                            <input type="text" data-name="event" maxlength="90" value="<?= esc($redemption['event'] ?? '') ?>">
                        </label>

                    </div>

                    <div class="nested-input">
                        <label><span>Gain Amount<span class="asterisk">*</span></span>
                            <input type="number" data-name="gainAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($redemption['gainAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">

                        <label>
                            <div class="inline-checkbox">
                                <input type="checkbox" data-name="taxPaid" value="true"
                                    <?= (!empty($redemption['taxPaid']) && $redemption['taxPaid'] == true) ? 'checked' : '' ?>>
                                <span>Tick box if tax has been deducted <span class="asterisk">*</span></span>
                            </div>
                        </label>

                    </div>

                    <div class="nested-input">
                        <label>Years Held
                            <input type="number" data-name="yearsHeld" min="0" max="99" step="1"
                                value="<?= esc($redemption['yearsHeld'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Years Held Since Last Gain
                            <input type="number" data-name="yearsHeldSinceLastGain" min="0" max="99" step="1"
                                value="<?= esc($redemption['yearsHeldSinceLastGain'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Deficiency Relief
                            <input type="number" data-name="deficiencyRelief" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($redemption['deficiencyRelief'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($life_annuity)): ?>

        <h2>Life Annuity</h2>

        <div id="life-annuity-container">

            <?php foreach ($life_annuity as $annuity): ?>

                <div class="life-annuity-group field-container" data-group="lifeAnnuity">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($annuity['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Event Details
                            <input type="text" data-name="event" maxlength="90" value="<?= esc($annuity['event'] ?? '') ?>">
                        </label>

                    </div>

                    <div class="nested-input">
                        <label> <span>Gain Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="gainAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($annuity['gainAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">

                        <label>
                            <div class="inline-checkbox">
                                <input type="checkbox" data-name="taxPaid" value="true"
                                    <?= (!empty($annuity['taxPaid']) && $annuity['taxPaid'] == true) ? 'checked' : '' ?>>
                                <span>Tick box if tax has been deducted</span>
                            </div>
                        </label>

                    </div>

                    <div class="nested-input">
                        <label>Years Held
                            <input type="number" data-name="yearsHeld" min="0" max="99" step="1"
                                value="<?= esc($annuity['yearsHeld'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Years Held Since Last Gain
                            <input type="number" data-name="yearsHeldSinceLastGain" min="0" max="99" step="1"
                                value="<?= esc($annuity['yearsHeldSinceLastGain'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Deficiency Relief
                            <input type="number" data-name="deficiencyRelief" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($annuity['deficiencyRelief'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($voided_isa)): ?>

        <h2>Voided ISA</h2>

        <div id="voided-isa-container">

            <?php foreach ($voided_isa as $isa): ?>
                <div class="voided-isa-group field-container" data-group="voidedIsa">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($isa['customerReference'] ?? '') ?>">
                        </label>

                    </div>

                    <div class="nested-input">
                        <label>Event Details
                            <input type="text" data-name="event" maxlength="90" value="<?= esc($isa['event'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Amount Of Tax Paid
                            <input type="number" data-name="taxPaidAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($isa['taxPaidAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Gain Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="gainAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($isa['gainAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Years Held
                            <input type="number" data-name="yearsHeld" min="0" max="99" step="1"
                                value="<?= esc($isa['yearsHeld'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Years Held Since Last Gain
                            <input type="number" data-name="yearsHeldSinceLastGain" min="0" max="99" step="1"
                                value="<?= esc($isa['yearsHeldSinceLastGain'] ?? '') ?>">
                        </label>
                    </div>
                    <br>

                </div>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <?php if (isset($foreign)): ?>

        <h2>Foreign Policies</h2>

        <div id="foreign-container">

            <?php foreach ($foreign as $foreign_policy): ?>
                <div class="foreign-group field-container" data-group="foreign">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($foreign_policy['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Gain Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="gainAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($foreign_policy['gainAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Amount Of Tax Paid
                            <input type="number" data-name="taxPaidAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($foreign_policy['taxPaidAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Years Held
                            <input type="number" data-name="yearsHeld" min="0" max="99" step="1"
                                value="<?= esc($foreign_policy['yearsHeld'] ?? '') ?>">
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


<p><a href="/insurance-income/retrieve-insurance-policies-income">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>