<?php include ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/capital-gains/process-create-amend-cgt-on-residential-property-overrides" method="POST"
    class="generic-form hmrc-connection">

    <?php if (isset($multiple_property_disposals)): ?>

        <h2>Multiple Property Disposals</h2>

        <div id="multiple-property-disposals-container">

            <?php foreach (($multiple_property_disposals ?? []) as $i => $multiple_disposal): ?>

                <div class="multiple-property-disposals-group field-container" data-group="multiplePropertyDisposals">

                    <div class="nested-input">
                        <label><span>CGT On Property Submission Reference <span class="asterisk">*</span></span>
                            <input type="text" data-name="ppdSubmissionId" maxlength="12"
                                value="<?= esc($multiple_disposal['ppdSubmissionId'] ?? '') ?>">
                        </label>
                    </div>


                    <div class="nested-input">
                        <label><span>Total Net Gain (either gain or loss is required) <span class="asterisk">*</span></span>
                            <input type="number" data-name="amountOfNetGain" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($multiple_disposal['amountOfNetGain'] ?? '') ?>">
                        </label>
                    </div>


                    <div class="nested-input">
                        <label><span>Total Net Loss (either gain or loss is required) <span class="asterisk">*</span></span>
                            <input type="number" data-name="amountOfNetLoss" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($multiple_disposal['amountOfNetLoss'] ?? '') ?>">
                        </label>
                    </div>

                    <br>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php if (isset($single_property_disposals)): ?>

        <h2>Single Property Disposals</h2>

        <div id="single-property-disposals-container">

            <?php foreach (($single_property_disposals ?? []) as $i => $single_disposal): ?>

                <div class="single-property-disposals-group field-container" data-group="singlePropertyDisposals">

                    <div class="nested-input">
                        <label><span>CGT On Property Submission Reference <span class="asterisk">*</span></span>
                            <input type="text" data-name="ppdSubmissionId" maxlength="12"
                                value="<?= esc($single_disposal['ppdSubmissionId'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Completion Date <span class="asterisk">*</span></span>
                            <input type="date" data-name="completionDate"
                                value="<?= esc($single_disposal['completionDate'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Disposal Proceeds <span class="asterisk">*</span></span>
                            <input type="number" data-name="disposalProceeds" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['disposalProceeds'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Acquisition Date <span class="asterisk"></span></span>
                            <input type="date" data-name="acquisitionDate"
                                value="<?= esc($single_disposal['acquisitionDate'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Acquisition Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="acquisitionAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['acquisitionAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Improvement Costs <span class="asterisk">*</span></span>
                            <input type="number" data-name="improvementCosts" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['improvementCosts'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Acquisition And Disposal Costs <span><span class="asterisk">*</span></span>
                            <input type="number" data-name="additionalCosts" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['additionalCosts'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Private Residence Relief <span class="asterisk">*</span></span>
                            <input type="number" data-name="prfAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['prfAmount'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Other Reliefs Amount <span class="asterisk">*</span></span>
                            <input type="number" data-name="otherReliefAmount" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['otherReliefAmount'] ?? '') ?>">
                        </label>
                    </div>


                    <div class="nested-input">
                        <label>Current Year Losses
                            <input type="number" data-name="lossesFromThisYear" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['lossesFromThisYear'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Losses From Previous Years
                            <input type="number" data-name="lossesFromPreviousYear" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['lossesFromPreviousYear'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Net Gain (either gain or loss required) <span class="asterisk">*</span></span>
                            <input type="number" data-name="amountOfNetGain" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['amountOfNetGain'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label><span>Net Loss (either gain or loss required) <span class="asterisk">*</span></span>
                            <input type="number" data-name="amountOfNetLoss" min="0" max="99999999999.99" step="0.01"
                                value="<?= esc($single_disposal['amountOfNetLoss'] ?? '') ?>">
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

<p><a class="hmrc-connection" href="/capital-gains/retrieve-all-residential-property-disposals">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>