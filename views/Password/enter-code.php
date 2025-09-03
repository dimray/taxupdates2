<p>To reset your password, enter the code from your email, along with your new
    password.</p>

<?php include ROOT_PATH . 'views/shared/errors.php'; ?>

<?php if ($timer != 0): ?>

    <div id="countdown-msg">
        <p>The entered code is not correct. Please wait <span id="countdown"
                data-start="<?= esc($timer) ?>"><?= esc($timer) ?></span>
            seconds and try again.</p>

    </div>

<?php endif; ?>



<form class="generic-form" action="/password/complete-reset" method="POST">

    <input type="hidden" name="email" value="<?= esc($email) ?>">

    <input type="hidden" name="csrf_token" value="<?= esc($csrf_token) ?>">

    <div class="form-input">
        <label for="authentication_code">Password Reset Code</label>
        <input type="text" name="authentication_code" id="authentication_code">
    </div>

    <div class="form-input">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="form-input">
        <label for="confirm_password">Confirm New Password</label>
        <input type="password" name="confirm_password" id="confirm_password">
    </div>

    <button class="form-button" id="countdown-button" type="submit">Submit</button>

</form>

<br>

<p><a href="/login">cancel</a></p>

<p><a href="/password/process-resend-code?email=<?= esc(urlencode($email)) ?>">Resend Password Reset Code</a></p>

<?php $include_countdown_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>