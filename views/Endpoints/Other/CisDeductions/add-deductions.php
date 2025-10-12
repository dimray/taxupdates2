<form class="generic-form" action="/cis-deductions/process-create-cis-deductions    " method="POST">

    <div class="form-input">
        <label for="contractorName">Contractor Name</label>
        <input type="text" name="contractorName" value="<?= esc($cis_deductions['contractorName'] ?? '') ?>" required>
    </div>

    <div class="form-input">
        <label for="employerRef">Contractor PAYE Reference</label>
        <input type="text" name="employerRef" value="<?= esc($cis_deductions['employerRef'] ?? '') ?>" required
            placeholder="123/AB456">
    </div>

    <h3>Amounts</h3>

    <?php for ($i = 0; $i < 12; $i++): ?>

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
                    name="cisDeductions[<?= $i ?>][grossAmountPaid]"
                    value="<?= esc($cis_deductions['cisDeductions'][$i]['grossAmountPaid'] ?? '') ?>">
            </label>

        </div>

        <div class="nested-input">
            <label>
                <span>Cost Of Materials</span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="cisDeductions[<?= $i ?>][costOfMaterials]"
                    value="<?= esc($cis_deductions['cisDeductions'][$i]['costOfMaterials'] ?? '') ?>">
            </label>
        </div>

        <div class="nested-input">
            <label>
                <span>CIS Deducted</span>
                <input type="number" min="0" max="99999999999.99" step="0.01"
                    name="cisDeductions[<?= $i ?>][deductionAmount]"
                    value="<?= esc($cis_deductions['cisDeductions'][$i]['deductionAmount'] ?? '') ?>">
            </label>

        </div>
    </div>

    <hr>

    <?php endfor; ?>


    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/cis-deductions/retrieve-cis-deductions">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>