<p>Your device has not been recognised so a code has been sent to your email. To complete your login, please enter the
    code.
</p>

<?php include ROOT_PATH . "/views/shared/errors.php"; ?>

<?php if ($timer != 0): ?>

    <br>

    <div id="countdown-msg">
        <p>The entered code is not correct. Please wait <span id="countdown"
                data-start="<?= esc($timer) ?>"><?= esc($timer) ?></span>
            seconds and try again.</p>
        <br>
    </div>

<?php endif; ?>

<br>

<form class="generic-form" action="/session/check-verification-code" method="POST" id="collect-device-data">

    <div>
        <div class="form-input">
            <label for="authentication_code">Verification Code</label>
            <input type="text" name="authentication_code" id="authentication_code" autofocus>
        </div>

    </div>

    <input type="hidden" name="email" value="<?= esc($email) ?>" id="email">
    <input type="hidden" name="device_data" id="device_data">

    <?php if ($timer != 0): ?>

        <button class="form-button" id="countdown-button" type="submit">Login</button>

    <?php else: ?>

        <button class="form-button" type="submit">Login</button>

    <?php endif; ?>

</form>

<br>
<p><a href="/session/process-resend-code?email=<?= esc(urlencode($email)) ?>">Resend Code</a></p>


<?php $include_collect_user_data_script = true; ?>
<?php $include_countdown_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>