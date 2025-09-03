<form class="generic-form" action="/profile/update-name" method="POST">

    <div class="form-input">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= esc($name) ?>">
    </div>

    <?php include ROOT_PATH . "views/shared/errors.php"; ?>

    <button type="submit" class="form-button">Update</button>

</form>

<br>

<p><a href="/profile/show-profile">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>