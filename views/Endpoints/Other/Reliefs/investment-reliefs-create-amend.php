<?php include ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/reliefs/process-create-and-amend-relief-investments" method="POST" class="generic-form">

    <?php if (isset($vct_subscription)): ?>

        <h2>VCT Subscriptions</h2>

        <div id="vct-subscription-container">

            <?php foreach (($vct_subscription ?? []) as $vct): ?>

                <div class="vct-subscription-group field-container" data-group="vctSubscription">

                    <div class="nested-input">
                        <label>Unique Reference Or Authorising Tax Office
                            <input type="text" data-name="uniqueInvestmentRef" maxlength="90"
                                value="<?= esc($vct['uniqueInvestmentRef'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Name Of Investment Or Fund <span class="asterisk">*</span></span>
                            <input type="text" data-name="name" maxlength="105" value="<?= esc($vct['name'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Date Of Investment <span class="asterisk">*</span></span>
                            <input type="date" data-name="dateOfInvestment" value="<?= esc($vct['dateOfInvestment'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Amount Invested
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="amountInvested"
                                value="<?= esc($vct['amountInvested'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="reliefClaimed"
                                value="<?= esc($vct['reliefClaimed'] ?? '') ?>">
                        </label>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($eis_subscription)): ?>

        <h2>EIS Subscriptions</h2>

        <div id="eis-subscription-container">

            <?php foreach (($eis_subscription ?? []) as $eis): ?>

                <div class="eis-subscription-group field-container" data-group="eisSubscription">

                    <div class="nested-input">
                        <label><span>Unique Investent Reference Or Authorising Tax Office <span class="asterisk">*</span></span>
                            <input type="text" data-name="uniqueInvestmentRef" maxlength="90"
                                value="<?= esc($eis['uniqueInvestmentRef'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Name Of Investment <span class="asterisk">*</span></span>
                            <input type="text" data-name="name" maxlength="105" value="<?= esc($eis['name'] ?? '') ?>">
                        </label>
                    </div>

                    <label class="inline-checkbox label-text">
                        <input type="checkbox" data-name="knowledgeIntensive" value="1"
                            <?= !empty($eis['knowledgeIntensive']) ? "checked" : "" ?>>
                        <span>Tick If Company Qualifies As Knowledge-Intensive</span>
                    </label>

                    <div class="nested-input">
                        <label><span>Date Of Investment <span class="asterisk">*</span></span>
                            <input type="date" data-name="dateOfInvestment" value="<?= esc($eis['dateOfInvestment'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Amount Invested
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="amountInvested"
                                value="<?= esc($eis['amountInvested'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="reliefClaimed"
                                value="<?= esc($eis['reliefClaimed'] ?? '') ?>">
                        </label>
                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($community_investment)): ?>

        <h2>Community Investments</h2>

        <div id="community-investment-container">

            <?php foreach (($community_investment ?? []) as $community): ?>

                <div class="community-investment-group field-container" data-group="communityInvestment">

                    <div class="nested-input">
                        <label><span>Unique Investment Reference Or Authorising Tax Office <span
                                    class="asterisk">*</span></span>
                            <input type="text" data-name="uniqueInvestmentRef" maxlength="90"
                                value="<?= esc($community['uniqueInvestmentRef'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Name Of Investment Or Fund
                            <input type="text" data-name="name" maxlength="105" value="<?= esc($community['name'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Date Of Investment
                            <input type="date" data-name="dateOfInvestment"
                                value="<?= esc($community['dateOfInvestment'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Amount Invested
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="amountInvested"
                                value="<?= esc($community['amountInvested'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Relief Claimed <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="reliefClaimed"
                                value="<?= esc($community['reliefClaimed'] ?? '') ?>">
                        </label>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($community_investment)): ?>

        <h2>Seed Enterprise Investments</h2>

        <div id="seed-enterprise-investment-container">

            <?php foreach (($seed_enterprise_investment ?? []) as $seed): ?>

                <div class="seed-enterprise-investment-group field-container" data-group="seedEnterpriseInvestment">

                    <div class="nested-input">
                        <label><span>Unique Reference Or Authorising Tax Office <span class="asterisk">*</span></span>
                            <input type="text" data-name="uniqueInvestmentRef" maxlength="90"
                                value="<?= esc($seed['uniqueInvestmentRef'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Name Of Company Holding The Investment <span class="asterisk">*</span></span>
                            <input type="text" data-name="companyName" maxlength="105"
                                value="<?= esc($seed['companyName'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Date Of Investment <span class="asterisk">*</span></span>
                            <input type="date" data-name="dateOfInvestment" value="<?= esc($seed['dateOfInvestment'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Amount Invested
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="amountInvested"
                                value="<?= esc($seed['amountInvested'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Amount Of Relief Claimed <span class="asterisk">*</span></span>
                            <input type="number" min="0" max="99999999999.99" step="0.01" data-name="reliefClaimed"
                                value="<?= esc($seed['reliefClaimed'] ?? '') ?>">
                        </label>
                    </div>


                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a href="/reliefs/retrieve-relief-investments">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>