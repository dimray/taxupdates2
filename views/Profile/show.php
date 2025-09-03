<dl>
    <dt>Name</dt>
    <dd>
        <?= esc($profile['name']) ?>
        <a href="/profile/edit-name?name=<?= esc(urlencode($profile['name'] ?? '')) ?>">Edit</a>
    </dd>
    <dt>Email</dt>
    <dd>
        <?= esc($profile['email']) ?>
        <a href="/profile/edit-email?email=<?= esc(urlencode($profile['email'] ?? '')) ?>">Edit</a>
    </dd>

    <?php if (isset($profile['nino'])): ?>

        <dt>NI Number</dt>
        <dd><?= esc($profile['nino']) ?></dd>
    <?php elseif (isset($profile['arn'])): ?>

        <dt>Agent Reference</dt>
        <dd><?= esc($profile['arn']) ?></dd>
    <?php endif; ?>
</dl>



<?php if ($_SESSION['user_role'] === "individual"): ?>

    <p><a href="/submissions/get-submissions">View Submissions Made</a></p>

<?php endif; ?>



<?php if (!$is_admin): ?>
    <form action="/profile/confirm-delete-profile" method="POST">
        <button class="confirm-delete" type="submit">Delete Account</button>
    </form>
<?php endif; ?>