<p>Ignoring a State Benefit will exclude it from your tax calculation. Confirm you wish to ignore
    <?= esc(formatCamelCase($benefit_type ?? '')) ?>.
</p>

<form action="/state-benefits/ignore-state-benefit" method="POST">

    <input type="hidden" name="benefit_id" value="<?= esc($benefit_id) ?>">
    <input type="hidden" name="benefit_type" value="<?= esc($benefit_type) ?>">

    <button type="submit">Ignore</button>
</form>

<p><a href="/state-benefits/list-state-benefits">Cancel</a></p>