<form action="/savings/process-edit-uk-savings-account-name" method="POST" class="generic-form">

    <div class="form-input">

        <input type="hidden" name="account_id" value="<?= esc($account_id) ?>">

        <label for="account_name">Account Name</label>
        <input type="text" name="account_name" id="account_name" value="<?= esc($account_name) ?>" maxlength="32"
            required>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a href="/savings/list-uk-savings-accounts">Cancel</a></p>