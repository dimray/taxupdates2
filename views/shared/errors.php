<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $error): ?>
        <p class="form-error"><?= esc($error) ?></p>
    <?php endforeach; ?>
<?php endif; ?>