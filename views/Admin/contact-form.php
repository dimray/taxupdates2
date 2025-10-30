<div class="email">
    <h3>Email:</h3>
    <p><a href="mailto:support@taxupdates.co.uk">support@taxupdates.co.uk</a></p>
</div>

<h3>Contact Form</h3>

<?php $start_time = time(); ?>

<?php include ROOT_PATH . "/views/shared/errors.php"; ?>

<form action="/admin/submit-form" method="POST" class="generic-form">

    <input type="hidden" name="start_time" value="<?= $start_time ?>">

    <div class="form-input">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?= esc($data['name'] ?? '') ?>" autocomplete="off">
    </div>

    <div class="form-input">
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?= esc($data['email'] ?? '') ?>" autocomplete="off">

    </div>

    <label for="phone" class="phone" aria-hidden="true">Phone</label>
    <input type="text" name="phone" id="phone" class="phone" autocomplete="off">

    <div class="form-input">
        <label for="message">Message</label>

        <textarea rows="3" name="message" id="message" autocomplete="off"
            maxlength="300"><?= esc($data['message'] ?? '') ?></textarea>
    </div>

    <p id="charCount" class="small">300 characters remaining</p>

    <button type="submit" class="form-button">Send</button>
</form>





<?php $include_message_length_script = true; ?>

<?php $include_disable_form_send_button_script = true; ?>