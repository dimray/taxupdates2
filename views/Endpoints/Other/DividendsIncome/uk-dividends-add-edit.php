<form class="generic-form hmrc-connection" action="/dividends-income/process-create-amend-uk-dividends-annual-summary"
    method="POST">

    <div class="form-input">
        <label for="ukDividends">UK Dividends</label>
        <input type="number" min="0" max="99999999999.99" step="0.01" name="ukDividends" id="ukDividends"
            value="<?= $dividends['ukDividends'] ?? '' ?>">
    </div>

    <div class="form-input">
        <label for="otherUkDividends">Other UK Dividends</label>
        <input type="number" min="0" max="99999999999.99" step="0.01" name="otherUkDividends" id="otherUkDividends"
            value="<?= $dividends['otherUkDividends'] ?? '' ?>">
        <p class="small">Dividends from Authorised unit trusts, Open-ended investment companies or Investment trusts.
        </p>
    </div>


    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<br>

<p><a class="hmrc-connection" href="/dividends-income/retrieve-uk-dividends-income-annual-summary">Cancel</a></p>