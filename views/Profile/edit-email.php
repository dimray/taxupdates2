<form class="generic-form" action="/profile/generate-and-send-code" method="POST">

    <div class="form-input">
        <label for="new_email">New Email</label>
        <input type="text" name="new_email" id="new_email" value="<?= esc($email) ?>">
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button class="form-button" type="submit">Update Email</button>

</form>

<br>

<p><a href="/profile/show-profile">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>