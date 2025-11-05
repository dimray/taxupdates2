<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/reliefs/process-create-and-amend-other-reliefs" method="POST" class="generic-form hmrc-connection">

    <?php if (isset($non_deductible_loan_interest)): ?>

        <h2>Non Deductible Loan Interest</h2>

        <div class="nested-input">
            <label>Your Reference
                <input type="text" name="nonDeductibleLoanInterest[customerReference]" maxlength="90"
                    value="<?= esc($non_deductible_loan_interest['customerReference'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="nonDeductibleLoanInterest[reliefClaimed]"
                    value="<?= esc($non_deductible_loan_interest['reliefClaimed'] ?? '') ?>">
            </label>
        </div>

    <?php endif; ?>

    <?php if (isset($payroll_giving)): ?>

        <h2>Payroll Giving</h2>

        <div class="nested-input">
            <label>Your Reference
                <input type="text" name="payrollGiving[customerReference]" maxlength="90"
                    value="<?= esc($payroll_giving['customerReference'] ?? '') ?>">
            </label>
        </div>


        <div class="nested-input">
            <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01" name="payrollGiving[reliefClaimed]"
                    value="<?= esc($payroll_giving['reliefClaimed'] ?? '') ?>">
            </label>
        </div>

    <?php endif; ?>

    <?php if (isset($qualifying_distribution)): ?>

        <h2>Qualifying Distributions Of Shares And Securities</h2>

        <div class="nested-input">
            <label>Your Reference
                <input type="text" name="qualifyingDistributionRedemptionOfSharesAndSecurities[customerReference]"
                    maxlength="90" value="<?= esc($qualifying_distribution['customerReference'] ?? '') ?>">
            </label>
        </div>


        <div class="nested-input">
            <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="qualifyingDistributionRedemptionOfSharesAndSecurities[amount]"
                    value="<?= esc($qualifying_distribution['amount'] ?? '') ?>">
            </label>
        </div>

    <?php endif; ?>

    <?php if (isset($maintenance_payments)): ?>

        <h2>Qualifying Maintenance Payments</h2>

        <div id="maintenance-payments-container">

            <?php foreach (($maintenance_payments ?? []) as $maintenance): ?>

                <div class="maintenance-payments-group field-container" data-group="maintenancePayments">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($maintenance['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Ex-Spouse Name
                            <input type="text" data-name="exSpouseName" maxlength="90"
                                value="<?= esc($maintenance['exSpouseName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Ex-Spouse Date Of Birth
                            <input type="date" data-name="exSpouseDateOfBirth"
                                value="<?= esc($maintenance['exSpouseDateOfBirth'] ?? '') ?>">
                        </label>
                    </div>


                    <div class="nested-input">
                        <label><span>Amount Paid <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="amount"
                                value="<?= esc($maintenance['amount'] ?? '') ?>">
                        </label>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($post_cessation_trade_relief)): ?>

        <h2>Post Cessation Trade Relief</h2>

        <div id="post-cessation-trade-relief-container">

            <?php foreach (($post_cessation_trade_relief ?? []) as $trade_relief): ?>

                <div class="post-cessation-trade-relief-group field-container"
                    data-group="postCessationTradeReliefAndCertainOtherLosses">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($trade_relief['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Business Name
                            <input type="text" data-name="businessName" maxlength="105"
                                value="<?= esc($trade_relief['businessName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Date Business Ceased
                            <input type="date" data-name="dateBusinessCeased"
                                value="<?= esc($trade_relief['dateBusinessCeased'] ?? '') ?>">
                        </label>
                    </div>


                    <div class="nested-input">
                        <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="amount"
                                value="<?= esc($trade_relief['amount'] ?? '') ?>">
                        </label>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($annual_payments_made)): ?>

        <h2>Annual Payments</h2>

        <div class="nested-input">
            <label>Your Reference
                <input type="text" name="annualPaymentsMade[customerReference]" maxlength="90"
                    value="<?= esc($annual_payments_made['customerReference'] ?? '') ?>">
            </label>
        </div>


        <div class="nested-input">
            <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                <input type="number" min="0" max="99999999999.99" step="0.01" name="annualPaymentsMade[reliefClaimed]"
                    value="<?= esc($annual_payments_made['reliefClaimed'] ?? '') ?>">
            </label>
        </div>

    <?php endif; ?>

    <?php if (isset($qualifying_loan_interest_payments)): ?>

        <h2>Qualifying Loan Interest</h2>

        <div id="qualifying-loan-interest-payments-container">

            <?php foreach (($qualifying_loan_interest_payments ?? []) as $interest_payment): ?>

                <div class="qualifying-loan-interest-payments-group field-container"
                    data-group="qualifyingLoanInterestPayments">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                value="<?= esc($interest_payment['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Lender Name
                            <input type="text" data-name="lenderName" maxlength="105"
                                value="<?= esc($interest_payment['lenderName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="reliefClaimed"
                                value="<?= esc($interest_payment['reliefClaimed'] ?? '') ?>">
                        </label>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a class="hmrc-connection" href="/reliefs/retrieve-other-reliefs">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>