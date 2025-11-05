<p>Enter Amounts for
    <? formatCamelCase($benefit_amount) ?>
</p>

<form action="/state-benefits/process-amend-state-benefit-amounts" method="POST" class="generic-form hmrc-connection">

    <div class="form-input">
        <label for="amount">Amount</label>
        <input type="number" name="amount" id="amount" min="0" max="99999999999.99" step="0.01" required>
    </div>

    <div class="form-input">
        <label for="tax_paid">Tax Paid</label>
        <input type="number" name="tax_paid" value="tax_paid" min="0" max="99999999999.99" step="0.01">
    </div>

    <input type="hidden" name="benefit_id" value="<?= esc($benefit_id) ?>">
    <input type="hidden" name="benefit_type" value="<?= esc($benefit_type) ?>">

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>
</form>