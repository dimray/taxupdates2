<?php require "shared/bsas-adjustments.php"; ?>

<form action="/business-source-adjustable-summary/process" method="POST" class="generic-form"
    id="zero-adjustments-form">


    <label class="inline-checkbox"><input type="checkbox" name="zeroAdjustments" id="zero-adjustments-toggle"
            value="true"><span>Set All
            Adjustments To Zero</span></label>

    <h3>Income</h3>

    <div class="form-input">
        <label for="turnover">Turnover</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="turnover" id="turnover"
            value="<?= $income['turnover'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="other">Other Income</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="other" id="other"
            value="<?= $income['other'] ?? '' ?>">
    </div>

    <h3>Expenses</h3>

    <div class="input-group">
        <div class="form-input">
            <label for="costOfGoods">Cost Of Goods</label>

            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="costOfGoods"
                id="costOfGoods" value="<?= $expenses['costOfGoods'] ?? '' ?>">
        </div>

        <div class="form-input">
            <label for="costOfGoodsDisallowable">Cost Of Goods Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="costOfGoodsDisallowable"
                id="costOfGoodsDisallowable" value="<?= $additions['costOfGoodsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="paymentsToSubcontractors">Payments To Subcontractors</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="paymentsToSubcontractors"
                id="paymentsToSubcontractors" value="<?= $expenses['paymentsToSubcontractors'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="paymentsToSubcontractorsDisallowable">Payments To Subcontractors Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="paymentsToSubcontractorsDisallowable" id="paymentsToSubcontractorsDisallowable"
                value="<?= $additions['paymentsToSubcontractorsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="wagesAndStaffCosts">Wages And Staff Costs</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="wagesAndStaffCosts"
                id="wagesAndStaffCosts" value="<?= $expenses['wagesAndStaffCosts'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="wagesAndStaffCostsDisallowable">Wages And Staff Costs Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="wagesAndStaffCostsDisallowable" id="wagesAndStaffCostsDisallowable"
                value="<?= $additions['wagesAndStaffCostsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="carVanTravelExpenses">Travel Expenses</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="carVanTravelExpenses"
                id="carVanTravelExpenses" value="<?= $expenses['carVanTravelExpenses'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="carVanTravelExpensesDisallowable">Travel Expenses Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="carVanTravelExpensesDisallowable" id="carVanTravelExpensesDisallowable"
                value="<?= $additions['carVanTravelExpensesDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="premisesRunningCosts">Premises Running Costs</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="premisesRunningCosts"
                id="premisesRunningCosts" value="<?= $expenses['premisesRunningCosts'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="premisesRunningCostsDisallowable">Premises Running Costs Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="premisesRunningCostsDisallowable" id="premisesRunningCostsDisallowable"
                value="<?= $additions['premisesRunningCostsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="maintenanceCosts">Maintenance Costs</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="maintenanceCosts"
                id="maintenanceCosts" value="<?= $expenses['maintenanceCosts'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="maintenanceCostsDisallowable">Maintenance Costs Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="maintenanceCostsDisallowable" id="maintenanceCostsDisallowable"
                value="<?= $additions['maintenanceCostsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="adminCosts">Admin Costs</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="adminCosts"
                id="adminCosts" value="<?= $expenses['adminCosts'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="adminCostsDisallowable">Admin Costs Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="adminCostsDisallowable"
                id="adminCostsDisallowable" value="<?= $additions['adminCostsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="interestOnBankOtherLoans">Loan Interest</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="interestOnBankOtherLoans"
                id="interestOnBankOtherLoans" value="<?= $expenses['interestOnBankOtherLoans'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="interestOnBankOtherLoansDisallowable">Loan Interest Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="interestOnBankOtherLoansDisallowable" id="interestOnBankOtherLoansDisallowable"
                value="<?= $additions['interestOnBankOtherLoansDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="financeCharges">Finance Charges</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="financeCharges"
                id="financeCharges" value="<?= $expenses['financeCharges'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="financeChargesDisallowable">Finance Charges Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="financeChargesDisallowable" id="financeChargesDisallowable"
                value="<?= $additions['financeChargesDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="irrecoverableDebts">Irrecoverable Debts</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="irrecoverableDebts"
                id="irrecoverableDebts" value="<?= $expenses['irrecoverableDebts'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="irrecoverableDebtsDisallowable">Irrecoverable Debts Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="irrecoverableDebtsDisallowable" id="irrecoverableDebtsDisallowable"
                value="<?= $additions['irrecoverableDebtsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="professionalFees">Professional Fees</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="professionalFees"
                id="professionalFees" value="<?= $expenses['professionalFees'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="professionalFeesDisallowable">Professional Fees Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="professionalFeesDisallowable" id="professionalFeesDisallowable"
                value="<?= $additions['professionalFeesDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="depreciation">Depreciation</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="depreciation"
                id="depreciation" value="<?= $expenses['depreciation'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="depreciationDisallowable">Depreciation Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="depreciationDisallowable"
                id="depreciationDisallowable" value="<?= $additions['depreciationDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="otherExpenses">Other Expenses</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="otherExpenses"
                id="otherExpenses" value="<?= $expenses['otherExpenses'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="otherExpensesDisallowable">Other Expenses Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="otherExpensesDisallowable"
                id="otherExpensesDisallowable" value="<?= $additions['otherExpensesDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="advertisingCosts">Advertising Costs</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="advertisingCosts"
                id="advertisingCosts" value="<?= $expenses['advertisingCosts'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="advertisingCostsDisallowable">Advertising Costs Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="advertisingCostsDisallowable" id="advertisingCostsDisallowable"
                value="<?= $additions['advertisingCostsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <div class="input-group">
        <div class="form-input">
            <label for="businessEntertainmentCosts">Business Entertainment</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="businessEntertainmentCosts" id="businessEntertainmentCosts"
                value="<?= $expenses['businessEntertainmentCosts'] ?? '' ?>">
        </div>
        <div class="form-input">
            <label for="businessEntertainmentCostsDisallowable">Business Entertainment Disallowable</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01"
                name="businessEntertainmentCostsDisallowable" id="businessEntertainmentCostsDisallowable"
                value="<?= $additions['businessEntertainmentCostsDisallowable'] ?? '' ?>">
        </div>
    </div>

    <!-- <div class="input-group">
        <div class="form-input">
            <label for="consolidatedExpenses">Consolidated Expenses</label>
            <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="consolidatedExpenses"
                id="consolidatedExpenses" value="<?= $expenses['consolidatedExpenses'] ?? '' ?>">
        </div>
    </div> -->

    <?php require ROOT_PATH . "views/shared/errors.php"; ?>


    <button class="form-button" type="submit">Submit</button>


</form>

<p><a href="/business-source-adjustable-summary/index">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_zero_adjustments_script = true; ?>