<form action="/expenses/process-create-and-amend-other-expenses" method="POST" class="generic-form">

    <h2>Payments To Trade Unions For Death Benefits</h2>

    <div class="nested-input">
        <label>Your Reference
            <input type="text" name="paymentsToTradeUnionsForDeathBenefits[customerReference]" maxlength="90"
                value="<?= esc($payments_to_trade_unions['customerReference'] ?? '') ?>">
        </label>
    </div>


    <div class="nested-input">
        <label>Amount
            <input type="number" name="paymentsToTradeUnionsForDeathBenefits[expenseAmount]" min="0"
                max="99999999999.99" step="0.01" value="<?= esc($payments_to_trade_unions['expenseAmount'] ?? '') ?>">
        </label>
    </div>

    <h2>Patent Royalties Payments</h2>

    <div class="nested-input">
        <label>Your Reference
            <input type="text" name="patentRoyaltiesPayments[customerReference]" maxlength="90"
                value="<?= esc($patent_royalties['customerReference'] ?? '') ?>">
        </label>
    </div>


    <div class="nested-input">
        <label>Amount
            <input type="number" name="patentRoyaltiesPayments[expenseAmount]" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($patent_royalties['expenseAmount'] ?? '') ?>">
        </label>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a href="/expenses/retrieve-other-expenses">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>