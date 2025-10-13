<form action="/disclosures/process-create-and-amend-disclosures" method="POST" class="generic-form">


    <?php if (isset($tax_avoidance)): ?>

        <h2>Tax Avoidance Schemes</h2>

        <div id="tax-avoidance-container">

            <?php foreach ($tax_avoidance as $scheme): ?>

                <div class="tax-avoidance-group field-container" data-group="taxAvoidance">

                    <div class="nested-input">
                        <label>Scheme Reference Number
                            <input type="text" data-name="srn" maxlength="8" value="<?= esc($scheme['srn'] ?? '') ?>"
                                placeholder="12345678">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Tax Year In Which Expected Advantage Arises
                            <input type="text" data-name="taxYear" maxlength="7" value="<?= esc($scheme['taxYear'] ?? '') ?>"
                                placeholder="YYYY-YY">
                        </label>
                    </div>

                    <br>


                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <h2>Voluntary Class 2 NIC Contributions</h2>

    <label class="inline-checkbox label-text">
        <input type="checkbox" name="class2Nics" value="1"
            <?= (!empty($class_2_nics['class2VoluntaryContributions'])) ? "checked" : "" ?>>
        <span>Tick Box If Making Voluntary Class 2 Contributions</span>
    </label>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>



<p><a href="/disclosures/retrieve-disclosures">Cancel</a></p>

<?php $include_add_another_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>