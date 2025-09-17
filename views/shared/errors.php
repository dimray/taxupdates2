<?php if (!empty($errors)): ?>
    <div class="error-messages">
        <?php foreach ($errors as $error): ?>
            <p class="form-error"><?= esc($error) ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>