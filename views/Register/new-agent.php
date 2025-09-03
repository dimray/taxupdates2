<?php $start_time = time(); ?>

<p>Not an agent? <a href="/register/new">Register as an Individual</a></p>

<form class="generic-form" action="/register/create" method="POST">

    <input type="hidden" name="csrf_token" value="<?= esc($csrf_token) ?>">

    <input type="hidden" name="start_time" value="<?= $start_time ?>">

    <input type="hidden" name="user_role" value="agent">

    <div class="form-input">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= esc($data['name'] ?? '') ?>">
    </div>


    <div class="form-input">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?= esc($data['email'] ?? '') ?>">
    </div>


    <label for="phone" class="phone" aria-hidden="true">Phone</label>
    <input type="text" name="phone" id="phone" class="phone" autocomplete="off">

    <div class="form-input">
        <label for="arn">Agent Services Account Number</label>
        <input type="text" name="arn" id="arn" value="<?= esc($data['arn'] ?? '') ?>">
    </div>


    <div class="form-input">
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
    </div>


    <div class="form-input">
        <label for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password">
    </div>

    <?php include ROOT_PATH . 'views/shared/errors.php'; ?>

    <button type="submit" class="form-button">Register</button>

</form>

<br>

<p><a href="/">Cancel</a></p>

<?php $include_scroll_to_errors_script = true; ?>