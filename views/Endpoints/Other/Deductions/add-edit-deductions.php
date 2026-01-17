<form action="/deductions/process-create-and-amend-deductions" method="POST" class="generic-form">

    <?php if (isset($seafarers)): ?>

        <h2>Seafarers Earnings Deduction</h2>

        <div id="seafarers-container">

            <?php foreach (($seafarers ?? []) as $index => $ship): ?>

                <div class="seafarers-group field-container" data-group="seafarers">

                    <div class="nested-input">
                        <label>Your Reference
                            <input type="text" data-name="customerReference" maxlength="90"
                                name="seafarers[<?= $index ?>][customerReference]"
                                value="<?= esc($ship['customerReference'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Amount Deducted
                            <input type="number" data-name="amountDeducted" min="0" max="99999999999.99" step="0.01"
                                name="seafarers[<?= $index ?>][amountDeducted]"
                                value="<?= esc($ship['amountDeducted'] ?? '') ?>" required>
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>Name Of Ship
                            <input type="text" data-name="nameOfShip" maxlength="90" name="seafarers[<?= $index ?>][nameOfShip]"
                                value="<?= esc($ship['nameOfShip'] ?? '') ?>">
                        </label>
                    </div>

                    <div class="nested-input">
                        <label>From Date</label>
                        <input type="date" data-name="fromDate" name="seafarers[<?= $index ?>][fromDate]"
                            value="<?= esc($ship['fromDate'] ?? '') ?>" required>
                    </div>

                    <div class="nested-input">
                        <label>To Date</label>
                        <input type="date" data-name="toDate" name="seafarers[<?= $index ?>][toDate]"
                            value="<?= esc($ship['toDate'] ?? '') ?>" required>
                    </div>

                    <br>
                    <hr>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <?php include ROOT_PATH . "/views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<br>

<p><a href="/deductions/retrieve-deductions">Cancel</a></p>

<?php $include_add_deductions_script = true; ?>
<?php $include_scroll_to_error_script = true; ?>