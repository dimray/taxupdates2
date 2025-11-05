<?php displayArrayAsList($contractor); ?>

<hr>

<form class="generic-form hmrc-connection" action="/cis-deductions/process-amend-cis-deductions" method="POST">

    <input type="hidden" name="contractorName" value="<?= $contractor['contractorName'] ?? '' ?>">
    <input type="hidden" name="employerRef" value="<?= $contractor['employerRef'] ?? '' ?>">
    <input type="hidden" name="submissionId" value="<?= $contractor['submissionId'] ?? '' ?>">

    <?php for ($i = 0; $i < 12; $i++): ?>

        <?php
        $cis_deducted = "";
        $cost_of_materials = "";
        $gross_amount_paid = "";

        foreach ($period_data as $period) {
            if (
                substr($period['deductionFromDate'], 5) === substr($monthly_periods[$i]['from'], 5) &&
                substr($period['deductionToDate'], 5) === substr($monthly_periods[$i]['to'], 5)
            ) {
                $cis_deducted = $period['deductionAmount'] ?? "";
                $cost_of_materials = $period['costOfMaterials'] ?? "";
                $gross_amount_paid = $period['grossAmountPaid'] ?? "";
                break; // Found the matching period, stop looping
            }
        } ?>

        <input type="hidden" name="cisDeductions[<?= $i ?>][deductionFromDate]" value="<?= $monthly_periods[$i]['from'] ?>">
        <input type="hidden" name="cisDeductions[<?= $i ?>][deductionToDate]" value="<?= $monthly_periods[$i]['to'] ?>">

        <div class="grouped-inputs">
            <span>
                <h4><?= formatDate($monthly_periods[$i]['from']) ?> to
                    <?= formatDate($monthly_periods[$i]['to']) ?></h4>
            </span>

            <div class="nested-input">
                <label>
                    <span>Gross Amount</span>
                    <input type="number" min="0" max="99999999999.99" step="0.01"
                        name="cisDeductions[<?= $i ?>][grossAmountPaid]" value="<?= esc($gross_amount_paid) ?>">
                </label>
            </div>

            <div class="nested-input">
                <label>
                    <span>Cost Of Materials</span>
                    <input type="number" min="0" max="99999999999.99" step="0.01"
                        name="cisDeductions[<?= $i ?>][costOfMaterials]" value="<?= esc($cost_of_materials) ?>">
                </label>
            </div>

            <div class="nested-input">
                <label>
                    <span>CIS Deducted</span>
                    <input type="number" min="0" max="99999999999.99" step="0.01"
                        name="cisDeductions[<?= $i ?>][deductionAmount]" value="<?= esc($cis_deducted) ?>">
                </label>
            </div>

        </div>

        <hr>

    <?php endfor; ?>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a class="hmrc-connection" href="/cis-deductions/retrieve-cis-deductions">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>