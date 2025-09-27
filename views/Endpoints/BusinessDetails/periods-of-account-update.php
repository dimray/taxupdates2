<form class="generic-form" action="/business-details/process-create-update-periods-of-account" method="POST">


    <div class="form-input">
        <label for="period_1_start">Period Start</label>
        <input type="date" name="period_1_start" id="period_1_start"
            value="<?= $periods_of_account[0]['startDate'] ?? '' ?>">

        <label for="period_1_end">Period End</label>
        <input type="date" name="period_1_end" id="period_1_end" value="<?= $periods_of_account[0]['endDate'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="period_2_start">Period Start</label>
        <input type="date" name="period_2_start" id="period_2_start"
            value="<?= $periods_of_account[1]['startDate'] ?? '' ?>">

        <label for="period_2_end">Period End</label>
        <input type="date" name="period_2_end" id="period_2_end" value="<?= $periods_of_account[1]['endDate'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="period_3_start">Period Start</label>
        <input type="date" name="period_3_start" id="period_3_start"
            value="<?= $periods_of_account[2]['startDate'] ?? '' ?>">

        <label for="period_3_end">Period End</label>
        <input type="date" name="period_3_end" id="period_3_end" value="<?= $periods_of_account[2]['endDate'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="period_4_start">Period Start</label>
        <input type="date" name="period_4_start" id="period_4_start"
            value="<?= $periods_of_account[3]['startDate'] ?? '' ?>">

        <label for="period_4_end">Period End</label>
        <input type="date" name="period_4_end" id="period_4_end" value="<?= $periods_of_account[3]['endDate'] ?? '' ?>">
    </div>



    <button type="submit" class="form-button">Submit</button>

</form>

<p><a href="/business-details/retrieve-business-details">Cancel</a></p>