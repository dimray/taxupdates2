<p>To confirm your new email address, please enter the code that has been sent to your new email address, and also your
    password.</p>

<?php include ROOT_PATH . "views/shared/errors.php"; ?>

<?php if ($timer != 0): ?>

    <div id="countdown-msg">
        <p>The entered code is not correct. Please wait <span id="countdown"
                data-start="<?= esc($timer) ?>"><?= esc($timer) ?></span>
            seconds and try again.</p>

    </div>

<?php endif; ?>

<form class="generic-form" action="/profile/update-email" method="POST">

    <div class="form-input">
        <label for="authentication_code">Code</label>
        <input type="text" name="authentication_code" id="authentication_code">
    </div>

    <div class="form-input">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>

    <button class="form-button" id="countdown-button">Confirm New Email</button>

</form>

<?php $include_countdown_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>