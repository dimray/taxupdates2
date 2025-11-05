<form action="/dividends-income/process-create-amend-directorship-and-dividend-information" method="POST"
    class="generic-form hmrc-connection">

    <div>
        <label class="inline-checkbox label-text">
            <input type="checkbox" name="companyDirector" value="1"
                <?= (isset($director_dividends['companyDirector']) && ($director_dividends['companyDirector'] === true || $director_dividends['companyDirector'] === 'true' || $director_dividends['companyDirector'] === '1')) ? "checked" : "" ?>>
            <span>Tick If Company Director</span>
        </label>
    </div>

    <div>
        <label class="inline-checkbox label-text">
            <input type="checkbox" name="closeCompany" value="1"
                <?= (isset($director_dividends['closeCompany']) && ($director_dividends['closeCompany'] === true || $director_dividends['closeCompany'] === 'true' || $director_dividends['closeCompany'] === '1')) ? "checked" : "" ?>>
            <span>Tick If Close Company</span>
        </label>
    </div>

    <div class="form-input">
        <label for="directorshipCeasedDate">Date Directorship Ceased</label>
        <input type="date" name="directorshipCeasedDate" id="directorshipCeasedDate"
            value="<?= esc($director_dividends['directorshipCeasedDate'] ?? '') ?>">
    </div>

    <div class="form-input">
        <label for="companyName">Company Name</label>
        <input type="text" name="companyName" id="companyName"
            value="<?= esc($director_dividends['companyName'] ?? '') ?>" maxlength="160">
    </div>

    <div class="form-input">
        <label for="companyNumber">Company Number</label>
        <span class="required">Must be 8 digits or 2 letters plus 6 digits</span>
        <input type="text" name="companyNumber" id="companyNumber"
            value="<?= esc($director_dividends['companyNumber'] ?? '') ?>" pattern="(?:\d{8}|[A-Za-z]{2}\d{6})">
    </div>

    <div class="form-input">
        <label for="shareholding">Percentage Shareholding</label>
        <input type="number" min="0" max="100" step="0.01" name="shareholding" id="shareholding"
            value="<?= esc($director_dividends['shareholding'] ?? '') ?>">
    </div>

    <div class="form-input">
        <label for="dividendReceived">Dividend Received (£)</label>
        <input type="number" min="0" max="99999999999.99" step="0.01" name="dividendReceived" id="dividendReceived"
            value="<?= esc($director_dividends['dividendReceived'] ?? '') ?>">
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <input type="hidden" name="employment_id" value="<?= $employment_id ?>">
    <input type="hidden" name="employer_name" value="<?= $employer_name ?>">

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a class="hmrc-connection" href="/dividends-income/retrieve-directorship-and-dividend-information">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>
<?php $include_add_another_script = true; ?>