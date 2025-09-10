<p>Each firm has only one Admin. If you transfer the role to <?= $name ?> you will no longer be your firm's Admin.

<p>The Admin deals with billing, is able to remove users, and can delete the firm's
    account.
</p>

<p>Please type in your password to confirm transfer of the Admin role to <?= $name ?>.</p>

<form class="generic-form" action="/firm/transfer-admin" method="POST">

    <div>
        <input type="hidden" name="agent_user_id" value="<?= $agent_user_id ?>">

        <div class="form-input">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/firm/show-firm">Cancel</a></p>