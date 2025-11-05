<form action="/savings/process-add-uk-savings-account" method="POST" class="generic-form hmrc-connection">

    <div class="form-input">
        <label for="account_name">Account Name</label>
        <input type="text" name="account_name" id="account_name" maxlength="32" required>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Submit</button>
</form>

<p><a class="hmrc-connection" href="/savings/list-uk-savings-accounts">Cancel</a></p>