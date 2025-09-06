<form class="generic-form" action="/session/create" method="POST" id="collect-device-data">
    <div>
        <div class="form-input">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?= esc($email ?? '') ?>">
        </div>

        <div class="form-input">
            <label for="password">Password</label>
            <input type="password" name="password" id="password">
        </div>

    </div>

    <input type="hidden" name="device_data" id="device_data">

    <input type="hidden" name="csrf_token" value="<?= esc($csrf_token) ?>">

    <?php include ROOT_PATH . 'views/shared/errors.php'; ?>

    <button class="form-button" type="submit">Log In</button>

</form>

<br>

<p><a href="/">Cancel</a></p>

<p><a href="/password/reset">Reset password</a></p>


<?php $include_collect_user_data_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>