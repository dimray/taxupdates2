<form action="/employments-income/process-create-amend-non-paye-employment-income" class="generic-form" method="POST">

    <div class="form-input">
        <label for="tips">Total Amount Received</label>
        <input name="tips" type="number" min="0" max="99999999999.99" step="0.01" value="<?= esc($tips ?? '') ?>">
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>


<p><a href="/employments-income/index">Cancel</a></p>