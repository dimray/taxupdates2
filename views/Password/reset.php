<form class="generic-form" action="/password/create" method="POST">

    <div class="form-input">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?= esc($data['email'] ?? '') ?>">
    </div>

    <input type="hidden" name="csrf_token" value="<?= esc($csrf_token) ?>">

    <?php include ROOT_PATH . 'views/shared/errors.php'; ?>

    <button class="form-button" type="submit">Submit</button>

</form>

<p><a href="/login">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>