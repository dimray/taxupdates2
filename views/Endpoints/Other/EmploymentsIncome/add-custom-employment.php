<form class="generic-form hmrc-connection" action="/employments-income/process-add-custom-employment" method="POST">

    <div class="form-input">
        <label for="employer-name">Employer Name</label>
        <input type="text" name="employer_name" id="employer-name" value="<?= $employer_data['employer_name'] ?? '' ?>"
            maxlength="73">
    </div>

    <div class="form-input">
        <label for="start-date">Start Date</label>
        <input type="date" name="start_date" id="start-date" value="<?= $employer_data['start_date'] ?? '' ?>" required>
    </div>

    <div class="form-input">
        <label for="occupational_pension">Occupational Pension</label>
        <div class="checkbox-flex">
            <input type="checkbox" name="occupational_pension" value="true"
                <?= !empty($employer_data['occupational_pension']) ? 'checked' : '' ?>>
            <span>Tick the box if this income is from an Occupational Pension</span>
        </div>

    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>

</form>