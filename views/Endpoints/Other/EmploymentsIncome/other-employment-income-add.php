<form class="generic-form" action="/employments-income/process-create-amend-other-employment-income" method="POST">

    <?php if (isset($share_options)): ?>

        <h2>Share Options</h2>

        <div id="share-options-container">

            <?php foreach ($share_options as $option): ?>

                <div class="share-options-group field-container" data-group="shareOption">

                    <div class="nested-input form-input">
                        <label for="shareOptionEmployerName">Employer Name
                            <input type="text" data-name="employerName" value="<?= esc($option['employerName'] ?? '') ?>"
                                data-required>
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Plan Type
                            <select data-name="schemePlanType">
                                <?php $scheme_plan_type = $option['schemePlanType'] ?? ''; ?>
                                <option value="" disabled selected hidden>Pick an option</option>
                                <option value="emi" <?= $scheme_plan_type === 'emi' ? 'selected' : '' ?>>Enterprise Management
                                    Incentive (EMI)</option>
                                <option value="csop" <?= $scheme_plan_type === 'csop' ? 'selected' : '' ?>>Company Share
                                    Option Plan (CSOP)</option>
                                <option value="saye" <?= $scheme_plan_type === 'saye' ? 'selected' : '' ?>>Save As You Earn
                                    (SAYE)</option>
                                <option value="other" <?= $scheme_plan_type === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Date Of Option Grant
                            <input type="date" data-name="dateOfOptionGrant"
                                value="<?= esc($option['dateOfOptionGrant'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Date Of Event
                            <input type="date" data-name="dateOfEvent" value="<?= esc($option['dateOfEvent'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Amount Of Consideration Received
                            <input type="number" data-name="amountOfConsiderationReceived" min="0" max="99999999999.99"
                                step="0.01" value="<?= esc($option['amountOfConsiderationReceived'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Number Of Shares Acquired
                            <input type="number" data-name="noOfSharesAcquired" min="0" step="1"
                                value="<?= esc($option['noOfSharesAcquired'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Exercise Price
                            <input type="number" data-name="exercisePrice" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($option['exercisePrice'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Amount Paid For Option
                            <input type="number" data-name="amountPaidForOption" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($option['amountPaidForOption'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Market Value Of Shares On Exercise
                            <input type="number" data-name="marketValueOfSharesOnExcise" min="0" max="99999999999.99"
                                step="0.01" value="<?= esc($option['marketValueOfSharesOnExcise'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Profit On Option Exercised
                            <input type="number" data-name="profitOnOptionExercised" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($option['profitOnOptionExercised'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Employer NIC Paid
                            <input type="number" data-name="employersNicPaid" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($option['employersNicPaid'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Taxable Amount
                            <input type="number" data-name="taxableAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($option['taxableAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <br>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($share_awards)): ?>

        <h2>Share Awards</h2>

        <div id="share-award-container">

            <?php foreach ($share_awards as $award): ?>

                <div class="share-award-group field-container" data-group="sharesAwardedOrReceived">

                    <div class="nested-input form-input">
                        <label>Employer Name
                            <input type="text" data-name="employerName" value="<?= esc($award['employerName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Plan Type
                            <select data-name="schemePlanType">
                                <?php $scheme_plan_type = $award['schemePlanType'] ?? ""; ?>
                                <option value="" disabled selected hidden>Pick an option</option>
                                <option value="sip" <?= $scheme_plan_type === 'sip' ? 'selected' : '' ?>>Share Incentive Plan
                                    (SIP)
                                </option>
                                <option value="other" <?= $scheme_plan_type === 'other' ? 'selected' : '' ?>>Other</option>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Date Shares Ceased To Be Subject To
                            Plan
                            <input type="date" data-name="dateSharesCeasedToBeSubjectToPlan"
                                value="<?= esc($award['dateSharesCeasedToBeSubjectToPlan'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Number Of Shares Awarded
                            <input type="number" data-name="noOfShareSecuritiesAwarded" min="0" step="1"
                                value="<?= esc($award['noOfShareSecuritiesAwarded'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Class Of Shares Awarded
                            <input type="text" data-name="classOfShareAwarded"
                                value="<?= esc($award['classOfShareAwarded'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Date Shares Awarded
                            <input type="date" data-name="dateSharesAwarded"
                                value="<?= esc($award['dateSharesAwarded'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Are The Shares Restricted?
                            <?php $restrictions = $award['sharesSubjectToRestrictions'] ?? ""; ?>
                            <select data-name="sharesSubjectToRestrictions">
                                <option value="" disabled selected hidden>Pick an option</option>
                                <option value="0" <?= $restrictions === '0' ? 'selected' : '' ?>>No</option>
                                <option value="1" <?= $restrictions === '1' ? 'selected' : '' ?>>Yes</option>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Election Entered To Ignore
                            Restrictions?
                            <?php $election = $award['electionEnteredIgnoreRestrictions'] ?? ""; ?>
                            <select data-name="electionEnteredIgnoreRestrictions">
                                <option value="" disabled selected hidden>Pick an option</option>
                                <option value="0" <?= $election === '0' ? 'selected' : '' ?>>No</option>
                                <option value="1" <?= $election === '1' ? 'selected' : '' ?>>Yes</option>
                            </select>
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Actual Market Value Of Shares On Award
                            <input type="number" data-name="actualMarketValueOfSharesOnAward" min="0" max="99999999999.99"
                                step="0.01" value="<?= esc($award['actualMarketValueOfSharesOnAward'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Unrestricted Market Value Of Shares On
                            Award
                            <input type="number" data-name="unrestrictedMarketValueOfSharesOnAward" min="0" max="99999999999.99"
                                step="0.01" value="<?= esc($award['unrestrictedMarketValueOfSharesOnAward'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Amount Paid On Award
                            <input type="number" data-name="amountPaidForSharesOnAward" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($award['amountPaidForSharesOnAward'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Market Value After Restrictions Lifted
                            <input type="number" data-name="marketValueAfterRestrictionsLifted" min="0" max="99999999999.99"
                                step="0.01" value="<?= esc($award['marketValueAfterRestrictionsLifted'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Taxable Amount
                            <input type="number" data-name="taxableAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($award['taxableAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($lump_sums)): ?>

        <h2>Lump Sums</h2>

        <div id="lump-sum-container">

            <?php foreach ($lump_sums as $lump_sum): ?>

                <div class="lump-sum-group field-container" data-group="lumpSums">

                    <div class="nested-input form-input">
                        <label>Employer Name
                            <input type="text" data-name="employerName" value="<?= esc($lump_sum['employerName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Employer PAYE Reference
                            <input type="text" data-name="employerRef" value="<?= esc($lump_sum['employerRef'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Taxable Lump Sum Amount
                            <input type="number" data-name="taxableLumpSumsAndCertainIncome.amount" min="0" max="99999999999.99"
                                step="0.01" value="<?= esc($lump_sum['taxableLumpSumsAndCertainIncome']['amount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Employer Financed Retirement Scheme Amount
                            <input type="number" data-name="benefitFromEmployerFinancedRetirementScheme.amount" min="0"
                                max="99999999999.99" step="0.01"
                                value="<?= esc($lump_sum['benefitFromEmployerFinancedRetirementScheme']['amount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Amount Of Redundancy Payments Over Exemption
                            <input type="number" data-name="redundancyCompensationPaymentsOverExemption.amount" min="0"
                                max="99999999999.99" step="0.01"
                                value="<?= esc($lump_sum['redundancyCompensationPaymentsOverExemption']['amount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input form-input">
                        <label>Amount Of Redundancy Payments Under Exemption
                            <input type="number" data-name="redundancyCompensationPaymentsUnderExemption.amount" min="0"
                                max="99999999999.99" step="0.01"
                                value="<?= esc($lump_sum['redundancyCompensationPaymentsUnderExemption']['amount'] ?? '') ?>">
                        </label>
                    </div>

                    <br>


                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($disability)): ?>

        <h2>Disability Payments</h2>

        <div class="form-input">
            <label for="disabilityAmountDeducted">Disability Payments Amount Exempt</label>
            <input type="number" name="disability[amountDeducted]" id="disabilityAmountDeducted" min="0"
                max="99999999999.99" step="0.01" value="<?= $disability['amountDeducted'] ?? '' ?>">

        </div>

    <?php endif; ?>

    <?php if (isset($foreign_service)) : ?>

        <h2>Foreign Service</h2>

        <div class="form-input">
            <label for="foreignServiceAmountDeducted">Foreign Service Amount Exempt</label>
            <input type="number" name="foreignService[amountDeducted]" id="foreignServiceAmountDeducted" min="0"
                max="99999999999.99" step="0.01" value="<?= $foreign_service['amountDeducted'] ?? '' ?>">
        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "/views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/employments-income/retrieve-other-employment-income">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_add_another_script = true; ?>