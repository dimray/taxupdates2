<?php require ROOT_PATH . "views/shared/mandatory-fields.php"; ?>

<form action="/capital-gains/process-create-amend-customer-added-residential-property-disposals" method="POST"
    class="generic-form">

    <?php if (isset($disposals)): ?>

    <h2>Disposals</h2>

    <div id="disposals-container">

        <?php foreach (($disposals ?? []) as $i => $disposal): ?>

        <div class="disposals-group field-container" data-group="disposals">

            <div class="nested-input">
                <label>Your Reference
                    <input type="text" data-name="customerReference" maxlength="90"
                        value="<?= esc($disposal['customerReference'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label><span>Disposal Date <span class="asterisk">*</span></span>
                    <input type="date" data-name="disposalDate" value="<?= esc($disposal['disposalDate'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label><span>Completion Date <span class="asterisk">*</span></span>
                    <input type="date" data-name="completionDate" value="<?= esc($disposal['completionDate'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label><span>Disposal Proceeds <span class="asterisk">*</span></span>
                    <input type="number" data-name="disposalProceeds" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['disposalProceeds'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label><span>Acquisition Date <span class="asterisk">*</span></span>
                    <input type="date" data-name="acquisitionDate"
                        value="<?= esc($disposal['acquisitionDate'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label><span>Acquisition Amount <span class="asterisk">*</span></span>
                    <input type="number" data-name="acquisitionAmount" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['acquisitionAmount'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label>Improvement Costs
                    <input type="number" data-name="improvementCosts" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['improvementCosts'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label>Acquisition And Disposal Costs
                    <input type="number" data-name="additionalCosts" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['additionalCosts'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label>Private Residence Relief Amount
                    <input type="number" data-name="prfAmount" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['prfAmount'] ?? '') ?>">
                </label>
            </div>


            <div class="nested-input">
                <label>Other Reliefs Amount
                    <input type="number" data-name="otherReliefAmount" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['otherReliefAmount'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label>Current Year Losses
                    <input type="number" data-name="lossesFromThisYear" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['lossesFromThisYear'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label>Losses From Previous Years
                    <input type="number" data-name="lossesFromPreviousYear" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['lossesFromPreviousYear'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label><span>Net Gain (either gain or loss is required) <span class="asterisk">*</span></span>
                    <input type="number" data-name="amountOfNetGain" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['amountOfNetGain'] ?? '') ?>">
                </label>
            </div>

            <div class="nested-input">
                <label><span>Net Loss (either gain or loss is required) <span class="asterisk">*</span></span>
                    <input type="number" data-name="amountOfNetLoss" min="0" max="99999999999.99" step="0.01"
                        value="<?= esc($disposal['amountOfNetLoss'] ?? '') ?>">
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


<p><a href="/capital-gains/retrieve-all-residential-property-disposals">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>