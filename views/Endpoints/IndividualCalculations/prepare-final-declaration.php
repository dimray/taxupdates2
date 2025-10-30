<p>The Final Declaration finalises your tax position for the year. Before you prepare your Final Declaration, please
    confirm the following:</p>

<div class="list">
    <ul>
        <li>All Cumulative Summaries have been submitted for each business.</li>
        <li>Details of all other income (e.g. interest, dividends) have been provided to
            HMRC.</li>
        <li>You do not have any additional information to provide.</li>
    </ul>
</div>


<form class="generic-form" action="/individual-calculations/confirm-prepare-final-declaration" method="POST">


    <input type="hidden" name="calculation_type" value="<?= $calculation_type ?>">


    <div class="inline-checkbox">
        <input type="checkbox" name="confirm_statements" id="confirm_statements" value="true" required>
        <label for="confirm_statements">I confirm the above statements are correct.</label>
    </div>

    <?php include ROOT_PATH . "/views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Prepare Final Declaration</button>
</form>

<p><a href="/obligations/final-declaration">Cancel</a></p>