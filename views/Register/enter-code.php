<p>To activate your account, please enter the code from your email.</p>

<?php include ROOT_PATH . "views/shared/errors.php"; ?>

<?php if ($timer != 0): ?>

    <div id="countdown-msg">
        <p>The entered code is not correct. Please wait <span id="countdown"
                data-start="<?= esc($timer) ?>"><?= esc($timer) ?></span>
            seconds and try again.</p>

    </div>

<?php endif; ?>



<form class="generic-form" action="/register/activate-account" method="POST" id="collect-device-data">

    <div class="form-input">
        <label for="authentication_code">Activation Code</label>
        <input type="text" name="authentication_code" id="authentication_code" autofocus>
    </div>

    <input type="hidden" name="email" value="<?= esc($email) ?>" id="email">
    <input type="hidden" name="device_data" id="device_data">


    <?php if ($timer != 0): ?>

        <button id="countdown-button" class="form-button">Activate</button>

    <?php else: ?>

        <button class="form-button" type="submit">Activate</button>

    <?php endif; ?>

</form>

<br>
<p><a href="/register/process-resend-code?email=<?= esc(urlencode($email)) ?>">Resend Code</a></p>


<?php $include_collect_user_data_script = true; ?>
<?php $include_countdown_script = true; ?>
<?php $include_scroll_to_errors_script = true; ?>