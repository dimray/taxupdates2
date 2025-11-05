<h2>Tax Year: <?= $tax_year ?></h2>

<?php if (!empty($account_interest)): ?>

<?php displayArrayAsList($account_interest); ?>

<form action="/savings/create-amend-uk-savings-account-annual-summary" method="GET">
    <input type="hidden" name="untaxed_interest" value="<?= esc($account_interest['untaxedUkInterest'] ?? '') ?>">
    <input type="hidden" name="taxed_interest" value="<?= esc($account_interest['taxedUkInterest'] ?? '') ?>">
    <input type="hidden" name="account_id" value="<?= esc($account_id ?? '') ?>">
    <input type="hidden" name="account_name" value="<?= esc($account_name ?? '') ?>">
    <button type="submit" class="link">Edit Interest</button>
</form>

<?php else: ?>

<p>No interest found</p>

<form action="/savings/create-amend-uk-savings-account-annual-summary" method="GET">
    <input type="hidden" name="account_id" value="<?= esc($account_id ?? '') ?>">
    <input type="hidden" name="account_name" value="<?= esc($account_name ?? '') ?>">
    <button type="submit" class="link">Add Interest</button>
</form>

<?php endif; ?>

<form action="/savings/edit-uk-savings-account-name" method="GET">
    <input type="hidden" name="account_id" value="<?= esc($account_id ?? '') ?>">
    <input type="hidden" name="account_name" value="<?= esc($account_name ?? '') ?>">
    <button type="submit" class="link">Edit Account Name</button>
</form>


<p><a class="hmrc-connection" href="/savings/list-uk-savings-accounts">Accounts</a></p>