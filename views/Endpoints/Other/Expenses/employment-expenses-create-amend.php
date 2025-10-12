<form action="/expenses/process-create-and-amend-employment-expenses" method="POST" class="generic-form">

    <div class="nested-input">
        <label>Travel Costs
            <input type="number" name="businessTravelCosts" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['businessTravelCosts'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Tools And Work Clothes - Actual Cost
            <input type="number" name="jobExpenses" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['jobExpenses'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Tools And Work Clothes - Flat Rate
            <input type="number" name="flatRateJobExpenses" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['flatRateJobExpenses'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Professional Fees And Subscriptions
            <input type="number" name="professionalSubscriptions" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['professionalSubscriptions'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Accommodation And Meals
            <input type="number" name="hotelAndMealExpenses" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['hotelAndMealExpenses'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Other Expenses And Capital Allowances
            <input type="number" name="otherAndCapitalAllowances" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['otherAndCapitalAllowances'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Mileage And Vehicle Expenses
            <input type="number" name="vehicleExpenses" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['vehicleExpenses'] ?? '') ?>">
        </label>
    </div>

    <div class="nested-input">
        <label>Mileage Shortfall <span class="small">(if employer reimburses less than tax-approved rate)</span>
            <input type="number" name="mileageAllowanceRelief" min="0" max="99999999999.99" step="0.01"
                value="<?= esc($expenses['mileageAllowanceRelief'] ?? '') ?>">
        </label>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a href="/expenses/retrieve-employment-expenses">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>