<?php require "shared/bsas-adjustments.php"; ?>

<?php if (!$add_another): ?>

    <p>If you need to enter adjustments for more than one country, submit your data for one country and you
        will then be given the option to add other countries.</p>

<?php endif; ?>

<form action="/business-source-adjustable-summary/process" method="POST" class="generic-form"
    id="zero-adjustments-form">

    <?php if (!$add_another): ?>

        <label class="inline-checkbox"><input type="checkbox" name="zeroAdjustments" id="zero-adjustments-toggle"
                value="true"><span>Set All
                Adjustments To Zero</span></label>

    <?php endif; ?>

    <?php if ($country_or_property === "country"): ?>
        <h2>Country</h2>

        <?php include ROOT_PATH . "views/shared/select-country.php"; ?>

    <?php else: ?>

        <h2>Property</h2>

        <?php include ROOT_PATH . "views/shared/select-foreign-property.php"; ?>

    <?php endif; ?>


    <h3>Income</h3>

    <div class="form-input">
        <label for="totalRentsReceived">Rent Received</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="totalRentsReceived"
            id="totalRentsReceived" value="<?= $income['totalRentsReceived'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="premiumsOfLeaseGrant">Lease Premiums</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="premiumsOfLeaseGrant"
            id="premiumsOfLeaseGrant" value="<?= $income['premiumsOfLeaseGrant'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="otherPropertyIncome">Other Income</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="otherPropertyIncome"
            id="otherPropertyIncome" value="<?= $income['otherPropertyIncome'] ?? '' ?>">
    </div>

    <h3>Expenses</h3>

    <?= $expenses['consolidatedExpenses'] ?? '' ?>


    <div class="form-input">
        <label for="premisesRunningCosts">Premises Costs</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="premisesRunningCosts"
            id="premisesRunningCosts" value="<?= $expenses['premisesRunningCosts'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="repairsAndMaintenance">Repairs And Maintenance</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="repairsAndMaintenance"
            id="repairsAndMaintenance" value="<?= $expenses['repairsAndMaintenance'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="financialCosts">Deductible Finance Costs</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="financialCosts"
            id="financialCosts" value="<?= $expenses['financialCosts'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="professionalFees">Professional Fees</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="professionalFees"
            id="professionalFees" value="<?= $expenses['professionalFees'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="costOfServices">Services</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="costOfServices"
            id="costOfServices" value="<?= $expenses['costOfServices'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="residentialFinancialCost">Residential Finance Costs</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="residentialFinancialCost"
            id="residentialFinancialCost" value="<?= $expenses['residentialFinancialCost'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="other">Other Costs</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="other" id="other"
            value="<?= $expenses['other'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="travelCosts">Travel Costs</label>
        <input type="number" min="-99999999999.99" max="99999999999.99" step="0.01" name="travelCosts" id="travelCosts"
            value="<?= $expenses['travelCosts'] ?? '' ?>">
    </div>

    <?php require ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/business-source-adjustable-summary/index">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_zero_adjustments_script = true; ?>